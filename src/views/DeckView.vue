<script setup>
import { computed, ref } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue';
import Button from '../components/IconButton.vue';
import DeckCardsPanel from '../components/DeckCardsPanel.vue';
import DeckInfoPanel from '../components/DeckInfoPanel.vue';
import DialogAdjustLearningOptions from '../components/DialogAdjustLearningOptions.vue';
import MessageBox from '../components/base/StudipMessageBox.vue';
import StudipIcon from '../components/base/StudipIcon.vue';
import { useCardsStore } from '../stores/cards.js';
import { useDecksStore } from '../stores/decks.js';

const cardsStore = useCardsStore();
const decksStore = useDecksStore();
const router = useRouter();

const props = defineProps(['id']);

const showLearnDialog = ref(false);

decksStore.fetchById(props.id);
cardsStore.fetchByDeck({ id: props.id });

const deck = computed(() => decksStore.byId(props.id));
const cards = computed(() => cardsStore.byDeck({ id: props.id }));
const folder = computed(() => deck.value?.folder.data ?? null);

const onAdjustLearn = (options) => {
    showLearnDialog.value = true;
};

const onLearn = (options) => {
    console.debug('onLearn', options);
    showLearnDialog.value = false;
    router.push({ name: 'study', params: { id: deck.value.id } });
};
</script>

<template>
    <div v-if="decksStore.isLoading"></div>
    <div v-else>
        <div class="tw-mb-6 tw-flex tw-flex-row tw-items-center">
            <div class="tw-grow">
                <div v-if="folder">
                    <RouterLink
                        :to="{ name: 'folder', params: { id: folder.id } }"
                        class="tw-flex tw-items-center tw-gap-2"
                    >
                        <StudipIcon shape="folder-empty" role="info" />
                        {{ folder.name }}
                    </RouterLink>
                </div>
                <div v-else>
                    <RouterLink to="/" class="tw-flex tw-items-center tw-gap-2 tw-italic">
                        <StudipIcon shape="folder-empty" role="info" />
                        {{ $gettext('Kein Ordner') }}
                    </RouterLink>
                </div>
                <div class="tw-mt-3 tw-font-bold tw-text-lg">{{ deck.name }}</div>
                <div>
                    <Button icon="edit" type="button" class="!tw-m-0 !tw-border-0">{{
                        $gettext('Bearbeiten')
                    }}</Button>
                </div>
            </div>
            <div>
                <Button icon="refresh" type="button" @click="onAdjustLearn">
                    {{ $gettext('Lernen') }}
                </Button>
            </div>
        </div>

        <MessageBox v-if="!cards.length" class="!tw-mb-4">{{
            $gettext('Dein neuer Kartensatz ist bereit!')
        }}</MessageBox>

        <TabGroup as="div" class="cw-tabs" :default-index="0">
            <TabList class="cw-tabs-nav">
                <Tab as="template" v-slot="{ selected }">
                    <button :class="{ 'is-active': selected }">
                        {{ $gettext('Info') }}
                    </button>
                </Tab>
                <Tab as="template" v-slot="{ selected }">
                    <button :class="{ 'is-active': selected }">
                        {{ $gettext('Karten') }}
                    </button>
                </Tab>
                <Tab as="template" v-slot="{ selected }">
                    <button :class="{ 'is-active': selected }">
                        {{ $gettext('Statistiken') }}
                    </button>
                </Tab>
                <Tab as="template" v-slot="{ selected }">
                    <button :class="{ 'is-active': selected }">
                        {{ $gettext('Einstellungen') }}
                    </button>
                </Tab>
            </TabList>
            <TabPanels class="cw-tabs-content">
                <TabPanel>
                    <DeckInfoPanel :deck="deck" />
                </TabPanel>
                <TabPanel>
                    <DeckCardsPanel :deck="deck" />
                </TabPanel>
                <TabPanel>Content 3 (Statistiken)</TabPanel>
                <TabPanel>Content 4 (Einstellungen)</TabPanel>
            </TabPanels>
        </TabGroup>
    </div>
    <DialogAdjustLearningOptions v-model:open="showLearnDialog" :deck="deck" @confirm="onLearn" />
</template>
