<script setup>
import { computed, ref } from 'vue';
import { RouterLink } from 'vue-router';
import { useGettext } from 'vue3-gettext';
import RadialProgress from './RadialProgress.vue';
import StudipActionMenu from './base/StudipActionMenu.vue';
import StudipAvatar from './base/StudipAvatar.vue';
import StudipIcon from './base/StudipIcon.vue';
import DialogConfirmDeleteDeck from './DialogConfirmDeleteDeck.vue';
import { useDecksStore } from '../stores/decks.js';

const { $gettext } = useGettext();
const decksStore = useDecksStore();

const props = defineProps(['deck']);
const emit = defineEmits(['deleted']);

const showConfirmDelete = ref(false);

const avatarUrl = computed(() => props.deck.owner.data.meta.avatar.small);
const formattedName = computed(() => props.deck.owner.data['formatted-name']);

const actionMenuItems = computed(() => [
    {
        id: 1,
        label: $gettext('Kartensatz löschen'),
        icon: 'trash',
        emit: 'delete',
    },
]);

const onDeleteDeck = () => (showConfirmDelete.value = true);

const deleteDeck = () => {
    showConfirmDelete.value = false;
    decksStore.deleteDeck(props.deck).then(() => {
        emit('deleted');
    });
};
</script>

<template>
    <section
        class="tw-flex tw-gap-2 tw-h-24 tw-py-2 tw-border tw-border-solid tw-border-[var(--light-gray-color-20)]"
    >
        <div
            class="tw-flex tw-items-center tw-justify-center tw-w-24 tw-aspect-square tw-cursor-pointer"
            @click="$emit('select', deck)"
        >
            <RadialProgress :percent="17" />
        </div>
        <div class="tw-flex tw-flex-col tw-flex-grow tw-justify-between">
            <div class="tw-cursor-pointer tw-flex-grow" @click="$emit('select', deck)">
                <span class="tw-text-lg tw-font-bold">{{ deck.name }}</span>
                <span v-if="deck.template.data"> (Kopie von {{ deck.template.data.name }}) </span>
            </div>
            <div class="tw-flex tw-items-end tw-justify-between">
                <StudipAvatar :avatar-url="avatarUrl" :formatted-name="formattedName" />
                <div class="tw-pl-4 tw-flex tw-gap-2">
                    <RouterLink
                        :to="{ name: 'study', params: { id: deck.id } }"
                        class="tw-flex tw-items-center tw-gap-1"
                    >
                        <StudipIcon shape="refresh" role="info" />
                        {{ $gettext('Lernen') }}
                    </RouterLink>
                    <StudipActionMenu
                        :items="actionMenuItems"
                        :collapseAt="0"
                        @delete="onDeleteDeck"
                    />
                </div>
            </div>
        </div>
    </section>
    <DialogConfirmDeleteDeck v-model:open="showConfirmDelete" @confirm="deleteDeck" />
</template>
