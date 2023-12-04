<script setup>
import StudipDialog from './base/StudipDialog.vue';

const props = defineProps(['open']);
const emit = defineEmits(['update:open', 'confirm']);

</script>

<template>
    <StudipDialog
        :question="$gettext('Möchten Sie diese Karte wirklich löschen?')"
        :open="open"
        @update:open="$emit('update:open', false)"
        @confirm="$emit('confirm')"
        :title="$gettext('Karte löschen')"
    />
</template>
