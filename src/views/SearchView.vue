<script setup>
import { toRaw, watch } from 'vue';
import { useRoute } from 'vue-router';
import IconButton from '../components/IconButton.vue';
import StudipIcon from '../components/base/StudipIcon.vue';
import DeckList from '../components/DeckList.vue';


import { computed, ref } from 'vue';
import { useDecksStore } from '../stores/decks.js';
import { useFoldersStore } from '../stores/folders.js';

const decksStore = useDecksStore();
const foldersStore = useFoldersStore();

//foldersStore.fetchContext();
const topFolders = computed(() => foldersStore.topFolders);
decksStore.fetchContext().then(() => {
    doSearch();
});

const props = defineProps(['query']);

const query = ref(props.query);
const selectedFolder = ref(-1);

const allDecks = computed(() => decksStore.byContext);
const filteredDecks = ref([]);

const doSearch = () => {
    filteredDecks.value = toRaw(allDecks.value.filter((el) => {
        // console.log(el);
        let found = false;
        /*
        if (selectedFolder.value != -1) {
            // if (el.)
        }
        */

        if (el.name.search(query.value) != -1
            || el.description.search(query.value) != -1
        ) {
            found = true;
        }

        return found;
    }));
}

const route = useRoute();

watch(
    () => route.query,
    async newQuery => {
        query.value = newQuery.q;
        doSearch();
    }
);
</script>

<template>
    <main>
        <!--<pre>
        {{ topFolders }}
        </pre>-->
        <form class="default tw-bg-[var(--content-color-20)] tw-p-3 tw-mb-2" @submit.prevent="doSearch">
            <div class="tw-font-bold">
                <StudipIcon shape="dialog-cards" role="info" class="tw-align-middle" />
                {{ $gettext('Deine Kartensätze') }}
            </div>

            <section class="tw-flex tw-gap-3 tw-items-center">
                <div class="formpart">
                    <label>
                        <span class="textlabel">{{ $gettext('Ordner') }}</span>
                        <select v-model="selectedFolder">
                            <option value="-1">Alles</option>
                            <option v-for="folder in topFolders" v-bind:key="folder.id" :value="folder.id">
                                {{ folder.name }}
                            </option>
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

                <IconButton icon="search" :disabled="decksStore.isLoading">
                    {{ $gettext('Nach Kartensätzen suchen') }}
                </IconButton>
            </section>
        </form>

        <DeckList :decks="filteredDecks" />
    </main>
</template>
