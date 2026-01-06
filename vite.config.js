import { defineConfig, loadEnv } from 'vite'; // <--- Ajoutez loadEnv ici
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(({ mode }) => {
    // Charge les variables d'environnement basées sur le mode (production/development)
    const env = loadEnv(mode, process.cwd(), '');

    return {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
            tailwindcss(),
        ],
        server: {
            watch: {
                ignored: ['**/storage/framework/views/**'],
            },
        },
        define: {
            // Ne passez que les variables spécifiques pour éviter d'exposer tout process.env
            'process.env.VITE_REVERB_APP_KEY': JSON.stringify(env.VITE_REVERB_APP_KEY),
            'process.env.VITE_REVERB_HOST': JSON.stringify(env.VITE_REVERB_HOST),
            'process.env.VITE_REVERB_PORT': JSON.stringify(env.VITE_REVERB_PORT),
            'process.env.VITE_REVERB_SCHEME': JSON.stringify(env.VITE_REVERB_SCHEME),
        },
    };
});
