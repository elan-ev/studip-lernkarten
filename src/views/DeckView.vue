<script setup>
import { computed, ref } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue';
import Button from '../components/IconButton.vue';
import DeckCardsPanel from '../components/DeckCardsPanel.vue';
import DeckInfoPanel from '../components/DeckInfoPanel.vue';
import DeckSettingsPanel from '../components/DeckSettingsPanel.vue';
import DeckStatisticsPanel from '../components/DeckStatisticsPanel.vue';
import DialogAdjustLearningOptions from '../components/DialogAdjustLearningOptions.vue';
import DialogShareDeck from '../components/DialogShareDeck.vue';
import StudipIcon from '../components/base/StudipIcon.vue';
import StudipProgressIndicator from '../components/base/StudipProgressIndicator.vue';
import { useCardsStore } from '../stores/cards.js';
import { useDecksStore } from '../stores/decks.js';

const cardsStore = useCardsStore();
const decksStore = useDecksStore();
const router = useRouter();

const props = defineProps(['id']);

const showLearnDialog = ref(false);
const showShareDialog = ref(false);

decksStore.fetchById(props.id);
cardsStore.fetchByDeck({ id: props.id });

const deck = computed(() => decksStore.byId(props.id));
const cards = computed(() => cardsStore.byDeck({ id: props.id }));
const folder = computed(() => deck.value?.folder.data ?? null);

const onAdjustLearn = () => {
    showLearnDialog.value = true;
};

const onLearn = (options) => {
    console.debug('onLearn', options);
    showLearnDialog.value = false;
    router.push({ name: 'study', params: { id: deck.value.id } });
};

const onShowShareDialog = () => {
    showShareDialog.value = true;
};
</script>

<template>
    <div v-if="decksStore.isLoading">
        <StudipProgressIndicator :description="$gettext('Lade Kartensatz…')" />
    </div>
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
                <Button icon="share" type="button" @click="onShowShareDialog">
                    {{ $gettext('Teilen') }}
                </Button>
            </div>
        </div>

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
                <TabPanel>
                    <DeckStatisticsPanel :deck="deck" />
                </TabPanel>
                <TabPanel>
                    <DeckSettingsPanel :deck="deck" />
                </TabPanel>
            </TabPanels>
        </TabGroup>
    </div>
    <DialogAdjustLearningOptions v-model:open="showLearnDialog" :deck="deck" @confirm="onLearn" />
    <DialogShareDeck v-model:open="showShareDialog" :deck="deck" />
</template>
