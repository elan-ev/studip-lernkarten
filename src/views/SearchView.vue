<script setup>
import { computed, ref } from 'vue';
import { useDecksStore } from '../stores/decks.js';

const decksStore = useDecksStore();
decksStore.fetchContext();

const props = defineProps(["query"]);

const query = ref(props.query);

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
                        <input v-model="query" type="text" name="lernkarten-decks-filter" />
                    </label>
                </div>

                <IconButton icon="search">
                    {{ $gettext('Nach Kartensätzen suchen') }}
                </IconButton>
            </section>
        </form>
    </main>
</template>
