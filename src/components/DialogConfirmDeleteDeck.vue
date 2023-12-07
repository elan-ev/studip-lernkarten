<script setup>
import { computed, ref } from 'vue';
import StudipDialog from './base/StudipDialog.vue';
import StudipMessageBox from './base/StudipMessageBox.vue';

const props = defineProps(['open']);
const emit = defineEmits(['update:open', 'confirm']);

const setIsOpen = (value) => emit('update:open', value);
</script>

<template>
    <StudipDialog
        :question="$gettext('Möchten Sie den Kartensatz wirklich löschen?')"
        :open="open"
        @update:open="$emit('update:open', false)"
        @confirm="$emit('confirm')"
        :title="$gettext('Kartensatz löschen')"
    />
</template>
