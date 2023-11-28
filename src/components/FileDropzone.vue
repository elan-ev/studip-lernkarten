<script setup>
import { ref } from 'vue';
import { useDropzone } from "vue3-dropzone";

const emit = defineEmits(['update:files']);
const props = defineProps(['fileid']);

const hover = ref(false);
const reject = ref('');

const onDrop = (acceptFiles, rejectReasons) => {
    emit('update:files', acceptFiles, props.fileid);

    reject.value = '';

    if (rejectReasons.length) {
        let messages = [];
        for (let i = 0; i < rejectReasons[0].errors.length; i++) {
            messages.push(rejectReasons[0].errors[i].message);
        }

        reject.value = messages.join(', ');
    }
}

const onDragenter = () => {
    console.log('dragenter');
    hover.value = true;
}

const onDragleave = () => {
    console.log('dragleave');
    hover.value = false;
}

const onDragover = () => {
    console.log('dragover');
    hover.value = false;
}

const { getRootProps, getInputProps, open, ...rest } = useDropzone({
    onDrop,
    onDragenter,
    onDragleave,
    onDragover,
    maxFiles: 1,
    maxSize: 8388608, // 8 MB
    // TODO: get the allowed max file size from Stud.IP config!
    accept: [
        'image/png',
        'image/jpeg',
        'image/webp'
    ]
});

</script>

<template>
    <section click="open"
        :classe="{
            hover: hover
        }"
    >
        <div v-bind="getRootProps()">
            <input v-bind="getInputProps()" />
            <slot name="dropzoneText">
                {{ $gettext('Ziehen sie eine Bilddatei hierhin oder klicken Sie, um ein Bild von ihrem Rechner auszuwählen.') }}
            </slot>
        </div>

        <span class="lernkarten-error">
            {{ reject }}
        </span>
    </section>
</template>


<style scoped>
section {
    display: block;
    border: 1px solid grey;
    text-align: center;
    font-weight: 900;
    font-size: 1.2em;
    padding-bottom: 0.5em;
}

section.hover {
    background-color: #999999;
}
</style>