import { ref, computed } from 'vue';
import { defineStore } from 'pinia';
import { api } from '../api.js';
import { useContextStore } from './context.js';

export const useDecksStore = defineStore(
    'decks',
    () => {
        const contextStore = useContextStore();

        const records = ref(new Map());
        const isLoading = ref(false);
        const errors = ref(false);

        function storeRecord(newRecord) {
            records.value.set(newRecord.id, newRecord);
        }

        const all = computed(() => {
            return [...records.value.values()];
        });

        const byContext = computed(() => {
            const context = contextStore.id;
            return all.value.filter((deck) => deck.context.data.id === context);
        });

        async function fetchContext() {
            isLoading.value = true;
            try {
                const { data } = await api.fetch(
                    `${contextStore.type}/${contextStore.id}/lernkarten-decks`,
                    { params: { include: 'folder,owner,shared-with,template.owner' } }
                );
                data.forEach(storeRecord);
            } catch (errors) {
                console.error('fetching decks', errors);
                errors.value = errors;
            }
            isLoading.value = false;
        }

        async function fetchById(id) {
            isLoading.value = true;
            try {
                const { data } = await api.fetch(`lernkarten-decks/${id}`, {
                    params: { include: 'folder,owner,shared-with,template.owner' },
                });
                storeRecord(data);
            } catch (errors) {
                console.error('fetching decks', errors);
                errors.value = errors;
            }
            isLoading.value = false;
        }

        function byId(id) {
            return records.value.get(id);
        }

        async function copyDeck(deck) {
            const { data } = await api.post(
                `lernkarten-decks/${deck.id}/copy`,
                deck,
            );

            return fetchById(data.id);
        }

        async function createDeck(folder, name, description) {
            const record = {
                name,
                description,
                context: { data: { id: contextStore.id, type: contextStore.type } },
                folder: {
                    data: folder ? { id: folder.id, type: 'lernkarten-folders' } : null,
                },
            };
            const { data } = await api.create('lernkarten-decks', record);
            storeRecord(data);

            return data;
        }

        function deleteDeck(deck) {
            return api
                .delete('lernkarten-decks', deck.id)
                .then(() => records.value.delete(deck.id));
        }

        return {
            all,
            byContext,
            byId,
            copyDeck,
            createDeck,
            deleteDeck,
            errors,
            fetchById,
            fetchContext,
            isLoading,
        };
    },
    {
        persist: true,
    }
);
