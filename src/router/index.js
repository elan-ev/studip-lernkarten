import { createRouter, createWebHistory } from 'vue-router';
import DashboardView from '../views/DashboardView.vue';
import FoldersView from '../views/FoldersView.vue';

const absoluteUriStudip = new URL(window.STUDIP.ABSOLUTE_URI_STUDIP);
const cid = window.STUDIP.URLHelper.parameters.cid;
const baseUrl = `${absoluteUriStudip.pathname}plugins.php/lernkartenplugin/`;

const router = createRouter({
    history: createWebHistory(baseUrl),
    routes: [
        {
            path: '/',
            name: 'home',
            component: DashboardView
        },
        {
            path: '/folders',
            name: 'folders',
            component: FoldersView
        }
    ]
});

router.beforeEach((to, from, next) => {
    if (cid && !('cid' in to?.query)) {
        next({ ...to, query: { ...to.query, cid } });
    } else {
        next();
    }
});

export default router;
