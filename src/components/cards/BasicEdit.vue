<script setup>
import { onMounted, reactive, ref } from 'vue';
import Button from '../IconButton.vue';
import { useCardsStore } from '../../stores/cards.js';

const cardsStore = useCardsStore();

const props = defineProps(['card', 'mode']);
const emit = defineEmits(['update:mode']);

const initialFocus = ref(null);
const isStoring = ref(false);
const localCard = reactive({
    front: props.card.fields.front,
    back: props.card.fields.back,
});

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

onMounted(() => initialFocus.value.focus());
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

                        <div class="tw-text-lg">
                            <textarea
                                v-model="localCard.front"
                                class="tw-w-full tw-box-border"
                                ref="initialFocus"
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
                        <div class="tw-text-lg">
                            <textarea v-model="localCard.back" class="tw-w-full tw-box-border" />
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
