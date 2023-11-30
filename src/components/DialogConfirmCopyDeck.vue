<script setup>
import { computed, ref } from 'vue';
import { useGettext } from 'vue3-gettext';
import Button from './IconButton.vue';
import StudipDialog from './base/StudipDialog.vue';
import StudipIcon from './base/StudipIcon.vue';
import { useDecksStore } from '../stores/decks.js';

const { $gettext } = useGettext();
const decksStore = useDecksStore();

const props = defineProps(['open', 'deck']);
const emit = defineEmits(['update:open']);

const setIsOpen = (value) => {
    emit('update:open', value);
};

const onConfirm = () => {
    decksStore.copyDeck(props.deck);
    setIsOpen(false);
};
</script>

<template>
    <StudipDialog
        :open="open"
        :question="$gettext('Möchten Sie den Kartensatz in ihren Arbeitsplatz kopieren?')"
        :title="$gettext('Kartensatz kopieren')"
        @confirm="onConfirm"
        @update:open="setIsOpen"
    >
    </StudipDialog>
</template>
