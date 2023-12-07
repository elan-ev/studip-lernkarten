import { defineCustomElement } from 'vue';
import LernkartenBlock from './LernkartenBlock.ce.vue';

console.log(LernkartenBlock.styles); // ["/* inlined css */"]

const LernkartenBlockElement = defineCustomElement(LernkartenBlock);
customElements.define('lernkarten-block', LernkartenBlockElement);
