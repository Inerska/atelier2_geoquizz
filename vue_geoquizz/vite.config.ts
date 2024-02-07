import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [
        vue(),
    ],
    server: {
        host: true,
        port: 80,
        watch: {
            usePolling: true
        },
        proxy: {
            '/service_geoquizz': 'http://service_geoquizz',
            '/service_series': 'http://series_directus',
        }
    },
    resolve: {
        alias: {
          '@': fileURLToPath(new URL('./src', import.meta.url))
        }
    },
    build: {
        outDir: 'dist',
        assetsDir: 'assets',
        copyPublicDir: true,
        emptyOutDir: true,
        sourcemap: true,
        target: 'modules',
        manifest: true,
    }
});
