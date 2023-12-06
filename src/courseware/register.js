import { createPinia } from 'pinia';
import { createApp, defineCustomElement, getCurrentInstance, h } from 'vue';
import { createGettext } from 'vue3-gettext';
import translations from '../locales/translations.json';
import LernkartenBlock from './LernkartenBlock.ce.vue';
import LernkartenDeckSelector from './LernkartenDeckSelector.ce.vue';

const plugins = [createGettext({ translations })];

const LernkartenBlockElement = createElementInstance({
    component: LernkartenBlock,
    props: ['deck'],
    plugins,
});
customElements.define('lernkarten-block', LernkartenBlockElement);

const LernkartenDeckSelectorElement = createElementInstance({
    component: LernkartenDeckSelector,
    props: ['deck'],
    plugins,
});
customElements.define('lernkarten-deck-selector', LernkartenDeckSelectorElement);

function createElementInstance({
    component = null,
    props = [],
    plugins = [],
    renderOptions = {},
} = {}) {
    return defineCustomElement({
        props,
        setup() {
            const app = createApp();
            const pinia = createPinia();
            app.use(pinia);
            plugins.forEach((plugin) => app.use(plugin));

            const inst = getCurrentInstance();
            Object.assign(inst.appContext, app._context);
            Object.assign(inst.provides, app._context.provides);
        },
        render: () => h(component, renderOptions),
        styles: [`@import url('` + window.STUDIP.URLHelper.getURL('assets/stylesheets/studip-base.css') + `')`]
    });
}
