<script setup>
import { computed } from 'vue';
import { RouterLink } from 'vue-router';
import DeckList from '../components/DeckList.vue';
import IconButton from '../components/IconButton.vue';
import StudipCompanion from '../components/base/StudipCompanion.vue';
import StudipIcon from '../components/base/StudipIcon.vue';
import { useDecksStore } from '../stores/decks.js';

const decksStore = useDecksStore();
decksStore.fetchContext();

const allDecks = computed(() => decksStore.byContext);
</script>

<template>
    <main>
        <RouterLink :to="{ name: 'decks-create' }" class="button add">
            {{ $gettext('Neuer Kartensatz') }}
        </RouterLink>

        <section class="tw-mt-12">
            <DeckList :decks="allDecks" />
        </section>
    </main>
</template>
