<script setup>
import { RouterLink } from 'vue-router';
import CardDeck from './CardDeck.vue';

const props = defineProps(['decks']);
</script>

<template>
    <section class="tw-flex tw-flex-col tw-gap-2">
        <article v-for="deck in decks" :key="deck.id">
            <RouterLink :to="{ name: 'deck', params: { id: deck.id } }">
                <CardDeck :deck="deck" />
            </RouterLink>
        </article>
    </section>
</template>
