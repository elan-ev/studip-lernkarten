<script setup>
import { onMounted, nextTick, reactive, ref, toRaw } from 'vue';
import Button from '../IconButton.vue';
import { useCardsStore } from '../../stores/cards.js';

const cardsStore = useCardsStore();

const props = defineProps(['card', 'mode']);
const emit = defineEmits(['update:mode']);

const isStoring = ref(false);
const localCard = reactive({
    front: props.card.fields.front,
    back: props.card.fields.back,
});

const front = ref(null);
const back = ref(null);
const wysiwyg_editor = ref({});

const onStore = () => {
    if (isStoring.value) {
        return;
    }
    isStoring.value = true;
    const { front, back } = localCard;
    cardsStore
        .updateFields(props.card, { front, back })
        .then(onCancel)
        .finally(() => (isStoring.value = false));
};
const onCancel = () => {
    emit('update:mode', 'show');
};

const checkEditor = (ref, focus) => {
    nextTick(() => {
        let textarea = ref.value;
        let id = textarea.id;

        window.STUDIP.wysiwyg.replace(textarea);

        if (!window.STUDIP.wysiwyg.getEditor(textarea)) {
            setTimeout(() => {
                checkEditor(ref, focus)
            }, 300);
            return;
        }

        wysiwyg_editor[id] = window.STUDIP.wysiwyg.getEditor(textarea);

        if (focus) {
            toRaw(wysiwyg_editor[id]).editing.view.focus();
        }
        // using toRaw to remove Vue proxys. They do not work well with CKEditor
        toRaw(wysiwyg_editor[id]).ui.focusTracker.on( 'change:isFocused', () => {
            console.log('value changed', toRaw(wysiwyg_editor[id]).getData(), id, localCard);
            localCard[id] = toRaw(wysiwyg_editor[id]).getData();
        });
    });
};

onMounted(() => {
    checkEditor(front, true);
    checkEditor(back, false);
})

</script>
<template>
    <section>
        <article>
            <form class="default studipform" @submit.prevent="">
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

                        <div class="tw-text">
                            <textarea
                                id="front"
                                v-model="localCard.front"
                                class="tw-w-full tw-box-border"
                                ref="front"
                            />
                        </div>
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
                        <div class="tw-text">
                            <textarea
                                id="back"
                                v-model="localCard.back"
                                class="tw-w-full tw-box-border"
                                ref="back"
                            />
                        </div>
                    </label>
                </div>
            </form>
        </article>
        <footer>
            <Button @click="onStore" icon="accept" type="button">{{
                $gettext('Speichern')
            }}</Button>
            <Button @click="onCancel" icon="decline" type="button">{{
                $gettext('Abbrechen')
            }}</Button>
        </footer>
    </section>
</template>
