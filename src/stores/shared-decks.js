import { ref, computed } from 'vue';
import { defineStore } from 'pinia';
import { api } from '../api.js';
import { useContextStore } from './context.js';

export const useSharedDecksStore = defineStore(
    'sharedDecks',
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

        async function fetchContext() {
            isLoading.value = true;

            const { data } = await api.fetch(
                `${contextStore.type}/${contextStore.id}/lernkarten-shared-decks`,
                { params: { include: 'sharer' } }
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

        return {
            all,
            byId,
            errors,
            fetchContext,
            isLoading,
            shareDeckWithCourse,
            shareDeckWithUserIds,
        };
    },
    {
        persist: true,
    }
);
