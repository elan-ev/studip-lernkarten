<script setup>
import { computed, ref } from 'vue';
import { RouterLink } from 'vue-router';
import StudipIcon from '../components/base/StudipIcon.vue';

const props = defineProps(['folders']);
const emit = defineEmits(['delete-folder']);
const deleteFolder = (folder) => emit('delete-folder', folder);
</script>

<template>
    <article
        v-if="folders.length > 0"
        class="studip toggle tw-my-2"
        v-for="folder in folders"
        :key="folder.id"
    >
        <header>
            <h1>
                <RouterLink :to="{ name: 'folder', params: { id: folder.id } }">{{
                    folder.name
                }}</RouterLink>
            </h1>
            <nav>
                <button type="button" @click="deleteFolder(folder)" class="tw-border-0 tw-p-0">
                    <StudipIcon shape="trash" class="tw-align-middle" />
                    <span class="sr-only">löschen</span>
                </button>
            </nav>
        </header>
    </article>
    <article v-else>
        <slot name="empty"></slot>
    </article>
</template>
