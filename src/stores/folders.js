import { ref, computed } from 'vue';
import { defineStore } from 'pinia';
import { api } from '../api.js';
import { useContextStore } from './context.js';

export const useFoldersStore = defineStore(
    'folders',
    () => {
        const contextStore = useContextStore();

        const allFolders = ref([]);
        const isLoading = ref(false);

        const parents = computed(() => {
            const folders = byContext();
            return folders.value.reduce((map, folder) => {
                const parentId = folder.parent.data?.id ?? null;
                if (!map.has(parentId)) {
                    map.set(parentId, []);
                }
                map.get(parentId).push(folder);
                return map;
            }, new Map());
        });

        const topFolders = computed(() => {
            const context = contextStore.id;
            return _.sortBy(
                allFolders.value.filter(
                    (folder) => !folder.parent.data && folder.context.data.id === context
                ),
                'name'
            );
        });

        const byContext = computed(() => {
            const context = contextStore.id;
            return allFolders.value.filter((folder) => folder.context.data.id === context);
        });

        async function fetch() {
            isLoading.value = true;
            const { data } = await api.fetch('lernkarten-folders');
            isLoading.value = false;
            allFolders.value = data;
        }

        function ancestors(folder, path = []) {
            if (!folder.parent.data) {
                return path;
            }
            const parent = byId(folder.parent.data.id);
            return ancestors(parent, [parent, ...path]);
        }

        function byId(id) {
            return allFolders.value.find((folder) => folder.id === id);
        }

        function children(id) {
            return allFolders.value.filter((folder) => folder.parent.data?.id === id);
        }

        function createFolder(name, parent) {
            const data = {
                name,
                context: { data: { id: contextStore.id, type: contextStore.type } },
                parent: parent
                    ? { data: { id: parent.id, type: 'lernkarten-folders' } }
                    : { data: null },
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

        return {
            allFolders,
            ancestors,
            byContext,
            byId,
            children,
            createFolder,
            deleteFolder,
            fetch,
            isLoading,
            parents,
            topFolders,
        };
    },
    {
        persist: true,
    }
);
