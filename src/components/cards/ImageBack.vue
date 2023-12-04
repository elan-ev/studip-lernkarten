<script setup>
const props = defineProps(['card']);
</script>

<template>
    <section v-if="card">
        <img v-if="card.fields.images?.front" :src="card.fields.images['back']" >
        <div v-html="card.fields.back"></div>
    </section>
</template>

<style scoped>
section {
    border: 1px solid var(--content-color-20);
}
section > img {
    max-width: 48em;
    width: 100%;
    margin-left: 12px;
}
section > div {
    background: var(--content-color-10);
    font-size: 20px;
    padding-block: 12px;
    padding-inline: 12px;
}
</style>
