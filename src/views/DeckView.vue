<script setup>
import { computed, ref } from 'vue';
import { RouterLink } from 'vue-router';
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue';
import IconButton from '../components/IconButton.vue';
import DeckCardsPanel from '../components/DeckCardsPanel.vue';
import DeckInfoPanel from '../components/DeckInfoPanel.vue';
import DeckSettingsPanel from '../components/DeckSettingsPanel.vue';
import DeckStatisticsPanel from '../components/DeckStatisticsPanel.vue';
import DialogAdjustLearningOptions from '../components/DialogAdjustLearningOptions.vue';
import DialogEditDeck from '../components/DialogEditDeck.vue';
import DialogShareDeck from '../components/DialogShareDeck.vue';
import StudipIcon from '../components/base/StudipIcon.vue';
import StudipProgressIndicator from '../components/base/StudipProgressIndicator.vue';
import { useCardsStore } from '../stores/cards.js';
import { useDecksStore } from '../stores/decks.js';

const cardsStore = useCardsStore();
const decksStore = useDecksStore();

const props = defineProps(['id']);

const showEditDialog = ref(false);
const showAdjustLearningDialog = ref(false);
const showShareDialog = ref(false);

decksStore.fetchById(props.id);
cardsStore.fetchByDeck({ id: props.id });

const deck = computed(() => decksStore.byId(props.id));
const cards = computed(() => cardsStore.byDeck({ id: props.id }));
const folder = computed(() => deck.value?.folder.data ?? null);

const onAdjustLearn = () => (showAdjustLearningDialog.value = true);
const onShowEditDialog = () => (showEditDialog.value = true);
const onShowShareDialog = () => (showShareDialog.value = true);
</script>

<template>
    <div v-if="decksStore.isLoading">
        <StudipProgressIndicator :description="$gettext('Lade Kartensatz…')" />
    </div>
    <div v-else>
        <div class="tw-mb-6 tw-flex tw-flex-row tw-items-center">
            <div class="tw-grow">
                <div v-if="folder" :title="$gettext('Zurück zum Ordner')">
                    <RouterLink
                        :to="{ name: 'folder', params: { id: folder.id } }"
                        class="tw-flex tw-items-center tw-gap-2"
                    >
                    <StudipIcon
                        shape="arr_1left"
                        :height="30"
                        :width="30"
                        class="tw-align-middle tw-mb-1"
                    />
                    <StudipIcon
                        shape="folder-empty"
                        :height="30"
                        :width="30"
                        class="tw-align-middle tw-mb-1"
                    />
                    <span class="breadcrumb">
                        {{ folder.name }}
                    </span>
                    </RouterLink>
                </div>
                <div v-else :title="$gettext('Zurück zur Ordnerübersicht')">
                    <RouterLink to="/" class="tw-flex tw-items-center tw-gap-2 tw-italic">
                        <StudipIcon
                            shape="arr_1left"
                            :height="30"
                            :width="30"
                            class="tw-align-middle tw-mb-1"
                        />
                        <StudipIcon
                            shape="folder-home-empty"
                            :height="30"
                            :width="30"
                            class="tw-align-middle tw-mb-1"
                        />
                        <span class="breadcrumb">
                            {{ $gettext('Kein Ordner') }}
                        </span>
                    </RouterLink>
                </div>
                <div class="tw-mt-3 tw-font-bold tw-text-lg">{{ deck.name }}</div>
            </div>
            <div>
                <IconButton icon="refresh" type="button" @click="onAdjustLearn">
                    {{ $gettext('Lernen') }}
                </IconButton>
                <IconButton icon="share" type="button" @click="onShowShareDialog">
                    {{ $gettext('Teilen') }}
                </IconButton>
                <IconButton icon="edit" type="button" @click="onShowEditDialog">
                    {{ $gettext('Bearbeiten') }}
                </IconButton>
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
    <DialogAdjustLearningOptions v-model:open="showAdjustLearningDialog" :decks="[deck]" />
    <DialogEditDeck v-model:open="showEditDialog" :deck="deck" />
    <DialogShareDeck v-if="showShareDialog" v-model:open="showShareDialog" :deck="deck" />
</template>

<style scoped>
span.breadcrumb {
    color: var(--headings-color);
    font-size: 1.4em;
    text-align: left;
}
</style>