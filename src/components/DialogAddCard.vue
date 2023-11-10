<script setup>
import { computed, ref } from 'vue';
import { useGettext } from 'vue3-gettext';
import StudipDialog from './base/StudipDialog.vue';
import StudipMessageBox from './base/StudipMessageBox.vue';
import { useCardsStore } from '../stores/cards.js';

const cardsStore = useCardsStore();
const { $gettext } = useGettext();

const props = defineProps(['open', 'deck']);
const emit = defineEmits(['update:open']);

const back = ref('');
const cardType = ref('basic');
const cardTypes = ref([{ text: $gettext('Einfach'), value: 'basic' }]);
const front = ref('');
const initialFocus = ref(null);

const reset = () => {
    back.value = '';
    front.value = '';
};
const setIsOpen = (value) => {
    emit('update:open', value);
    reset();
};
const createOne = () => {
    const card = { model: cardType.value, fields: { front: front.value, back: back.value } };
    cardsStore.createCard(props.deck, card).then(() => setIsOpen(false));
};
const createMore = () => {
    const card = { model: cardType.value, fields: { front: front.value, back: back.value } };
    cardsStore.createCard(props.deck, card).then(reset);
};
</script>

<template>
    <StudipDialog
        :close-text="$gettext('Abbrechen')"
        :height="600"
        :initial-focus="initialFocus"
        :open="open"
        :title="$gettext('Karten erstellen')"
        :width="600"
        @update:open="setIsOpen"
    >
        <template #dialogContent>
            <form class="default studipform" @submit.prevent="createOne">
                <div class="formpart">
                    <label class="studiprequired">
                        <span class="textlabel">
                            {{ $gettext('Kartentyp') }}
                        </span>
                        <span
                            class="asterisk"
                            :title="$gettext('Dies ist ein Pflichtfeld')"
                            aria-hidden="true"
                            >*</span
                        >
                        <select v-model="cardType">
                            <option v-for="option in cardTypes" :value="option.value">
                                {{ option.text }}
                            </option>
                        </select>
                    </label>
                </div>

                <div class="formpart">
                    <label class="studiprequired">
                        <span class="textlabel">
                            {{ $gettext('Vorderseite') }}
                        </span>
                        <span
                            class="asterisk"
                            :title="$gettext('Dies ist ein Pflichtfeld')"
                            aria-hidden="true"
                            >*</span
                        >
                        <textarea
                            v-model="front"
                            ref="initialFocus"
                            required
                            aria-required="true"
                        />
                    </label>
                </div>

                <div class="formpart">
                    <label class="studiprequired">
                        <span class="textlabel">
                            {{ $gettext('Rückseite') }}
                        </span>
                        <span
                            class="asterisk"
                            :title="$gettext('Dies ist ein Pflichtfeld')"
                            aria-hidden="true"
                            >*</span
                        >
                        <textarea v-model="back" required aria-required="true" />
                    </label>
                </div>
            </form>
        </template>

        <template #dialogButtons>
            <button type="button" class="button add" @click="createOne">
                {{ $gettext('Erstellen') }}
            </button>
            <button type="button" class="button add" @click="createMore">
                {{ $gettext('Erstellen und danach noch eine') }}
            </button>
        </template>
    </StudipDialog>
</template>
