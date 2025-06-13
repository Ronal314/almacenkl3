import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js', // Asegúrate de que este archivo exista y sea tu punto de entrada
            ],
            refresh: true, // Esto permite recargar el navegador en modo desarrollo cuando hay cambios
        }),
    ],
    build: {
        outDir: 'public/build', // Carpeta de salida para los archivos compilados
        manifest: 'manifest.json', // Nombre explícito del archivo manifest
        emptyOutDir: true, // Limpia la carpeta /public/build/ antes de generar nuevos archivos
        rollupOptions: {
            output: {
                entryFileNames: `[name].[hash].js`,       // Archivos JS directamente en /public/build/
                chunkFileNames: `[name].[hash].js`,      // Chunks JS directamente en /public/build/
                assetFileNames: `[name].[hash].[ext]`,  // Archivos CSS, imágenes, etc., directamente en /public/build/
            },
        },
    },
});