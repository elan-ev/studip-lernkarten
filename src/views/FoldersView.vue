<script setup>
import { computed } from 'vue';
import { useContextStore } from '../stores/context.js';
import { useFoldersStore } from '../stores/folders.js';

const contextStore = useContextStore();
const foldersStore = useFoldersStore();
foldersStore.fetch();

const topFolders = computed(() => {
    return foldersStore.folders.filter((folder) => {
        return !folder.parent.data && folder.context.data.id === contextStore.id;
    });
});
</script>

<template>
    <main>
        <header>
            <h2>{{ $gettext('Ordnerverwaltung') }}</h2>
        </header>

        <section>
            <article>
                <pre>{{ foldersStore.isLoading }}</pre>
                <pre>{{ foldersStore.folders }}</pre>
                <pre>{{ topFolders }}</pre>
            </article>

            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
            incididunt ut labore et dolore magna aliqua. Ut enimad minim veniam, quis nostrud
            exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
            dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt
            mollit anim id est laborum.
        </section>
    </main>
</template>
