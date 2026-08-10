import { createPinia } from 'pinia';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';
import { createApp } from 'vue';
import { createGettext } from 'vue3-gettext';
import App from './App.vue';
import router from './router';
import translations from './locales/translations.json';
import CKEditor from '@ckeditor/ckeditor5-vue';
import { loadWysiwyg } from './wysiwyg.js';
import './assets/main.css';

const el = document.getElementById('lernkarten-app');

if (el) {
    STUDIP.loadChunk('courseware', { silent: true }).catch(() => {});

    const initialState = {
        userId: el.dataset.userId || null,
        courseId: el.dataset.courseId || null,
        apiBase: el.dataset.apiBase || 'system',
        isTeacher: el.dataset.isTeacher || false,
    };

    const app = createApp(App);

    app.provide('initialState', initialState);

    const pinia = createPinia();
    pinia.use(piniaPluginPersistedstate);

    app.use(pinia);
    
    app.use(router);

    app.use(createGettext({ translations, silent: true }));
    app.use(CKEditor);

    loadWysiwyg()
        .then((wysiwyg) => app.use(wysiwyg))
        .then(() => app.mount('#lernkarten-app'));

}