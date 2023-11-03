import { ref, computed } from 'vue';
import { defineStore } from 'pinia';
import { api } from '../api.js';
import { useContextStore } from './context.js';

export const useFoldersStore = defineStore(
    'folders',
    () => {
        const allFolders = ref([]);
        const isLoading = ref(false);

        async function fetch() {
            isLoading.value = true;
            const { data } = await api.fetch('lernkarten-folders');
            isLoading.value = false;
            allFolders.value = data;
        }

        function byContext(context) {
            return allFolders.value.filter((folder) => folder.context.data.id === context);
        }

        const contextStore = useContextStore();

        function createFolder(name, parent) {
            const data = {
                name,
                context: { data: { id: contextStore.id, type: contextStore.type } },
                parent: parent
                    ? { data: { id: parent.id, type: 'lernkarten-folders' } }
                    : { data: null }
            };
            return api.create('lernkarten-folders', data).then(({ data }) => {
                allFolders.value.push(data);
            });
        }

        function deleteFolder(folder) {
            return api
                .delete('lernkarten-folders', folder.id)
                .then(
                    () => (allFolders.value = allFolders.value.filter(({ id }) => id !== folder.id))
                );
        }

        return { allFolders, byContext, createFolder, deleteFolder, fetch, isLoading };
    },
    {
        persist: true
    }
);
