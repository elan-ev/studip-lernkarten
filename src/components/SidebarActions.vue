<script setup>
import { computed, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useGettext } from 'vue3-gettext';
import { useContextStore } from '../stores/context.js';
import DialogShareDeckHere from './DialogShareDeckHere.vue';
import StudipIcon from './base/StudipIcon.vue';

const contextStore = useContextStore();
const { $gettext } = useGettext();
const router = useRouter();
const route = useRoute();

const showShareDialog = ref(false);

const isTeacher = computed(() => contextStore.isCourse && contextStore.isTeacher);
const isWorkplace = computed(() => !contextStore.isCourse);

const onCreateDeck = () => {
    const f = route.name === 'folder' ? route.params.id : null;
    router.push({ name: 'decks-create', query: { f } });
};

const onShareDeck = () => (showShareDialog.value = true);

const actionList = computed(() => {
    const actions = [];
    if (isWorkplace.value) {
        actions.push({
            icon: 'add',
            text: $gettext('Neuen Kartensatz erstellen'),
            click: onCreateDeck
        });
    }
    if (isTeacher.value) {
        actions.push({
            icon: 'share',
            text: $gettext('Kartensatz hierhin teilen'),
            click: onShareDeck
        });
    }

    return actions;
});
</script>

<template>
    <div v-if="actionList.length" id="sidebar-actions" class="sidebar-widget">
        <div class="sidebar-widget-header">{{ $gettext('Aktionen') }}</div>
        <div class="sidebar-widget-content">
            <form method="post">
                <ul class="widget-list widget-links" :aria-label="$gettext('Aktionen')">
                    <li v-for="action in actionList" class="!tw-pl-0">
                        <button type="button" @click="action.click">
                            <StudipIcon :shape="action.icon" class="!tw-align-middle" />
                            {{ action.text }}
                        </button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <DialogShareDeckHere v-if="showShareDialog" v-model:open="showShareDialog" />
</template>
