<script setup>
import { computed, getCurrentInstance, nextTick, onMounted, ref, toRaw, watch } from 'vue';
import StudipProgressIndicator from '../components/base/StudipProgressIndicator.vue';
import { useSharedDecksStore } from '../stores/shared-decks.js';
import StudyView from '../components/StudyView.vue';

const props = defineProps(["deck"]);

const sharedDecksStore = useSharedDecksStore();

const initialized = ref(false);

const sharedDeck = computed(() => {
    if (!props.deck) {
        return null;
    }

    const deck = sharedDecksStore.byId(props.deck);

    return deck;
});
const decks = computed(() => '' + sharedDeck.value.deck.data.id);

onMounted(() => {
    nextTick(() => {
        if (!props.deck) {
            initialized.value = true;
        } else {
            sharedDecksStore.fetchById(props.deck).then(() => (initialized.value = true));
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
    <StudipProgressIndicator
        v-if="!initialized"
        :description="$gettext('Initialisiere Lernkarten-Block …')"
    />

    <div v-else>
        <StudyView v-if="sharedDeck" :decks="decks" order="basic" :standalone="true" />
        <div v-else>TODO: Hier fehlt ein geteiltes deck</div>
    </div>

    <article class="studip tw-hidden">
        <header>
            <h1>Lernkarten-Block (deck: {{ props.deck }})</h1>
        </header>
    </article>
</template>
