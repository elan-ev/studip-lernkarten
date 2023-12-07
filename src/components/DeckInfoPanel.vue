<script setup>
import { computed } from 'vue';
import Button from './IconButton.vue';
import CourseAvatar from './CourseAvatar.vue';
import StudipAvatar from './base/StudipAvatar.vue';
import StudipDate from '../components/base/StudipDate.vue';
import StudipIcon from '../components/base/StudipIcon.vue';

const props = defineProps(['deck']);

const avatarUrl = computed(() => props.deck.owner.data.meta.avatar.small);
const formattedName = computed(() => props.deck.owner.data['formatted-name']);

const sharedWithCourses = computed(() =>
    props.deck['shared-with'].data.filter(({ type }) => type === 'courses'),
);
const sharedWithUsers = computed(() =>
    props.deck['shared-with'].data.filter(({ type }) => type === 'users'),
);

const courseUrl = (course) =>
    window.STUDIP.URLHelper.getURL(`dispatch.php/course/details/index/${course.id}`);
const userUrl = (user) =>
    window.STUDIP.URLHelper.getURL('dispatch.php/profile', { username: user.username });
</script>

<template>
    <article class="studip">
        <header>
            <h1>{{ $gettext('Allgemeine Informationen') }}</h1>
        </header>
        <section>
            <table>
                <tr>
                    <th>
                        <StudipIcon shape="add" role="info" />
                        {{ $gettext('Erstellt') }}
                    </th>
                    <td>
                        <StudipDate :date="new Date(deck.mkdate)" />
                        {{ $gettext('von') }}
                        <StudipAvatar :avatar-url="avatarUrl" :formatted-name="formattedName" />
                    </td>
                </tr>

                <tr>
                    <th>
                        <StudipIcon shape="dialog-cards" role="info" />
                        {{ $gettext('Anzahl Karten') }}
                    </th>
                    <td>
                        <span>{{ deck.cards.data.length }}</span>
                    </td>
                </tr>
                <tr>
                    <th>
                        <StudipIcon shape="vote" role="info" />
                        {{ $gettext('Gesamtfortschritt') }}
                    </th>
                    <td>
                        <span>0%</span>
                    </td>
                </tr>
            </table>
        </section>
    </article>

    <article class="studip">
        <header>
            <h1>{{ $gettext('Beschreibung') }}</h1>
        </header>
        <section>
            {{ deck.description }}
        </section>
        <footer>
            <Button disabled type="button" icon="edit">{{ $gettext('Bearbeiten') }}</Button>
        </footer>
    </article>

    <article class="studip">
        <header>
            <h1>{{ $gettext('Geteilt mit') }}</h1>
        </header>
        <section class="tw-flex tw-flex-col tw-gap-2">
            <a v-for="course in sharedWithCourses" :key="course.id" :href="courseUrl(course)">
                <CourseAvatar :course="course" />
            </a>
            <a v-for="user in sharedWithUsers" :key="user.id" :href="userUrl(user)">
                <StudipAvatar
                    :avatar-url="user.meta.avatar.small"
                    :formatted-name="user['formatted-name']"
                />
            </a>
        </section>
    </article>
</template>

<style scoped>
th {
    align-items: center;
    display: flex;
    font-weight: normal;
    gap: 0.25rem;
    opacity: 0.6;
    padding-inline-end: 2rem;
}
td {
    font-weight: bold;
}
tr + tr > * {
    padding-block-start: 1rem;
}
</style>
