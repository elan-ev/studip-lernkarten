import { ref, computed } from 'vue';
import { defineStore } from 'pinia';
import { api } from '../api.js';
import { useContextStore } from './context.js';
import { useDecksStore } from './decks.js';

export const useSharedDecksStore = defineStore(
    'sharedDecks',
    () => {
        const contextStore = useContextStore();
        const decksStore = useDecksStore();

        const records = ref(new Map());
        const isLoading = ref(false);
        const errors = ref(false);

        function storeRecord(newRecord) {
            records.value.set(newRecord.id, newRecord);
        }

        const all = computed(() => {
            return [...records.value.values()];
        });

        async function fetchById(id) {
            isLoading.value = true;
            try {
                const { data } = await api.fetch(`lernkarten-shared-decks/${id}`, {
                    params: { include: 'colearning-deck.owner,deck,sharer' },
                });
                storeRecord(data);
            } catch (errors) {
                console.error('fetching shared deck', errors);
                errors.value = errors;
            }
            isLoading.value = false;
        }

        async function fetchContext() {
            isLoading.value = true;

            const { data } = await api.fetch(
                `${contextStore.type}/${contextStore.id}/lernkarten-shared-decks`,
                { params: { include: 'colearning-deck.owner,deck,sharer' } },
            );
            isLoading.value = false;
            data.forEach(storeRecord);
        }

        function byId(id) {
            return records.value.get(id);
        }

        function shareDeckWithCourse(deck, course) {
            return createSharedDeck(deck, course);
        }

        function shareDeckWithUserIds(deck, userIds) {
            return Promise.all(userIds.map((id) => createSharedDeck(deck, { id, type: 'users' })));
        }

        async function createSharedDeck(deck, recipient) {
            const record = {
                deck: { data: { id: deck.id, type: 'lernkarten-decks' } },
                recipient: { data: { id: recipient.id, type: recipient.type } },
            };
            const { data } = await api.create('lernkarten-shared-decks', record);
            storeRecord(data);

            return data;
        }

        async function colearn(sharedDeck) {
            const { data } = await api.post(
                `lernkarten-shared-decks/${sharedDeck.id}/colearn`,
                sharedDeck,
            );

            return decksStore.fetchById(data.id).then(() => fetchById(sharedDeck.id));
        }

        async function copy(sharedDeck) {
            const { data } = await api.post(
                `lernkarten-shared-decks/${sharedDeck.id}/copy`,
                sharedDeck,
            );

            return decksStore.fetchById(data.id);
        }

        return {
            all,
            byId,
            colearn,
            copy,
            errors,
            fetchById,
            fetchContext,
            isLoading,
            shareDeckWithCourse,
            shareDeckWithUserIds,
        };
    },
    {
        persist: true,
    },
);
