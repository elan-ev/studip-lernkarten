<script setup>
import IconButton from '../components/IconButton.vue';
import { useFsrs } from '../composables/fsrs.js';

defineEmits(['repeat']);

const { Rating } = useFsrs();
console.debug(Rating);
</script>

<template>
    <div>
        <IconButton icon="accept" @click="$emit('repeat', Rating.Easy)">
            {{ $gettext('Einfach') }}
        </IconButton>
        <IconButton icon="accept" @click="$emit('repeat', Rating.Good)">
            {{ $gettext('Gut') }}
        </IconButton>
        <IconButton icon="accept" @click="$emit('repeat', Rating.Hard)">
            {{ $gettext('Schwer') }}
        </IconButton>
        <IconButton icon="decline" @click="$emit('repeat', Rating.Again)">
            {{ $gettext('Nochmal') }}
        </IconButton>
    </div>
</template>
