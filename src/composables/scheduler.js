import { computed, ref } from 'vue';
import { useFsrs } from './fsrs.js';
import { useCardsStore } from '../stores/cards.js';
import { useDecksStore } from '../stores/decks.js';

export function useScheduler({ id }) {
    const errors = ref(null);
    const isLoading = ref(false);

    const deck = ref(null);
    const cards = ref([]);

    const { repeatWithRating, Rating, State } = useFsrs();
    const cardsStore = useCardsStore();
    const decksStore = useDecksStore();

    const cardStates = computed(() => {
        return cards.value.reduce(
            (map, card) => {
                map.set(card.state, map.get(card.state) + 1);
                return map;
            },

            new Map([
                [State.New, 0],
                [State.Learning, 0],
                [State.Review, 0],
                [State.Relearning, 0],
            ])
        );
    });

    const queuedCard = computed(() => {
        const now = new Date();
        // now.setMinutes(now.getMinutes() + );

        const dueCards = cards.value.filter((card) => new Date(card.due) < now);

        return dueCards.length ? _.sample(dueCards) : null;
    });

    const repeat = (rating) => {
        if (!(rating in Rating)) {
            throw new Error('Invalid rating.');
        }
        const card = queuedCard.value;
        cardsStore.updateLearningStats(card, repeatWithRating(card, rating));
        return card;
    };

    Promise.all([decksStore.fetchById(id), cardsStore.fetchByDeck({ id })]).then(() => {
        deck.value = decksStore.byId(id);
        cards.value = cardsStore.byDeck({ id });
    });

    return {
        cards,
        cardStates,
        deck,
        errors,
        isLoading,
        queuedCard,
        repeat,
    };
}
