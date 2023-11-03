<script setup>
import { computed, ref } from 'vue';
import StudipIcon from '../components/base/StudipIcon.vue';
import { useFoldersStore } from '../stores/folders.js';

const props = defineProps(['folders', 'parents']);
const emit = defineEmits(['add-child', 'delete-folder']);
const folderStore = useFoldersStore();
const sort = (items) => _.sortBy(items, 'name');
const children = (folder) => sort(props.parents.has(folder.id) ? props.parents.get(folder.id) : []);

const addChild = (folder) => emit('add-child', folder);
const deleteFolder = (folder) => emit('delete-folder', folder);
</script>

<template>
    <article class="studip toggle tw-my-2" v-for="folder in folders" :key="folder.id">
        <header>
            <h1>
                <a href="#">Ordername: {{ folder.name }}</a>
            </h1>
            <nav>
                <button type="button" @click="deleteFolder(folder)" class="tw-border-0 tw-p-0">
                    <StudipIcon shape="trash" class="tw-align-middle" />
                    <span class="sr-only">löschen</span>
                </button>
            </nav>
        </header>

        <section>
            <FolderTree
                :folders="children(folder)"
                :parents="parents"
                @add-child="(folder) => addChild(folder)"
                @delete-folder="(folder) => deleteFolder(folder)"
            />

            <article>
                <button type="button" class="button add" @click="addChild(folder)">Kind</button>
            </article>
        </section>
    </article>
</template>
