<script setup>
import { computed } from 'vue';
import { useContextStore } from '../stores/context.js';
import { useFoldersStore } from '../stores/folders.js';
import DialogCreateFolder from '../components/DialogCreateFolder.vue';
import FolderTree from '../components/FolderTree.vue';

const contextStore = useContextStore();
const foldersStore = useFoldersStore();
foldersStore.fetch();

const folders = computed(() => {
    return foldersStore.byContext(contextStore.id);
});

const parents = computed(() =>
    folders.value.reduce((map, folder) => {
        const parentId = folder.parent.data?.id ?? null;
        if (!map.has(parentId)) {
            map.set(parentId, []);
        }
        map.get(parentId).push(folder);
        return map;
    }, new Map())
);

const topFolders = computed(() => {
    return folders.value.filter((folder) => {
        return !folder.parent.data && folder.context.data.id === contextStore.id;
    });
});

const addTopFolder = (folder) => {
    console.debug('addTopFolder', folder);
    foldersStore.createFolder('Ein Top Folder', null);
};
const addChildFolder = (folder) => {
    console.debug('addChildFolder', folder);
    foldersStore.createFolder('Ein Child Folder', folder);
};
const deleteFolder = (folder) => {
    console.debug('deleteFolder', folder);
    foldersStore.deleteFolder(folder);
};
</script>

<template>
    <main>
        <header>
            <h2>{{ $gettext('Ordnerverwaltung') }}</h2>
        </header>

        <section>
            <article>
                <pre>{{ foldersStore.isLoading }}</pre>
            </article>

            <FolderTree
                :folders="topFolders"
                :parents="parents"
                @add-child="addChildFolder"
                @delete-folder="deleteFolder"
            />

            <button type="button" class="button add" @click="addTopFolder">Neuer Top-Ordner</button>
        </section>
    </main>

    <DialogCreateFolder />
</template>
