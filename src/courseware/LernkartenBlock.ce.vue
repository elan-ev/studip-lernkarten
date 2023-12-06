<script setup>
import { computed, getCurrentInstance, nextTick, onMounted, ref, toRaw, watch } from 'vue';
import StudipProgressIndicator from '../components/base/StudipProgressIndicator.vue';
import { useSharedDecksStore } from '../stores/shared-decks.js';
import StudyView from '../components/StudyView.vue';

const sharedDecksStore = useSharedDecksStore();

const initialized = ref(false);
const sharedDeckId = ref(null);
const deckId = computed(() => {
    const inst = getCurrentInstance();

    return inst.root.props.deck;
});
const sharedDeck = computed(() => {
    if (!sharedDeckId.value) {
        return null;
    }

    const deck = sharedDecksStore.byId(sharedDeckId.value);

    return deck;
});
const decks = computed(() => '' + sharedDeck.value.deck.data.id);

onMounted(() => {
    nextTick(() => {
        sharedDeckId.value = deckId.value;
        if (!sharedDeckId.value) {
            initialized.value = true;
        } else {
            sharedDecksStore.fetchById(deckId.value).then(() => (initialized.value = true));
        }
    });
});

const externalCss = computed(() => {
    return (
        window.STUDIP.ABSOLUTE_URI_STUDIP +
        'plugins_packages/elan-ev/LernkartenPlugin/dist/style.css'
    );
});
</script>
<template>
    <link media="screen" rel="stylesheet" :href="externalCss" />
    <div class="tw-hidden" :this-is-necessary="deckId" />
    <StudipProgressIndicator
        v-if="!initialized"
        :description="$gettext('Initialisiere Lernkarten-Block …')"
    />

    <div v-else>
        <StudyView v-if="sharedDeck" :decks="decks" order="basic" :hide-back="true" />
        <div v-else>TODO: Hier fehlt ein geteiltes deck</div>
    </div>

    <article class="studip tw-hidden">
        <header>
            <h1>Lernkarten-Block (deck: {{ sharedDeckId }})</h1>
        </header>
    </article>
</template>
