<script setup>
import { computed, ref } from 'vue';
import { RouterLink } from 'vue-router';
import StudipIcon from '../components/base/StudipIcon.vue';

const props = defineProps(['folders']);
const emit = defineEmits(['delete-folder']);
const deleteFolder = (folder) => emit('delete-folder', folder);
const editFolder = (folder) => emit('edit-folder', folder);
</script>

<template>
    <colgroup>
        <col style="width: 36px;">
        <col>
        <col style="width: 64px;">
    </colgroup>
    <thead>
        <tr class="sortable">
            <th></th>
            <th class="sortasc">{{ $gettext('Name') }}</th>
            <th class="actions">{{ $gettext('Aktionen') }}</th>
        </tr>
    </thead>

    <tbody>
        <tr
            v-if="folders.length > 0"
            class="studip toggle tw-my-2"
            v-for="folder in folders"
            :key="folder.id"
        >
            <td>
                <StudipIcon
                    shape="folder-empty"
                    :height="26"
                    :width="26"
                    class="tw-align-middle tw-mr-1 tw-mb-1"
                />
            </td>
            <td>
                <RouterLink :to="{ name: 'folder', params: { id: folder.id } }">{{
                    folder.name
                }}</RouterLink>
            </td>
            <td class="actions">
                <nav>
                    <button type="button" @click="editFolder(folder)"
                        class="tw-border-0 tw-p-0 tw-bg-transparent tw-cursor-pointer tw-mr-2"
                        :title="$gettext('Bearbeiten')"
                    >
                        <StudipIcon shape="edit" class="tw-align-middle" />
                        <span class="sr-only">{{ $gettext('Bearbeiten') }}</span>
                    </button>
                    <button type="button" @click="deleteFolder(folder)"
                        class="tw-border-0 tw-p-0 tw-bg-transparent tw-cursor-pointer"
                        :title="$gettext('Löschen')"
                    >
                        <StudipIcon shape="trash" class="tw-align-middle" />
                        <span class="sr-only">{{ $gettext('Löschen') }}</span>
                    </button>
                </nav>
            </td>
        </tr>
        <tr v-else>
            <td colspan="3">
                <slot name="empty">
                    {{ $gettext('Dieser Ordner ist leer') }}
                </slot>
            </td>
        </tr>
    </tbody>
</template>
