<script setup>
const props = defineProps(['card']);
</script>
<template>
    <section>
        <article>
            <div
                class="tw-p-4 tw-text-lg tw-border tw-border-solid tw-border-[var(--light-gray-color-20)]"
                v-html="card.fields.front"
            ></div>
            <div
                class="tw-p-4 tw-text-lg tw-border tw-border-solid tw-border-[var(--light-gray-color-20)]"
                v-html="card.fields.back"
            ></div>
        </article>
    </section>
</template>
