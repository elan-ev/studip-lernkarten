<script setup>
import { computed, ref } from 'vue';
import { useGettext } from 'vue3-gettext';
import Button from './IconButton.vue';
import StudipDialog from './base/StudipDialog.vue';
import StudipIcon from './base/StudipIcon.vue';

const { $gettext } = useGettext();

const props = defineProps(['open', 'deck']);
const emit = defineEmits(['update:open', 'confirm']);

const initialFocus = ref(null);

const reset = () => {};

const setIsOpen = (value) => {
    emit('update:open', value);
    reset();
};

const onConfirm = () => {
    emit('confirm', {});
};
</script>

<template>
    <StudipDialog
        :confirm-text="$gettext('Lernen')"
        :close-text="$gettext('Schließen')"
        :height="600"
        :initial-focus="initialFocus"
        :open="open"
        :title="$gettext('Lernoptionen')"
        :width="600"
        @confirm="onConfirm"
        @update:open="setIsOpen"
    >
        <template #dialogContent>
            <form class="default studipform" @submit.prevent="onConfirm">
                <div class="formpart">
                    <label class="studiprequired">
                        <span class="textlabel">
                            {{ $gettext('Kartensatz') }}
                        </span>
                        <span
                            class="asterisk"
                            :title="$gettext('Dies ist ein Pflichtfeld')"
                            aria-hidden="true"
                            >*</span
                        >
                        <select>
                            <option>{{ deck.name }}</option>
                        </select>
                    </label>
                </div>
            </form>
        </template>
    </StudipDialog>
</template>
