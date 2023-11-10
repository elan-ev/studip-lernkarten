<script setup>
import { computed } from 'vue';
import { RouterLink } from 'vue-router';
import DeckList from '../components/DeckList.vue';
import StudipIcon from '../components/base/StudipIcon.vue';
import { useDecksStore } from '../stores/decks.js';

const decksStore = useDecksStore();
decksStore.fetchContext();

const allDecks = computed(() => decksStore.byContext);
</script>

<template>
    <main>
        <header>
            <h2>{{ $gettext('Deine Kartensätze') }}</h2>
        </header>

        <form class="default" @submit.prevent="">
            <div class="formpart">
                <label>
                    <span class="textlabel">{{ $gettext('Kartensätze filtern') }}</span>
                    <input type="text" name="lernkarten-decks-filter" />
                </label>
            </div>

            <div class="formpart">
                <RouterLink :to="{ name: 'decks-create' }" class="button add">
                    {{ $gettext('Neuer Kartensatz') }}
                </RouterLink>
            </div>

            <div class="formpart">
                <select>
                    <option>Alle Ordner</option>
                    <option>Ohne Ordner</option>
                    <option disabled>_________</option>
                    <option>Ordner verwalten</option>
                </select>
            </div>
        </form>

        <section class="tw-mt-12">
            <DeckList :decks="allDecks" />
        </section>
    </main>
</template>
