<script setup>
import { RouterLink } from 'vue-router';
import CardSharedDeck from './CardSharedDeck.vue';

const props = defineProps(['sharedDecks']);
</script>

<template>
    <section class="tw-flex tw-flex-col tw-gap-2">
        <article v-for="sharedDeck in sharedDecks" :key="sharedDeck.id">
            <CardSharedDeck :shared-deck="sharedDeck" />
        </article>
    </section>
</template>
