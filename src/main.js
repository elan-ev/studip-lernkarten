import { createPinia } from 'pinia';
import { createApp } from 'vue';
import { createGettext } from 'vue3-gettext';
import App from './App.vue';
import router from './router';
import translations from './locales/translations.json';
import './assets/main.css';

const mountLernkarten = (el, data) => {
    const app = createApp(App);

    app.use(createPinia());
    app.use(router);
    app.use(createGettext({ translations }));

    app.mount(el);
};

if (window.STUDIP) {
    window.STUDIP.mountLernkarten = mountLernkarten;
}
