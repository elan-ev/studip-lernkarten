<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import CardDeck from './CardDeck.vue';
import CardSharedDeck from './CardSharedDeck.vue';
import { useDecksStore } from '../stores/decks.js';

const decksStore = useDecksStore();
const router = useRouter();

const props = defineProps(['sharedDeck']);

const colearningDeck = computed(() => {
    return props.sharedDeck['colearning-deck'].data;
});

const onSelect = (deck) => {
    router.push({
        name: 'deck',
        params: {
            id:
                deck.type === 'lernkarten-decks'
                    ? deck.id
                    : colearningDeck.value?.id ?? deck.deck.data.id,
        },
    });
};
</script>

<template>
    <CardDeck v-if="colearningDeck" :deck="colearningDeck" @select="onSelect(colearningDeck)" />
    <CardSharedDeck v-else :shared-deck="sharedDeck" @select="onSelect(sharedDeck)" />
</template>
