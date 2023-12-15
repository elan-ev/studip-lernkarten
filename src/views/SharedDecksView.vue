<script setup>
import { computed } from 'vue';
import SharedDeckList from '../components/SharedDeckList.vue';
import StudipCompanion from '../components/base/StudipCompanion.vue';
import StudipProgressIndicator from '../components/base/StudipProgressIndicator.vue';
import { useContextStore } from '../stores/context.js';
import { useCourseMembershipsStore } from '../stores/course-memberships.js';
import { useDecksStore } from '../stores/decks.js';
import { useSharedDecksStore } from '../stores/shared-decks.js';

const contextStore = useContextStore();
const courseMembershipsStore = useCourseMembershipsStore();
const decksStore = useDecksStore();
const sharedDecksStore = useSharedDecksStore();

courseMembershipsStore.fetchContext();
decksStore.fetchContext();
sharedDecksStore.fetchContext();

const isAtLeastTutor = computed(() =>
    ['tutor', 'dozent'].includes(courseMembershipsStore.byContext()?.permission)
);

const sharedByMe = computed(() =>
    sharedDecksStore.all.filter((sharedDeck) => sharedDeck.sharer.data.id === contextStore.userId)
);

const sharedWithMe = computed(() =>
    sharedDecksStore.all.filter((sharedDeck) => sharedDeck.sharer.data.id !== contextStore.userId)
);

const doneLoading = computed(
    () => !courseMembershipsStore.isLoading && !decksStore.isLoading && !sharedDecksStore.isLoading
);

const workingPlaceUrl = computed(() =>
    window.STUDIP.URLHelper.getURL('plugins.php/lernkartenplugin/search', {}, true)
);
</script>

<template>
    <main>
        <StudipProgressIndicator
            v-if="!doneLoading"
            :description="$gettext('Lade Veranstaltungsdaten …')"
        />
        <template v-if="doneLoading">
            <article class="studip" v-if="sharedWithMe.length">
                <header>
                    <h1>{{ $gettext('Mit mir geteilte Kartensätze') }}</h1>
                </header>
                <SharedDeckList :shared-decks="sharedWithMe" />
            </article>
            <article class="studip" v-if="isAtLeastTutor">
                <header>
                    <h1>{{ $gettext('Von mir geteilte Kartensätze') }}</h1>
                </header>
                <SharedDeckList v-if="sharedByMe.length" :shared-decks="sharedByMe" />
                <StudipCompanion
                    v-else
                    :msg-companion="
                        $gettext(
                            'Sie haben noch keinen Kartensatz mit dieser Veranstaltung geteilt.'
                        )
                    "
                    mood="sad"
                >
                    <template #companionActions>
                        <a :href="workingPlaceUrl" class="button add">
                            {{ $gettext('Kartensatz teilen') }}
                        </a>
                    </template>
                </StudipCompanion>
            </article>
        </template>
    </main>
</template>
