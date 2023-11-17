import { State, Rating, Card, FSRS } from 'fsrs.js';
import { useGettext } from 'vue3-gettext';

export function useFsrs() {
    const createCard = (flashCard) => {
        const card = new Card();
        card.due = new Date(flashCard.due);
        card.stability = flashCard.stability;
        card.difficulty = flashCard.difficulty;
        card.elapsed_days = flashCard['elapsed-days'];
        card.scheduled_days = flashCard['scheduled-days'];
        card.reps = flashCard.reps;
        card.lapses = flashCard.lapses;
        card.state = flashCard.state;
        card.last_review = new Date(flashCard['last-review']);

        return card;
    };

    const fsrs = new FSRS();

    const repeat = (flashCard, date = new Date()) => {
        const card = createCard(flashCard);

        return fsrs.repeat(card, date);
    };

    const repeatWithRating = (flashCard, rating) => {
        const card = createCard(flashCard);
        const schedulingInfos = fsrs.repeat(card, new Date());

        return schedulingInfos[rating].card;
    };

    const { $gettext } = useGettext();

    const translatedStates = {
        [State.New]: $gettext('Neue Karte'),
        [State.Learning]: $gettext('Zu lernende Karte'),
        [State.Review]: $gettext('Gelernte Karte'),
        [State.Relearning]: $gettext('Erneut zu lernende Karte'),
    };

    return {
        State,
        Rating,
        createCard,
        repeat,
        repeatWithRating,
        translatedStates,
    };
}
