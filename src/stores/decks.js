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
        const error = ref(false);

        const allDecks = computed(() => {
            return [...records.value.values()];
        });

        const byContext = computed(() => {
            const context = contextStore.id;
            return allDecks.value.filter((deck) => deck.context.data.id === context);
        });

        async function fetchContext() {
            isLoading.value = true;

            const contextType = contextStore.type;

            const { data } = await api.fetch(
                `${contextStore.type}/${contextStore.id}/lernkarten-decks`,
                { params: { include: 'owner' } }
            );
            isLoading.value = false;
            data.forEach(storeRecord);
        }

        async function fetchById(id) {
            isLoading.value = true;
            try {
                const { data } = await api.fetch(`lernkarten-decks/${id}`, {
                    params: { include: 'folder,owner' },
                });
                storeRecord(data);
            } catch (error) {
                error.value = error;
            }
            isLoading.value = false;
        }

        function byId(id) {
            return records.value.get(id);
        }

        async function createDeck(folder, name, description) {
            const { data } = await api.create('lernkarten-decks', {
                name,
                description,
                context: { data: { id: contextStore.id, type: contextStore.type } },
                folder: {
                    data: folder ? { data: { id: folder.id, type: 'lernkarten-folders' } } : null,
                },
            });
            storeRecord(data);

            return data;
        }

        function storeRecord(newRecord) {
            records.value.set(newRecord.id, newRecord);
        }

        return {
            allDecks,
            byContext,
            byId,
            createDeck,
            error,
            fetchById,
            fetchContext,
            isLoading,
        };
    },
    {
        persist: true,
    }
);
