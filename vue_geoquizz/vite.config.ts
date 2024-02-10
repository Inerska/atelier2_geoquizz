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
        port: 81,
        watch: {
            usePolling: true
        },
        proxy: {
            '^/gateway': {
                target: 'http://gateway_nginx:80',
                rewrite: path => path.replace(/^\/gateway/, ''),
            },
            '/ws_gateway': {
                target: 'ws://websocket_geoquizz',
                ws: true,
                rewrite: path => path.replace(/^\/ws_gateway/, ''),
            }
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
