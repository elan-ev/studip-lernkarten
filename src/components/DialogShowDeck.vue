<script setup>
import { $gettext } from '../gettext.js';
import { ref } from 'vue';
import DeckDetails from './DeckDetails.vue';
import StudipDialog from './base/StudipDialog.vue';

defineProps(['open', 'deck']);
const emit = defineEmits(['update:open']);

const initialFocus = ref(null);

const reset = () => {};
const setIsOpen = (value) => {
    emit('update:open', value);
    reset();
};
</script>

<template>
    <StudipDialog
        :close-text="$gettext('Schließen')"
        :height="800"
        :initial-focus="initialFocus"
        :open="open"
        :title="$gettext('Kartensatzdetails')"
        :width="800"
        @update:open="setIsOpen"
    >
        <template #dialogContent>
            <DeckDetails :deck="deck" />
        </template>
    </StudipDialog>
</template>
