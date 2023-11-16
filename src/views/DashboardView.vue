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
        <form class="default tw-bg-[var(--content-color-20)] tw-p-3" @submit.prevent="">
            <div class="tw-font-bold">
                <StudipIcon shape="dialog-cards" role="info" class="tw-align-middle" />
                {{ $gettext('Deine Kartensätze') }}
            </div>

            <section class="tw-flex tw-gap-3 tw-items-center">
                <div class="formpart">
                    <label>
                        <span class="textlabel">{{ $gettext('Ordner') }}</span>
                        <select>
                            <option>Alle Ordner</option>
                            <option>Ohne Ordner</option>
                            <!-- TODO: Hier fehlen noch alle Ordner in diesem Context -->
                        </select>
                    </label>
                </div>

                <div class="formpart">
                    <label>
                        <span class="textlabel">{{ $gettext('Kartensätze filtern') }}</span>
                        <input type="text" name="lernkarten-decks-filter" />
                    </label>
                </div>

                <IconButton icon="search">
                    {{ $gettext('Nach Kartensätzen suchen') }}
                </IconButton>
            </section>
        </form>

        <RouterLink :to="{ name: 'decks-create' }" class="button add">
            {{ $gettext('Neuer Kartensatz') }}
        </RouterLink>

        <section class="tw-mt-12">
            <DeckList :decks="allDecks" />
        </section>
    </main>
</template>
