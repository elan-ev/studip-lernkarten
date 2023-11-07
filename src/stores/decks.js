import { ref, computed } from 'vue';
import { defineStore } from 'pinia';
import { api } from '../api.js';
import { useContextStore } from './context.js';

export const useDecksStore = defineStore(
    'decks',
    () => {
        const contextStore = useContextStore();

        const allDecks = ref([]);
        const isLoading = ref(false);

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
            allDecks.value = data;
        }

        function byId(id) {
            return allDecks.value.find((deck) => deck.id === id);
        }

        return {
            allDecks,
            byContext,
            byId,
            fetchContext,
            isLoading,
        };
    },
    {
        persist: true,
    }
);
