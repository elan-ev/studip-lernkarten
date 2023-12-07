<script setup>
import { nextTick, onBeforeUnmount, ref, watch } from 'vue';
import { useGettext } from 'vue3-gettext';
import StudipDialog from './base/StudipDialog.vue';
import { useCardsStore } from '../stores/cards.js';
import FileDropzone from "./FileDropzone.vue";
import CardImage from "./CardImage.vue";

const cardsStore = useCardsStore();
const { $gettext } = useGettext();

const props = defineProps(['open', 'deck']);
const emit = defineEmits(['update:open']);

const cardType = ref('basic');
const cardTypes = ref([
    { text: $gettext('Einfach'), value: 'basic' },
    { text: $gettext('Bild und optionaler Text'), value: 'image' },
]);
const front = ref(null);
const back = ref(null);
const images = ref({});

const reset = () => {
    window.STUDIP.wysiwyg.getEditor(front.value).setData('');
    window.STUDIP.wysiwyg.getEditor(back.value).setData('');

    back.value.value = '';
    front.value.value = '';
    images.value = {};

};

const setIsOpen = (value) => {
    emit('update:open', value);
    reset();
};
const createOne = () => {
    const card = {
        model: cardType.value,
        fields: { front: front.value.value, back: back.value.value, images: images.value },
    };
    cardsStore.createCard(props.deck, card).then(() => setIsOpen(false));
};
const createMore = () => {
    const card = {
        model: cardType.value,
        fields: { front: front.value.value, back: back.value.value, images: images.value },
    };
    cardsStore.createCard(props.deck, card).then(reset);
};

const checkEditor = (ref, focus) => {
    nextTick(() => {
        let textarea = ref.value;

        window.jQuery(textarea).on('load.wysiwyg', () => {
            const editor = window.STUDIP.wysiwyg.getEditor(textarea);
            if (focus) {
                editor.editing.view.focus();
            }
            editor.ui.focusTracker.on('change:isFocused', () => {
                textarea.value = editor.getData();
            })
        });

        // make sure, wysiwyg is cleared before to force reinitialization
        if (window.STUDIP.wysiwyg.getEditor(textarea)) {
            window.STUDIP.wysiwyg.replace(textarea);
        }

        window.STUDIP.wysiwyg.replace(textarea);
    });
};

onBeforeUnmount(() => {
    window.jQuery(front.value).off();
    window.jQuery(back.value).off();
});

watch(
    () => props.open,
    (nowOpen) => {
        if (nowOpen == true) {
            checkEditor(front, true);
            checkEditor(back, false);
        } else {
            window.jQuery(front.value).off();
            window.jQuery(back.value).off();
        }
    },
);

const setImage = (base64, fileid) => {
    images.value[fileid] = base64;
}
</script>

<template>
    <StudipDialog
        :close-text="$gettext('Abbrechen')"
        :height="600"
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
                    <label class="studiprequired" for="card-text-front">
                        <span class="textlabel">
                            {{ $gettext('Vorderseite') }}
                        </span>
                        <span
                            class="asterisk"
                            :title="$gettext('Dies ist ein Pflichtfeld')"
                            aria-hidden="true"
                            >*</span
                        >
                    </label>

                    <div v-if="cardType == 'image'">
                        <CardImage v-if="images['front']" :edit="true"
                            :image="images['front']" fileid="front" @update:files="setImage"/>
                        <FileDropzone v-else @update:files="setImage" fileid="front"/>
                    </div>

                    <textarea id="card-text-front" ref="front" required aria-required="true" />
                </div>

                <div class="formpart">
                    <label class="studiprequired" for="card-text-back">
                        <span class="textlabel">
                            {{ $gettext('Rückseite') }}
                        </span>
                        <span
                            class="asterisk"
                            :title="$gettext('Dies ist ein Pflichtfeld')"
                            aria-hidden="true"
                            >*</span
                        >
                    </label>

                    <div v-if="cardType == 'image'">
                        <CardImage v-if="images['back']" :edit="true"
                            :image="images['back']" fileid="back" @update:files="setImage"/>
                        <FileDropzone v-else @update:files="setImage" fileid="back"/>
                    </div>

                    <textarea id="card-text-back" ref="back" required aria-required="true" />
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
