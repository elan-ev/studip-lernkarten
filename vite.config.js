import { resolve } from 'node:path';

import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig(({ mode }) => {
    return {
        build: {
            lib: {
                entry: {
                    lernkarten: resolve(import.meta.dirname, 'src/main.js'),
                    register: resolve(import.meta.dirname, 'src/courseware/register.js'),
                },
            },
            sourcemap: mode === 'development' ? 'inline' : false,
        },
        define: { 'process.env.NODE_ENV': `"${mode}"` },
        plugins: [vue()],
        resolve: {
            alias: {
                '@': resolve(import.meta.dirname, 'src'),
            },
        },
    };
});