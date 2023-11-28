<script setup>
import { watch, ref, toRaw, nextTick } from 'vue';
import { useGettext } from 'vue3-gettext';
import StudipDialog from './base/StudipDialog.vue';
import StudipMessageBox from './base/StudipMessageBox.vue';
import { useCardsStore } from '../stores/cards.js';
import FileDropzone from "./FileDropzone.vue";

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
const wysiwyg_editor = ref({});

const reset = () => {
    back.value = '';
    front.value = '';
};

const setIsOpen = (value) => {
    emit('update:open', value);
    reset();
};
const createOne = () => {
    const card = {
        model: cardType.value,
        fields: { front: front.value.value, back: back.value.value },
    };
    cardsStore.createCard(props.deck, card).then(() => setIsOpen(false));
};
const createMore = () => {
    const card = {
        model: cardType.value,
        fields: { front: front.value.value, back: back.value.value },
    };
    cardsStore.createCard(props.deck, card).then(reset);
};

const checkEditor = (ref, focus) => {
    nextTick(() => {
        let textarea = ref.value;
        let id = textarea.id;

        window.STUDIP.wysiwyg.replace(textarea);

        if (!window.STUDIP.wysiwyg.getEditor(textarea)) {
            setTimeout(() => {
                checkEditor(ref, focus);
            }, 300);
            return;
        }

        wysiwyg_editor[id] = window.STUDIP.wysiwyg.getEditor(textarea);

        if (focus) {
            toRaw(wysiwyg_editor[id]).editing.view.focus();
        }
        // using toRaw to remove Vue proxys. They do not work well with CKEditor
        toRaw(wysiwyg_editor[id]).ui.focusTracker.on('change:isFocused', () => {
            textarea.value = toRaw(wysiwyg_editor[id]).getData();
        });
    });
};

watch(
    () => props.open,
    (newValue) => {
        if (newValue == true) {
            checkEditor(front, true);
            checkEditor(back, false);
        } else {
            wysiwyg_editor.value = {};
        }
    },
);


const setImage = (files, fileid) => {
      console.log(files, fileid);
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
                        <FileDropzone @update:files="setImage" fileid="front" />
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
                        <FileDropzone @update:files="setImage" fileid="back"/>
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
