<script setup>
const props = defineProps(['card', 'index']);
const emit = defineEmits(['select']);

const extractContent = (html) => {
    return new DOMParser().parseFromString(html, 'text/html').documentElement.textContent;
};
</script>

<template>
    <div class="lernkarten-card-list-item" @click="emit('select')">
        <div>({{ index }}) F: {{ extractContent(card.fields.front) }}</div>
        <div>A: {{ extractContent(card.fields.back) }}</div>
    </div>
</template>

<style>
.lernkarten-card-list-item {
    display: flex;
    cursor: pointer;
    border: 1px solid var(--light-gray-color-20);
}
.lernkarten-card-list-item + .lernkarten-card-list-item {
    margin-block-start: 0.5rem;
}
.lernkarten-card-list-item > div {
    flex-basis: 50%;
    padding: 0.5rem;
}
.lernkarten-card-list-item > div:last-child {
    border-left: 1px solid var(--light-gray-color-20);
}
</style>
