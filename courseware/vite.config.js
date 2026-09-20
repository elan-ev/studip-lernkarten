import { resolve } from 'node:path';

import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig(({ mode }) => {
    return {
        build: {
            outDir: resolve(import.meta.dirname, 'dist'),
            emptyOutDir: false,
            lib: {
                entry: resolve(import.meta.dirname, 'src/main.js'),
                name: 'lernkarten-courseware',
                formats: ['umd'],
                fileName: () => 'lernkarten-courseware.umd.js',
            },
            rollupOptions: {
                external: ['vue', 'vuex'],
                output: {
                    globals: {
                        vue: 'Vue',
                        vuex: 'Vuex',
                    },
                    assetFileNames: 'lernkarten-courseware.[ext]',
                },
            },
            sourcemap: mode === 'development' ? 'inline' : false,
        },
        define: { 'process.env.NODE_ENV': `"${mode}"` },
        plugins: [vue()],
        resolve: {
            alias: {
                '@': resolve(import.meta.dirname, '../src'),
            },
        },
    };
});
