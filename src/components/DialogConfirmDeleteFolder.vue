<script setup>
import { computed, ref } from 'vue';
import StudipDialog from './base/StudipDialog.vue';
import StudipMessageBox from './base/StudipMessageBox.vue';

const props = defineProps(['open']);
const emit = defineEmits(['update:open', 'confirm']);

const setIsOpen = (value) => emit('update:open', value);
const onConfirm = () => emit('confirm');
</script>

<template>
    <StudipDialog
        :question="$gettext('Möchten Sie den Ordner wirklich löschen?')"
        :open="open"
        @update:open="setIsOpen(false)"
        @confirm="onConfirm"
        :title="$gettext('Ordner löschen')"
    />
</template>
