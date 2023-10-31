import { ref, computed } from 'vue';
import { defineStore } from 'pinia';
import { api } from '../api.js';

export const useFoldersStore = defineStore(
    'folders',
    () => {
        const folders = ref([]);
        const isLoading = ref(false);

        async function fetch() {
            isLoading.value = true;
            const { data } = await api.fetch('lernkarten-folders');
            isLoading.value = false;
            folders.value = data;
        }

        return { fetch, folders, isLoading };
    },
    {
        persist: true
    }
);
