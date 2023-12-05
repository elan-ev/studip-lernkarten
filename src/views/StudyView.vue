<script setup>
import { computed, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useGettext } from 'vue3-gettext';
import BasicBack from '../components/cards/BasicBack.vue';
import BasicFront from '../components/cards/BasicFront.vue';
import ImageBack from '../components/cards/ImageBack.vue';
import ImageFront from '../components/cards/ImageFront.vue';
import IconButton from '../components/IconButton.vue';
import StudipIcon from '../components/base/StudipIcon.vue';
import StudipProgressIndicator from '../components/base/StudipProgressIndicator.vue';
import StudyViewRepeatButtons from '../components/StudyViewRepeatButtons.vue';
import StudyViewStatistics from '../components/StudyViewStatistics.vue';
import { useScheduler } from '../composables/scheduler.js';

const props = defineProps(['decks', 'order']);

const { $gettext } = useGettext();
const router = useRouter();
const { cards, cardStates, dueCards, isLoading, queuedCard, repeat } = useScheduler({
    decks: props.decks,
    order: props.order,
});

const showAnswer = ref(false);

onMounted(enableCompactNavigation);

const cardFront = computed(() => {
    switch (queuedCard.value.model) {
        case 'image':
            return ImageFront;
        default:
            return BasicFront;
    }
});
const cardBack = computed(() => {
    switch (queuedCard.value.model) {
        case 'image':
            return ImageBack;
        default:
            return BasicBack;
    }
});

const folderName = computed(() => {
    // return deck.value.folder?.name ?? $gettext('Kein Ordner');
    return $gettext('TODO Ordner');
});

const onShowAnswer = () => (showAnswer.value = true);

const onRepeat = (rating) => {
    const card = repeat(rating);
    showAnswer.value = false;
};
const onCancel = () => {
    router.back();
    disableCompactNavigation();
};

function disableCompactNavigation() {
    // STUDIP.Vue.emit('toggle-compact-navigation', false);
}
function enableCompactNavigation() {
    // STUDIP.Vue.emit('toggle-compact-navigation', true);
}
</script>

<template>
    <div class="tw-flex tw-flex-col tw-items-center">
        <StudipProgressIndicator :description="$gettext('Lade Karten …')" v-if="isLoading" />
        <div v-if="!isLoading" class="tw-max-w-[700px] tw-w-full">
            <div v-if="queuedCard">
                <StudyViewStatistics
                    class="tw-mb-8"
                    :card-states="cardStates"
                    :due-cards="dueCards"
                />
                <div class="tw-flex tw-items-center tw-gap-2 tw-mb-2">
                    <StudipIcon shape="folder-empty" role="info" height="32" width="32" />
                    <span>{{ folderName }}</span>
                </div>
                <div>
                    <component :is="cardFront" :card="queuedCard" />
                    <div class="tw-flex tw-justify-between">
                        <IconButton
                            v-if="!showAnswer"
                            icon="visibility-visible"
                            @click="onShowAnswer"
                        >
                            {{ $gettext('Antwort ansehen') }}
                        </IconButton>
                        <StudyViewRepeatButtons v-else @repeat="onRepeat" />
                        <IconButton icon="decline" variant="link" type="button" @click="onCancel">
                            {{ $gettext('Abbrechen') }}
                        </IconButton>
                    </div>
                </div>
                <div v-if="showAnswer">
                    <component :is="cardBack" :card="queuedCard" />
                </div>
            </div>
            <article v-else class="studip">
                <header>
                    <h1>Congratulations</h1>
                </header>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                    incididunt ut labore et dolore magna aliqua. Ut enimad minim veniam, quis
                    nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
                    culpa qui officia deserunt mollit anim id est laborum.
                </p>
                <div>
                    <IconButton icon="stop" type="button" @click="onCancel">
                        {{ $gettext('Zurück zum Kartensatz') }}
                    </IconButton>
                </div>
            </article>
        </div>
    </div>
    <pre>{{ queuedCard }}</pre>
    <pre>{{ cards }}</pre>
</template>
