<script setup>
import { computed, ref } from 'vue';
import DialogShowDeck from '../components/DialogShowDeck.vue';
import SharedDeckList from '../components/SharedDeckList.vue';
import StudipCompanion from '../components/base/StudipCompanion.vue';
import StudipProgressIndicator from '../components/base/StudipProgressIndicator.vue';
import { useCardsStore } from '../stores/cards.js';
import { useContextStore } from '../stores/context.js';
import { useDecksStore } from '../stores/decks.js';
import { useSharedDecksStore } from '../stores/shared-decks.js';

const cardsStore = useCardsStore();
const contextStore = useContextStore();
const decksStore = useDecksStore();
const sharedDecksStore = useSharedDecksStore();

decksStore.fetchContext();
sharedDecksStore.fetchContext();

const selectedDeck = ref(null);
const showDeckDialog = ref(false);

const sharedWithMe = computed(() =>
    sharedDecksStore.all.filter((sharedDeck) => sharedDeck.sharer.data.id !== contextStore.userId)
);
const doneLoading = computed(
    () => !decksStore.isLoading && !sharedDecksStore.isLoading
);

const onSelectSharedDeck = (sharedDeck) => {
    const deck = sharedDeck['colearning-deck'].data || sharedDeck.deck.data;
    cardsStore.fetchByDeck({ id: deck.id });
    selectedDeck.value = deck;
    showDeckDialog.value = true;
};
</script>

<template>
    <main>
        <StudipProgressIndicator
            v-if="!doneLoading"
            :description="$gettext('Lade Kartensätze …')"
        />
        <template v-if="doneLoading">
            <article class="studip" v-if="sharedWithMe.length">
                <header>
                    <h1>{{ $gettext('Mit mir geteilte Kartensätze') }}</h1>
                </header>
                <SharedDeckList :shared-decks="sharedWithMe" @select="onSelectSharedDeck" />
            </article>
            <StudipCompanion
                v-if="!sharedWithMe.length"
                mood="sad"
                :msg-companion="$gettext('Bisher hat niemand einen Kartensatz mit Ihnen geteilt.')"
                >
            </StudipCompanion>
        </template>
    </main>
    <DialogShowDeck v-model:open="showDeckDialog" :deck="selectedDeck" />
</template>
