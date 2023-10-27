import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import router from './router';
import './assets/main.css';

const mountLernkarten = (el, data) => {
    const app = createApp(App);

    app.use(createPinia());
    app.use(router);

    app.mount(el);
};

if (window.STUDIP) {
    window.STUDIP.mountLernkarten = mountLernkarten;
}
