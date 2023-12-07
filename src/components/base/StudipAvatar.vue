<script setup>
const props = defineProps({
    avatarUrl: {
        type: String,
        required: true,
    },
    formattedName: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="tw-items-center tw-flex tw-gap-2">
        <span>
            <img class="tw-align-middle" :src="avatarUrl" />
        </span>
        <span>{{ formattedName }}</span>
    </div>
</template>
