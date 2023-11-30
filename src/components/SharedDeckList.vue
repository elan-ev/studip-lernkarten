<script setup>
import ListItem from './SharedDeckListItem.vue';

const props = defineProps(['sharedDecks']);
</script>

<template>
    <section class="tw-flex tw-flex-col tw-gap-2">
        <article v-for="sharedDeck in sharedDecks" :key="sharedDeck.id">
            <ListItem :shared-deck="sharedDeck" />
        </article>
    </section>
</template>
