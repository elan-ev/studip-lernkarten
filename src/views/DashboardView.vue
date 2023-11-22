<script setup>
import { computed } from 'vue';
import { RouterLink } from 'vue-router';
import DeckList from '../components/DeckList.vue';
import IconButton from '../components/IconButton.vue';
import StudipCompanion from '../components/base/StudipCompanion.vue';
import StudipIcon from '../components/base/StudipIcon.vue';
import { useDecksStore } from '../stores/decks.js';
import { useSharedDecksStore } from '../stores/shared-decks.js';

const decksStore = useDecksStore();
const sharedDecksStore = useSharedDecksStore();

decksStore.fetchContext();
sharedDecksStore.fetchContext();

const allDecks = computed(() => decksStore.byContext);
const sharedDecks = computed(() => sharedDecksStore.all);
</script>

<template>
    <main>
        <RouterLink :to="{ name: 'decks-create' }" class="button add">
            {{ $gettext('Neuer Kartensatz') }}
        </RouterLink>

        <section class="tw-mt-12">
            {{ sharedDecks }}
        </section>

        <section class="tw-mt-12">
            <DeckList :decks="allDecks" />
        </section>
    </main>
</template>
