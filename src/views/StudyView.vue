<script setup>
import { computed, onMounted, ref } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import { useGettext } from 'vue3-gettext';
import BasicBack from '../components/cards/BasicBack.vue';
import BasicFront from '../components/cards/BasicFront.vue';
import ImageBack from '../components/cards/ImageBack.vue';
import ImageFront from '../components/cards/ImageFront.vue';
import IconButton from '../components/IconButton.vue';
import StudipIcon from '../components/base/StudipIcon.vue';
import StudyViewRepeatButtons from '../components/StudyViewRepeatButtons.vue';
import StudyViewStatistics from '../components/StudyViewStatistics.vue';
import { useScheduler } from '../composables/scheduler.js';

const props = defineProps(['id']);

const { $gettext } = useGettext();
const router = useRouter();
const { cards, cardStates, deck, dueCards, queuedCard, repeat } = useScheduler({ id: props.id });

const showAnswer = ref(false);

onMounted(enableCompactNavigation);

// const cardsLeft = computed(() => orderedCards.value.length - (currentIndex.value + 1));
const cardsLeft = computed(() => 17);
const currentIndex = computed(() => 0);

const folderName = computed(() => {
    return deck.value.folder?.name ?? $gettext('Kein Ordner');
});

const cardFront = computed(() => {
    switch (queuedCard.value.model) {
        case 'image': return ImageFront;
        default:      return BasicFront;
    }
});
const cardBack = computed(() => {
    switch (queuedCard.value.model) {
        case 'image': return ImageBack;
        default:      return BasicBack;
    }
});

const onShowAnswer = () => (showAnswer.value = true);

const onRepeat = (rating) => {
    const card = repeat(rating);
    showAnswer.value = false;
    // currentIndex.value = currentIndex.value + 1;
};
const onCancel = () => {
    router.push({ name: 'deck', params: { id: props.id } });
    disableCompactNavigation();
};

function disableCompactNavigation() {
    STUDIP.Vue.emit('toggle-compact-navigation', false);
}
function enableCompactNavigation() {
    STUDIP.Vue.emit('toggle-compact-navigation', true);
}
</script>

<template>
    <div class="tw-flex tw-flex-col tw-items-center">
        <div v-if="isLoading">
            {{ $gettext('Lade Kartensatz') }}
        </div>
        <div v-if="deck" class="tw-max-w-[700px] tw-w-full">
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
                    <RouterLink
                        :to="{ name: 'deck', params: { id } }"
                        @click="disableCompactNavigation"
                    >
                        Zurück zum Kartensatz
                    </RouterLink>
                </div>
            </article>
        </div>
    </div>
    <pre>{{ queuedCard }}</pre>
    <pre>{{ cards }}</pre>
</template>
