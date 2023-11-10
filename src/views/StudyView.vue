<script setup>
import { computed } from 'vue';
import { useCardsStore } from '../stores/cards.js';
import { useDecksStore } from '../stores/decks.js';

const cardsStore = useCardsStore();
const decksStore = useDecksStore();

const props = defineProps(['id']);

decksStore.fetchById(props.id);
cardsStore.fetchByDeck({ id: props.id });

const deck = computed(() => decksStore.byId(props.id));
const cards = computed(() => cardsStore.byDeck({ id: props.id }));
</script>

<template>
    <pre>{{ decksStore.isLoading }}</pre>
    <pre>{{ deck }}</pre>
</template>
