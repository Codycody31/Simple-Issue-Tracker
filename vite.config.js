import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
            pwa: {
                name: 'Issue Tracker',
                themeColor: '#4DBA87',
                msTileColor: '#000000',
                appleMobileWebAppCapable: 'yes',
                appleMobileWebAppStatusBarStyle: 'black',

                // configure the workbox plugin
                workboxPluginMode: 'GenerateSW',
            }
        }),
    ],
});
