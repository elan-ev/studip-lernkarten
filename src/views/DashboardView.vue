<script setup>
import { computed } from 'vue';
import { RouterLink } from 'vue-router';
import DeckList from '../components/DeckList.vue';
import IconButton from '../components/IconButton.vue';
import SharedDeckList from '../components/SharedDeckList.vue';
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

const allStandardDecks = computed(() => allDecks.value.filter(({colearning}) => !colearning));
const doneLoading = computed(() => !decksStore.isLoading && !sharedDecksStore.isLoading);
</script>

<template>
    <main>
        <article class="studip tw-mt-12">
            <header>
                <h1>{{ $gettext('Mit mir geteilte Kartensätze') }}</h1>
            </header>
            <SharedDeckList v-if="doneLoading" :shared-decks="sharedDecks" />
        </article>

        <article class="studip tw-mt-12">
            <header>
                <h1>{{ $gettext('Eigene Kartensätze') }}</h1>
            </header>
            <DeckList :decks="allStandardDecks" />
        </article>
    </main>
</template>
