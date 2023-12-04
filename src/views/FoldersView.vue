<script setup>
import { computed, ref } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import { useContextStore } from '../stores/context.js';
import { useDecksStore } from '../stores/decks.js';
import { useFoldersStore } from '../stores/folders.js';
import CardDeck from '../components/CardDeck.vue';
import DialogCreateFolder from '../components/DialogCreateFolder.vue';
import DialogConfirmDeleteFolder from '../components/DialogConfirmDeleteFolder.vue';
import FolderList from '../components/FolderList.vue';
import IconButton from '../components/IconButton.vue';
import Ribbon from '../components/Ribbon.vue';
import StudipCompanion from '../components/base/StudipCompanion.vue';
import StudipIcon from '../components/base/StudipIcon.vue';

const router = useRouter();

const createDialogOpen = ref(false);
const confirmDeleteDialogOpen = ref(false);
const selectedFolder = ref(null);

const contextStore = useContextStore();
const decksStore = useDecksStore();
const foldersStore = useFoldersStore();
decksStore.fetchContext();

const topFolders = computed(() => foldersStore.topFolders);

const decks = computed(() => decksStore.byContext.filter((deck) => !deck.folder.data && !deck.colearning));

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

const onSelectDeck = (deck) => {
    router.push({ name: 'deck', params: { id: deck.id } });
};
</script>

<template>
    <Ribbon>
        <li>
            <RouterLink :to="{ name: 'folders' }" disabled>
                <StudipIcon
                    shape="folder-home-empty"
                    role="info"
                    :height="18"
                    :width="18"
                    class="tw-align-middle tw-mr-1"
                />
                <span class="">{{ $gettext('Home') }}</span>
            </RouterLink>
        </li>
    </Ribbon>

    <section class="tw-mt-8">
        <FolderList :folders="topFolders" @delete-folder="deleteFolder">
            <template #empty>
                <StudipCompanion :msgCompanion="$gettext('Es gibt noch keinen Ordner.')">
                    <template #companionActions>
                        <IconButton icon="add"  @click="addTopFolder">
                            {{ $gettext('Ordner anlegen') }}
                        </IconButton>
                    </template>
                </StudipCompanion>
            </template>
        </FolderList>
        <button type="button" class="button add" @click="addTopFolder">
            {{ $gettext('Ordner anlegen') }}
        </button>
    </section>

    <section class="tw-mt-12" v-if="decks.length">
        <header>
            <h3>
                {{ $gettext('Kartensätze ohne Ordner') }}
            </h3>
        </header>
        <article v-for="deck in decks" :key="deck.id">
            <CardDeck :deck="deck" @select="onSelectDeck" />
        </article>
    </section>

    <DialogCreateFolder v-model:open="createDialogOpen" @confirm="onCreateDialog" />
    <DialogConfirmDeleteFolder
        v-model:open="confirmDeleteDialogOpen"
        @confirm="onConfirmDeleteDialog"
    />
</template>
