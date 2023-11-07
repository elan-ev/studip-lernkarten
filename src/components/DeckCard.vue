<script setup>
import { computed } from "vue";
import RadialProgress from './RadialProgress.vue';
import StudipAvatar from './base/StudipAvatar.vue';
import StudipIcon from './base/StudipIcon.vue';

const props = defineProps(['deck']);

const avatarUrl = computed(() => props.deck.owner.data.meta.avatar.small);
const formattedName = computed(() => props.deck.owner.data['formatted-name']);
</script>

<template>
    <section class="tw-flex tw-gap-2 tw-h-24 tw-py-2 tw-cursor-pointer tw-border tw-border-solid tw-border-[var(--light-gray-color-20)]">
        <div class="tw-flex tw-items-center tw-justify-center tw-w-24 tw-aspect-square">
            <RadialProgress :percent="17" />
        </div>
        <div class="tw-flex tw-flex-col tw-flex-grow tw-justify-between">
            <div class="tw-text-lg tw-font-bold">{{ deck.name }}</div>
            <div class="tw-flex tw-items-end tw-justify-between">
                <StudipAvatar :avatar-url="avatarUrl" :formatted-name="formattedName"/>
                <div class="tw-px-4">
                    <a href="#" class="tw-flex tw-items-center tw-gap-1">
                        <StudipIcon shape="refresh" role="info" />
                        {{ $gettext("Lernen") }}
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>
