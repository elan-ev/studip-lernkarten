<script setup>
import { computed } from 'vue';
const props = defineProps(['course']);
const style = computed(() => {
    return {
        'background-image': `url('${window.STUDIP.ABSOLUTE_URI_STUDIP}pictures/course/nobody_medium.png')`,
    };
});
</script>

<template>
    <div class="tw-items-center tw-flex tw-gap-2">
        <span
            :title="course.title"
            alt=""
            class="my-courses-avatar course-avatar-small"
            :style="style"
        ></span>
        <span>{{ course.title }}</span>
    </div>
</template>
