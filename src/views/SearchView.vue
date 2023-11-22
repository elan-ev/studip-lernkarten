<script setup>
import { computed } from 'vue';
import { useDecksStore } from '../stores/decks.js';

const decksStore = useDecksStore();
decksStore.fetchContext();

const props = defineProps(["query"]);

const allDecks = computed(() => decksStore.byContext);
</script>

<template>
    <main>
        Query: "{{ query }}"
    </main>
</template>
