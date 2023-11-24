<script setup>
import { computed, ref } from 'vue';
import { RouterLink } from 'vue-router';
import DialogCopySharedDeck from './DialogCopySharedDeck.vue';
import IconButton from './IconButton.vue';
import StudipAvatar from './base/StudipAvatar.vue';
import StudipIcon from './base/StudipIcon.vue';
import { useSharedDecksStore } from '../stores/shared-decks.js';

const sharedDecksStore = useSharedDecksStore();

const props = defineProps(['sharedDeck']);

const showCopyDialog = ref(false);

const avatarUrl = computed(() => props.sharedDeck.sharer.data.meta.avatar.small);
const formattedName = computed(() => props.sharedDeck.sharer.data['formatted-name']);

const onCopy = () => {
    showCopyDialog.value = true;
};

const onCoLearn = () => {
    sharedDecksStore.coLearn(props.sharedDeck);
};
</script>

<template>
    <section
        class="tw-flex tw-gap-2 tw-h-24 tw-py-2 tw-cursor-pointer tw-border tw-border-solid tw-border-[var(--light-gray-color-20)]"
    >
        <div class="tw-flex tw-items-center tw-justify-center tw-w-24 tw-aspect-square">
            <StudipIcon shape="share" role="info" :size="32" />
        </div>
        <div class="tw-flex tw-flex-col tw-flex-grow tw-justify-between">
            <div class="tw-text-lg tw-font-bold">{{ sharedDeck.deck.data.name }}</div>
            <div class="tw-flex tw-items-end tw-justify-between">
                <StudipAvatar :avatar-url="avatarUrl" :formatted-name="formattedName" />
                <div class="tw-px-4">
                    <IconButton icon="copy" type="button" @click="onCopy">
                        {{ $gettext('Kopieren') }}
                    </IconButton>
                    <IconButton icon="refresh" type="button" @click="onCoLearn">
                        {{ $gettext('Mitlernen') }}
                    </IconButton>
                </div>
            </div>
        </div>
    </section>
    <DialogCopySharedDeck v-model:open="showCopyDialog" :shared-deck="sharedDeck" />
</template>
