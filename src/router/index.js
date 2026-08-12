import { createRouter, createWebHashHistory } from 'vue-router';
import SharedDecksInCourseView from '../views/SharedDecksInCourseView.vue';
import SharedDecksInWorkplaceView from '../views/SharedDecksInWorkplaceView.vue';
import DecksCreateView from '../views/DecksCreateView.vue';
import DeckView from '../views/DeckView.vue';
import FolderView from '../views/FolderView.vue';
import FoldersView from '../views/FoldersView.vue';
import SearchView from '../views/SearchView.vue';
import StudyView from '../views/StudyView.vue';

const isCourse = 'cid' in window.STUDIP.URLHelper.parameters;

const router = createRouter({
    // Native Unterstützung für Hash-Routing (#/search, #/decks/123)
    history: createWebHashHistory(),
    routes: [
        {
            path: '/',
            name: 'home',
            component: isCourse ? SharedDecksInCourseView : FoldersView,
        },
        {
            path: '/decks/create',
            name: 'decks-create',
            component: DecksCreateView,
            props: (route) => ({ folder: route.query.f }),
        },
        {
            path: '/decks/:id',
            name: 'deck',
            component: DeckView,
            props: true,
        },
        {
            path: '/folders/:id',
            name: 'folder',
            component: FolderView,
            props: true,
        },
        {
            path: '/search',
            name: 'search',
            component: SearchView,
            props: (route) => ({ query: route.query.q }),
        },
        {
            path: '/shared',
            name: 'shared',
            component: SharedDecksInWorkplaceView,
            props: true,
        },
        {
            path: '/study',
            name: 'study',
            component: StudyView,
            props: (route) => ({
                decks: route?.query?.decks ?? [],
                order: route?.query?.order ?? null,
            }),
        },
    ],
});

export default router;