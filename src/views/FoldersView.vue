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
const showAdjustLearningDialog = ref(false);

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

const onLearnDecks = () => (showAdjustLearningDialog.value = true);
</script>

<template>
    <Ribbon>
        <li>
            <RouterLink :to="{ name: 'home' }" disabled>
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
        <IconButton type="button" icon="add" @click="addTopFolder">
            {{ $gettext('Ordner anlegen') }}
        </IconButton>
        <IconButton type="button" icon="refresh" @click="onLearnDecks">
            {{ $gettext('Kartensätze lernen') }}
        </IconButton>
    </section>

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
    <DialogConfirmDeleteFolder
        v-model:open="confirmDeleteDialogOpen"
        @confirm="onConfirmDeleteDialog"
    />
</template>
