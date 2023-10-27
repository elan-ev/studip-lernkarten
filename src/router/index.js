import { createRouter, createWebHistory } from 'vue-router';
import DashboardView from '../views/DashboardView.vue';

const absoluteUriStudip = new URL(window.STUDIP.ABSOLUTE_URI_STUDIP);
const router = createRouter({
    history: createWebHistory(`${absoluteUriStudip.pathname}plugins.php/lernkartenplugin/`),
    routes: [
        {
            path: '/',
            name: 'home',
            component: DashboardView
        }
    ]
});

router.beforeEach((to, from, next) => {
    if ('cid' in to?.query) {
        next();
    } else {
        next({ ...to, query: { ...to.query, cid: window.STUDIP.URLHelper.parameters.cid } });
    }
});

export default router;
