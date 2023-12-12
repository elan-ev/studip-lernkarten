<script setup>
import { computed, inject, ref, watch } from 'vue';

const ClassicEditor = inject('ClassicEditor');

const props = defineProps(['modelValue']);
const emit = defineEmits(['update:modelValue']);

const currentText = ref(props.modelValue);
const editor = ref(ClassicEditor);
const editorConfig = ref({});
const textarea = ref(null);

const prefill = (editor) => (currentText.value = props.modelValue);

const onInput = (value) => {
    currentText.value = value;
    emit('update:modelValue', value);
};

watch(
    () => props.modelValue,
    () => (currentText.value = props.modelValue)
);
</script>

<template>
    <ckeditor
        :editor="editor"
        :config="editorConfig"
        @ready="prefill"
        v-model="currentText"
        @input="onInput"
    ></ckeditor>
</template>
