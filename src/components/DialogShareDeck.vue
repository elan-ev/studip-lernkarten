<script setup>
import { computed, ref } from 'vue';
import { useGettext } from 'vue3-gettext';
import Button from './IconButton.vue';
import StudipDialog from './base/StudipDialog.vue';
import StudipIcon from './base/StudipIcon.vue';
import StudipMultiPersonSearch from './base/StudipMultiPersonSearch.vue';
import RangeTypeChooser from './RangeTypeChooser.vue';
import { useCourseMembershipsStore } from '../stores/course-memberships.js';
import { useInstancesStore } from '../stores/instances.js';
import { useSemestersStore } from '../stores/semesters.js';

const { $gettext } = useGettext();
const courseMembershipsStore = useCourseMembershipsStore();
const instancesStore = useInstancesStore();
const semestersStore = useSemestersStore();

const props = defineProps(['open', 'deck']);
const emit = defineEmits(['update:open']);

const initialFocus = ref(null);
const isLoading = ref(true);
const selectedCourse = ref(null);
const selectedUsers = ref(null);
const typeOfSource = ref('');

const reset = () => {
    selectedCourse.value = null;
    selectedUsers.value = null;
    typeOfSource.value = null;
};

Promise.all([courseMembershipsStore.fetch(), instancesStore.fetch(), semestersStore.fetch()]).then(
    () => (isLoading.value = false)
);

const instanceRangeIds = computed(() => {
    return instancesStore.all.map(({ range }) => range.data.id);
});

const courses = computed(() => {
    return courseMembershipsStore.all
        .filter((membership) => ['tutor', 'dozent'].includes(membership.permission))
        .map(({ course }) => course.data)
        .filter((course) => instanceRangeIds.value.includes(course.id));
});

const setIsOpen = (value) => {
    emit('update:open', value);
    reset();
};

const semesterOf = (course) => {
    const semesterId = course['start-semester'].data.id;

    return semestersStore.byId(semesterId);
};

const onSelectUsers = (users) => {
    selectedUsers.value = users;
};

const confirmDisabled = computed(() => {
    return !(
        (typeOfSource.value === 'courses' && selectedCourse.value !== null) ||
        (typeOfSource.value === 'users' && selectedUsers.value?.length)
    );
});

const onConfirm = () => {
    switch (typeOfSource.value) {
        case 'courses':
            console.debug('sharing with course', selectedCourse.value);
            return;
        case 'users':
            console.debug('sharing with users', selectedUsers.value);
            return;
    }
};
</script>

<template>
    <StudipDialog
        :confirm-text="$gettext('Teilen')"
        :close-text="$gettext('Schließen')"
        :confirm-disabled="confirmDisabled"
        :height="600"
        :initial-focus="initialFocus"
        :open="open"
        :title="$gettext('Kartensatz teilen: %{ name }', { name: props.deck?.name })"
        :width="600"
        @confirm="onConfirm"
        @update:open="setIsOpen"
    >
        <template #dialogContent>
            <form v-if="!isLoading" class="default studipform" @submit.prevent="onConfirm">
                <RangeTypeChooser v-model="typeOfSource" />

                <section v-if="typeOfSource === 'courses'">
                    <div class="formpart">
                        <label class="studiprequired">
                            <span class="textlabel">
                                {{ $gettext('Veranstaltung') }}
                            </span>
                            <span
                                class="asterisk"
                                :title="$gettext('Dies ist ein Pflichtfeld')"
                                aria-hidden="true"
                                >*</span
                            >
                            <select v-model="selectedCourse">
                                <option :value="null" disabled>
                                    {{ $gettext('Veranstaltung auswählen') }}
                                </option>

                                <option v-for="course in courses" :key="course.id" :value="course">
                                    {{ course.title }} ({{ semesterOf(course).title }})
                                </option>
                            </select>
                            <pre></pre>
                        </label>
                    </div>
                </section>
            </form>

            <section v-if="typeOfSource === 'users'">
                <StudipMultiPersonSearch name="content-persons" @input="onSelectUsers" />
            </section>

            <div v-if="isLoading">{{ $gettext('Laden…') }}</div>
        </template>
    </StudipDialog>
</template>
