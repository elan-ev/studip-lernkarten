<script setup>
import { useRouter } from 'vue-router';
import CardDeck from './CardDeck.vue';

const router = useRouter();

const props = defineProps(['decks']);

const onSelectDeck = (deck) => {
    router.push({ name: 'deck', params: { id: deck.id } });
};
</script>

<template>
    <section class="tw-flex tw-flex-col tw-gap-2">
        <article v-for="deck in decks" :key="deck.id">
            <CardDeck :deck="deck" @select="onSelectDeck" />
        </article>
    </section>
</template>
