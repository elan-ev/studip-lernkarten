<script setup>
import Button from './IconButton.vue';

const props = defineProps(['image', 'fileid']);
const emit = defineEmits(['update:files']);

const deleteImage = () => {
    emit('update:files', null, props.fileid);
}

</script>
<template>
    <div>
        <img :src="props.image">
        <Button icon="trash" type="button" @click="deleteImage">
            {{ $gettext('Bild löschen') }}
        </Button>
    </div>
</template>

<style scoped>
    img {
        width: 100%;
        max-width: 48em;
    }
</style>