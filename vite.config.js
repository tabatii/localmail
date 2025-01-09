import { defineConfig } from 'vite';

export default defineConfig({
    build: {
        rollupOptions: {
            input: [
                'resources/css/localmail.css',
            ],
            output: {
                assetFileNames: '[name][extname]',
            },
        },
    },
});
