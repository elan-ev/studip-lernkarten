<script setup>
import { computed, onMounted, ref } from 'vue';
import StudipProgressIndicator from '../components/base/StudipProgressIndicator.vue';
import { useSharedDecksStore } from '../stores/shared-decks.js';

const sharedDecksStore = useSharedDecksStore();

const props = defineProps(['deck']);
defineEmits(['change']);

const selectedSharedDeck = ref(null);

const externalCss = computed(() => {
    return window.STUDIP.ASSETS_URL + 'stylesheets/studip-base.css';
});

onMounted(() => {
    selectedSharedDeck.value = props.deck;
});

const onChange = (root) => {
    root.$emit('change', selectedSharedDeck.value);
};

sharedDecksStore.fetchContext().then(() => {
    console.debug('Lernkarten-Deck-Selector geladen');
});
</script>
<template>
    <link media="screen" rel="stylesheet" :href="externalCss" />
    <StudipProgressIndicator
        :description="$gettext('Lade geteilte Kartensätze …')"
        v-if="sharedDecksStore.isLoading"
    />
    <article class="studip" v-else>
        <header>
            <h1>Lernkarten-Deck-Selector</h1>
        </header>
        <select v-model="selectedSharedDeck" @change="onChange($root)">
            <option
                v-for="sharedDeck in sharedDecksStore.all"
                :key="sharedDeck.id"
                :value="sharedDeck.id"
            >
                {{ sharedDeck.deck.data.name }}
            </option>
        </select>
    </article>
</template>
