import { createRouter, createWebHistory } from 'vue-router';
import DashboardView from '../views/DashboardView.vue';
import DecksCreateView from '../views/DecksCreateView.vue';
import DeckView from '../views/DeckView.vue';
import FolderView from '../views/FolderView.vue';
import FoldersView from '../views/FoldersView.vue';
import SearchView from '../views/SearchView.vue';
import StudyView from '../views/StudyView.vue';

const absoluteUriStudip = new URL(window.STUDIP.ABSOLUTE_URI_STUDIP);
const cid = window.STUDIP.URLHelper.parameters.cid;
const baseUrl = `${absoluteUriStudip.pathname}plugins.php/lernkartenplugin/`;

const router = createRouter({
    history: createWebHistory(baseUrl),
    routes: [
        {
            path: '/',
            name: 'home',
            component: DashboardView,
        },
        {
            path: '/search',
            name: 'search',
            component: SearchView,
            props: (route) => ({ query: route.query.q }),
        },
        {
            path: '/folders',
            name: 'folders',
            component: FoldersView,
        },
        {
            path: '/folders/:id',
            name: 'folder',
            component: FolderView,
            props: true,
        },
        {
            path: '/decks/:id',
            name: 'deck',
            component: DeckView,
            props: true,
        },
        {
            path: '/decks/create',
            name: 'decks-create',
            component: DecksCreateView,
            props: (route) => ({ folder: route.query.f }),
        },
        {
            path: '/study/:id',
            name: 'study',
            component: StudyView,
            props: true,
        },
    ],
});

router.beforeEach((to, from, next) => {
    if (cid && !('cid' in to?.query)) {
        next({ ...to, query: { ...to.query, cid } });
    } else {
        next();
    }
});

export default router;
