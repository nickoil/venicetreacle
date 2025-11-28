import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js', 
                'resources/css/table-handler.css',
                'resources/js/table-handler.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        https: {
            key: fs.readFileSync('docker/certs/hq.venicetreacle.dv.key'),
            cert: fs.readFileSync('docker/certs/hq.venicetreacle.dv.crt'),
        },
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            host: 'hq.venicetreacle.dv',
            protocol: 'wss',
        },
        watch: {
            usePolling: true,
        },
    },
});
