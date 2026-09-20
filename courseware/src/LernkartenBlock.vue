<script setup>
import { computed, inject, nextTick, onMounted, ref } from 'vue';
import CardSharedDeck from './CardSharedDeck.vue';
import { GETTEXT_KEY } from './gettext-key';
import StudipCompanion from '@/components/base/StudipCompanion.vue';
import StudipProgressIndicator from '@/components/base/StudipProgressIndicator.vue';
import { useSharedDecksStore } from '@/stores/shared-decks.js';
import StudyView from '@/components/StudyView.vue';

const $gettext = inject(GETTEXT_KEY).$gettext;

const props = defineProps(['deck']);

const sharedDecksStore = useSharedDecksStore();

const initialized = ref(false);
const showColearn = ref(false);

const colearningDeck = computed(() => sharedDeck.value?.['colearning-deck'].data ?? null);
const sharedDeck = computed(() => (props.deck ? sharedDecksStore.byId(props.deck) : null));
const deckIds = computed(() => '' + (colearningDeck.value?.id ?? ''));

onMounted(() => {
    nextTick(() => {
        if (!props.deck) {
            initialized.value = true;
            return;
        }
        sharedDecksStore.fetchById(props.deck).finally(() => (initialized.value = true));
    });
});

const onColearn = () => {
    if (colearningDeck.value) {
        showColearn.value = true;
        return;
    }

    sharedDecksStore.colearn(sharedDeck.value).then(() => (showColearn.value = true));
};
</script>

<template>
    <StudipProgressIndicator
        v-if="!initialized"
        :description="$gettext('Initialisiere Lernkarten-Block …')"
    />

    <StudipCompanion
        v-else-if="!sharedDeck"
        :msg-companion="$gettext('Es wurden bisher noch keine Inhalte eingepflegt.')"
    />

    <template v-else>
        <div v-if="!showColearn">
            <CardSharedDeck :shared-deck="sharedDeck" @colearn="onColearn" />
        </div>
        <StudyView v-else :decks="deckIds" order="progress" :standalone="true" />
    </template>
</template>
