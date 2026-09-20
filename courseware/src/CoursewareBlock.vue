<template>
    <div class="cw-block cw-block-lernkarten">
        <component
            :is="coursewarePluginComponents.CoursewareDefaultBlock"
            ref="defaultBlock"
            :block="block"
            :canEdit="canEdit"
            :isTeacher="isTeacher"
            :preview="true"
            :defaultGrade="false"
            @storeEdit="storeBlock"
            @closeEdit="initCurrentData"
        >
            <template #content>
                <span v-if="!isBlockInitialized" class="sr-only">
                    {{
                        $gettext(
                            'Die Lernkarten werden angezeigt, nachdem der Block gespeichert wurde.',
                        )
                    }}
                </span>
                <div v-else>
                    <lernkarten-block :deck="sharedDeckId"></lernkarten-block>
                </div>
            </template>

            <template v-if="canEdit" #edit>
                <form class="default" @submit.prevent="onSubmit">
                    <lernkarten-deck-selector
                        :deck="sharedDeckId"
                        @change="onSelectDeck"
                    ></lernkarten-deck-selector>
                </form>
            </template>

            <template #info>
                {{ $gettext('Informationen zum Lernkartenblock') }}
            </template>
        </component>
    </div>
</template>

<script setup>
import { computed, inject, onMounted, provide, ref, watch } from 'vue';
import { useStore } from 'vuex';
import { createGettext } from 'vue3-gettext';
import translations from '@/locales/translations.json';
import { GETTEXT_KEY } from './gettext-key';
import LernkartenBlock from './LernkartenBlock.vue';
import LernkartenDeckSelector from './LernkartenDeckSelector.vue';

defineOptions({ name: 'courseware-lernkarten-block' });

const props = defineProps({
    block: Object,
    canEdit: Boolean,
    isTeacher: Boolean,
});

const gettext = createGettext({ translations, silent: true });
provide(GETTEXT_KEY, gettext);
const $gettext = gettext.$gettext;

const coursewarePluginComponents = inject('coursewarePluginComponents');

const store = useStore();
const context = computed(() => store.getters.context);
const isBlockInitialized = computed(() => props.block.attributes.payload.initialized);

const defaultBlock = ref(null);
const callback = ref(null);
const sharedDeckId = ref(null);

const updateBlock = (payload) => store.dispatch('updateBlockInContainer', payload);

const initCurrentData = () => {
    sharedDeckId.value = props.block.attributes.payload.shareddeck || null;
};

const onSelectDeck = (cb) => {
    callback.value = cb;
};

const storeBlock = () => {
    if (!callback.value) {
        return;
    }
    callback
        .value()
        .then((sharedDeck) => (sharedDeckId.value = sharedDeck.id))
        .then(() =>
            updateBlock({
                attributes: {
                    ...props.block.attributes,
                    payload: {
                        ...props.block.attributes.payload,
                        initialized: true,
                        shareddeck: sharedDeckId.value,
                    },
                },
                blockId: props.block.id,
                containerId: props.block.relationships.container.data.id,
            }),
        )
        .then(() => defaultBlock.value.displayFeature(false))
        .catch((error) => {
            console.debug('Error selecting deck', error);
        });
};

onMounted(() => {
    initCurrentData();
    if (!props.block.attributes.payload.initialized) {
        storeBlock();
    }
});
</script>
