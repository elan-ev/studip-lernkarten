<script setup>
import { computed, ref } from 'vue';
import { RouterLink } from 'vue-router';
import { useContextStore } from '../stores/context.js';
import { useDecksStore } from '../stores/decks.js';
import { useFoldersStore } from '../stores/folders.js';
import CardDeck from '../components/CardDeck.vue';
import DialogCreateFolder from '../components/DialogCreateFolder.vue';
import DialogConfirmDeleteFolder from '../components/DialogConfirmDeleteFolder.vue';
import FolderList from '../components/FolderList.vue';
import FolderTree from '../components/FolderTree.vue';
import Ribbon from '../components/Ribbon.vue';

const createDialogOpen = ref(false);
const confirmDeleteDialogOpen = ref(false);
const selectedFolder = ref(null);

const contextStore = useContextStore();
const decksStore = useDecksStore();
const foldersStore = useFoldersStore();
foldersStore.fetch();
decksStore.fetchContext();

const topFolders = computed(() => foldersStore.topFolders);

const decks = computed(() => decksStore.byContext.filter((deck) => !deck.folder.data));

const addTopFolder = () => {
    createDialogOpen.value = true;
};
const onCreateDialog = (name) => {
    createDialogOpen.value = false;
    foldersStore.createFolder(name, null);
};

const deleteFolder = (folder) => {
    confirmDeleteDialogOpen.value = true;
    selectedFolder.value = folder;
};
const onConfirmDeleteDialog = () => {
    confirmDeleteDialogOpen.value = false;
    foldersStore.deleteFolder(selectedFolder.value);
};
</script>

<template>
    <Ribbon>
        <li>
            <RouterLink :to="{ name: 'folders' }"> Home </RouterLink>
        </li>
    </Ribbon>

    <section class="tw-mt-8">
        <FolderList :folders="topFolders" @delete-folder="deleteFolder" />

        <button type="button" class="button add" @click="addTopFolder">Neuer Ordner</button>
    </section>

    <section class="tw-mt-12">
        <header>
            <h3>Decks ohne Ordner</h3>
        </header>
        <article v-for="deck in decks" :key="deck.id">
            <RouterLink :to="{ name: 'deck', params: { id: deck.id } }">
                <CardDeck :deck="deck" />
            </RouterLink>
        </article>
    </section>

    <DialogCreateFolder v-model:open="createDialogOpen" @confirm="onCreateDialog" />
    <DialogConfirmDeleteFolder
        v-model:open="confirmDeleteDialogOpen"
        @confirm="onConfirmDeleteDialog"
    />
</template>
