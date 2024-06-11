import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/js/assets/css/styles.css',
                'resources/js/assets/js/scripts.js',
                'resources/js/assets/js/datatables-simple-demo.js'
            ],
            refresh: true,
        }),
    ],
});
