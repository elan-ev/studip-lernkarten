<script setup>
import { computed, ref } from 'vue';
import { RouterLink } from 'vue-router';
import CardDeck from '../components/CardDeck.vue';
import DeckList from '../components/DeckList.vue';
import DialogAdjustLearningOptions from '../components/DialogAdjustLearningOptions.vue';
import DialogCreateFolder from '../components/DialogCreateFolder.vue';
import DialogConfirmDeleteFolder from '../components/DialogConfirmDeleteFolder.vue';
import FolderList from '../components/FolderList.vue';
import IconButton from '../components/IconButton.vue';
import Ribbon from '../components/Ribbon.vue';
import StudipIcon from '../components/base/StudipIcon.vue';
import { useDecksStore } from '../stores/decks.js';
import { useFoldersStore } from '../stores/folders.js';

const decksStore = useDecksStore();
const foldersStore = useFoldersStore();
decksStore.fetchContext();

const createDialogOpen = ref(false);
const confirmDeleteDialogOpen = ref(false);
const selectedFolder = ref(null);
const showAdjustLearningDialog = ref(false);

const props = defineProps(['id']);

const folder = computed(() => foldersStore.byId(props.id));
const children = computed(() => {
    if (!folder.value) {
        return [];
    }
    const children = foldersStore.children(props.id);

    return _.sortBy(children, 'name');
});

const decks = computed(() =>
    folder.value
        ? decksStore.byContext.filter((deck) => deck.folder.data?.id === folder.value.id)
        : []
);

const onAddChild = () => {
    createDialogOpen.value = true;
};
const createChild = (name) => {
    createDialogOpen.value = false;
    foldersStore.createFolder(name, folder.value);
};

const onDeleteFolder = (folder) => {
    confirmDeleteDialogOpen.value = true;
    selectedFolder.value = folder;
};
const deleteFolder = () => {
    foldersStore.deleteFolder(selectedFolder.value);
    confirmDeleteDialogOpen.value = false;
};

const onLearnDecks = () => (showAdjustLearningDialog.value = true);
</script>

<template>
    <Ribbon v-if="folder">
        <li>
            <RouterLink :to="{ name: 'home' }">
                <StudipIcon
                    shape="folder-home-empty"
                    :height="18"
                    :width="18"
                    class="tw-align-middle tw-mr-1"
                />
                <span class="">{{ $gettext('Home') }}</span>
            </RouterLink>
        </li>
        <li v-for="ancestor in foldersStore.ancestors(folder)" :key="ancestor.id">
            <RouterLink
                :to="{ name: 'folder', params: { id: ancestor.id } }"
                class="tw-whitespace-nowrap"
            >
                {{ ancestor.name }}
            </RouterLink>
        </li>
        <li>{{ folder.name }}</li>
    </Ribbon>

    <section class="tw-mt-8">
        <FolderList :folders="children" @delete-folder="onDeleteFolder" />

        <IconButton type="button" icon="add" @click="onAddChild">
            {{ $gettext('Unterordner erstellen') }}
        </IconButton>
        <IconButton type="button" icon="refresh" @click="onLearnDecks">
            {{ $gettext('Kartensätze lernen') }}
        </IconButton>
    </section>

    <section class="tw-mt-12" v-if="decks.length">
        <header>
            <h3 class="tw-mt-12">
                {{ $gettext('Kartensätze in diesem Ordner') }}
            </h3>
        </header>
        <DeckList :decks="decks" />
    </section>

    <DialogAdjustLearningOptions v-model:open="showAdjustLearningDialog" :decks="decks" />
    <DialogCreateFolder v-model:open="createDialogOpen" @confirm="createChild" />
    <DialogConfirmDeleteFolder v-model:open="confirmDeleteDialogOpen" @confirm="deleteFolder" />
</template>
