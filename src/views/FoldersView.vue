<script setup>
import { computed, ref } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import { useContextStore } from '../stores/context.js';
import { useDecksStore } from '../stores/decks.js';
import { useFoldersStore } from '../stores/folders.js';
import CardDeck from '../components/CardDeck.vue';
import DeckList from '../components/DeckList.vue';
import DialogAdjustLearningOptions from '../components/DialogAdjustLearningOptions.vue';
import DialogCreateFolder from '../components/DialogCreateFolder.vue';
import DialogEditFolder from '../components/DialogEditFolder.vue';
import DialogConfirmDeleteFolder from '../components/DialogConfirmDeleteFolder.vue';
import FolderList from '../components/FolderList.vue';
import IconButton from '../components/IconButton.vue';
import Ribbon from '../components/Ribbon.vue';
import StudipCompanion from '../components/base/StudipCompanion.vue';
import StudipIcon from '../components/base/StudipIcon.vue';

const router = useRouter();

const createDialogOpen = ref(false);
const editDialogOpen = ref(false);
const confirmDeleteDialogOpen = ref(false);
const selectedFolder = ref(null);
const showAdjustLearningDialog = ref(false);

const contextStore = useContextStore();
const decksStore = useDecksStore();
const foldersStore = useFoldersStore();
const editFolderObject = ref(null);
decksStore.fetchContext();

const topFolders = computed(() => foldersStore.topFolders);

const decks = computed(() =>
    _.sortBy(
        decksStore.byContext.filter((deck) => !deck.folder.data && !deck.colearning),
        ['name']
    )
);

const addTopFolder = () => {
    createDialogOpen.value = true;
};

const editFolder = (folder) => {
    editDialogOpen.value = true;
    editFolderObject.value = folder;
};

const onEditDialog = (folder, name) => {
    editDialogOpen.value = false;
    foldersStore.updateFolder(folder, { name });
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

const onLearnDecks = () => (showAdjustLearningDialog.value = true);
</script>

<template>
    <table class="default">
        <Ribbon>
            <span :title="$gettext('Zum Hauptordner')">
                <RouterLink :to="{ name: 'home' }">
                    <StudipIcon
                        shape="folder-home-empty"
                        :height="30"
                        :width="30"
                        class="tw-align-middle tw-mr-2 tw-mb-1"
                    />
                    <span class="">{{ $gettext('Lernkarten') }}</span>
                </RouterLink>
            </span>
        </Ribbon>

        <FolderList :folders="topFolders" @delete-folder="deleteFolder" @edit-folder="editFolder">
            <template #empty>
                <StudipCompanion :msgCompanion="$gettext('Es gibt noch keinen Ordner.')">
                    <template #companionActions>
                        <IconButton icon="add" @click="addTopFolder">
                            {{ $gettext('Ordner anlegen') }}
                        </IconButton>
                    </template>
                </StudipCompanion>
            </template>
        </FolderList>

        <tfoot>
            <tr>
                <td colspan="3">
                    <div class="footer-items">
                        <IconButton type="button" icon="add" @click="addTopFolder">
                            {{ $gettext('Ordner anlegen') }}
                        </IconButton>
                        <IconButton type="button" icon="refresh" @click="onLearnDecks">
                            {{ $gettext('Kartensätze lernen') }}
                        </IconButton>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>

    <section class="tw-mt-12" v-if="decks.length">
        <header>
            <h3>
                {{ $gettext('Kartensätze ohne Ordner') }}
            </h3>
        </header>
        <DeckList :decks="decks" />
    </section>

    <DialogAdjustLearningOptions v-model:open="showAdjustLearningDialog" :decks="decks" />
    <DialogCreateFolder v-model:open="createDialogOpen" @confirm="onCreateDialog" />
    <DialogEditFolder
        v-model:open="editDialogOpen"
        :folder="editFolderObject"
        @confirm="onEditDialog"
    />
    <DialogConfirmDeleteFolder
        v-model:open="confirmDeleteDialogOpen"
        @confirm="onConfirmDeleteDialog"
    />
</template>
