<script setup>
import { computed, ref } from 'vue';
import { useGettext } from 'vue3-gettext';
import Button from './IconButton.vue';
import CardBasicEdit from './cards/BasicEdit.vue';
import CardBasicShow from './cards/BasicShow.vue';
import StudipDialog from './base/StudipDialog.vue';
import StudipIcon from './base/StudipIcon.vue';
import { useFsrs } from '../composables/fsrs.js';
import { useCardsStore } from '../stores/cards.js';
import DialogConfirmDeleteCard from './DialogConfirmDeleteCard.vue';

const cardsStore = useCardsStore();
const { $gettext } = useGettext();
const { translatedStates } = useFsrs();

const cardViews = {
    show: CardBasicShow,
    edit: CardBasicEdit,
};

const props = defineProps(['open', 'card', 'cardIndex', 'deck', 'number-of-cards']);
const emit = defineEmits(['update:open', 'show-next', 'show-prev', 'delete']);

const initialFocus = ref(null);
const cardViewMode = ref('show');
const showConfirmDelete = ref(false);

const cardView = computed(() => cardViews[cardViewMode.value]);
const readableState = computed(() => translatedStates[props.card.state]);

const reset = () => {};
const setIsOpen = (value) => {
    emit('update:open', value);
    reset();
};
const onSubmit = () => {};
const onEdit = () => {
    console.debug('onEdit', props.card, cardViewMode.value);
    cardViewMode.value = 'edit';
};
const onMove = () => {
    console.debug('onMove', props.card);
};
const onReverse = () => {
    console.debug('onReverse', props.card);
};
const onDelete = () => {
    console.debug('onDelete', props.card);
    showConfirmDelete.value = true;
};

const deleteCard = () => {
    showConfirmDelete.value = false;
    emit('show-next');

    cardsStore.deleteCard(props.card).then(() => {
        emit('delete');
    });
}
</script>

<template>
    <StudipDialog
        :close-text="$gettext('Schließen')"
        :height="600"
        :initial-focus="initialFocus"
        :open="open"
        :title="$gettext('Kartendetails')"
        :width="800"
        @update:open="setIsOpen"
    >
        <template #dialogContent>
            <div class="tw-flex tw-items-stretch">
                <div class="tw-w-12 tw-flex tw-items-center tw-justify-start">
                    <button
                        type="button"
                        @click="emit('show-prev')"
                        class="tw-border-0 tw-bg-transparent tw-p-0 tw-w-full tw-h-full tw-cursor-pointer"
                    >
                        <StudipIcon shape="arr_1left" :size="24" />
                    </button>
                </div>
                <div class="tw-grow">
                    <div class="tw-flex tw-justify-between tw-items-center">
                        <div>
                            <Button icon="edit" type="button" @click="onEdit">
                                {{ $gettext('Bearbeiten') }}
                            </Button>
                            <Button disabled icon="arr_1right" type="button" @click="onMove">
                                {{ $gettext('Verschieben') }}
                            </Button>
                            <Button disabled icon="refresh" type="button" @click="onReverse">
                                {{ $gettext('Umdrehen') }}
                            </Button>
                            <Button icon="trash" type="button" @click="onDelete">
                                {{ $gettext('Löschen') }}
                            </Button>
                        </div>
                        <div>
                            <StudipIcon shape="dialog-cards" role="info" class="tw-align-middle" />
                            {{ cardIndex + 1 }}/{{ numberOfCards }}
                        </div>
                    </div>
                    <div>
                        <div class="tw-flex tw-gap-2">
                            <div class="tw-grow tw-p-2 tw-text-[#00A279]">
                                <StudipIcon shape="accept" />
                                {{
                                    $gettext('%{ count } genau gewusst', {
                                        count: card['easy-count'],
                                    })
                                }}
                            </div>
                            <div class="tw-grow tw-p-2 tw-text-[#ADC447]">
                                {{
                                    $gettext('%{ count } gewusst', {
                                        count: card['good-count'],
                                    })
                                }}
                            </div>
                            <div class="tw-grow tw-p-2 tw-text-[#e79e3d]">
                                {{
                                    $gettext('%{ count } kaum gewusst', {
                                        count: card['hard-count'],
                                    })
                                }}
                            </div>
                            <div class="tw-grow tw-p-2 tw-text-[#db4646]">
                                {{
                                    $gettext('%{ count } nicht gewusst', {
                                        count: card['again-count'],
                                    })
                                }}
                            </div>
                            <div class="tw-grow tw-p-2 tw-bg-slate-100">
                                {{ readableState }}
                            </div>
                        </div>

                        <component
                            :is="cardView"
                            :card="card"
                            class="tw-mt-4"
                            v-model:mode="cardViewMode"
                        />
                    </div>
                </div>
                <div class="tw-w-12 tw-flex tw-items-center tw-justify-end">
                    <button
                        type="button"
                        @click="emit('show-next')"
                        class="tw-border-0 tw-bg-transparent tw-p-0 tw-w-full tw-h-full tw-cursor-pointer"
                    >
                        <StudipIcon shape="arr_1right" :size="24" />
                    </button>
                </div>
            </div>
        </template>
    </StudipDialog>
    <DialogConfirmDeleteCard v-model:open="showConfirmDelete" @confirm="deleteCard" />
</template>
