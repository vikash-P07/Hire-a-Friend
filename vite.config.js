import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

/**
 * Vite configuration for the Hire-a-Friend companion booking platform.
 *
 * Stack:
 *   - Laravel 12 (PHP 8.3+)
 *   - Bootstrap 5 (loaded via CDN in layouts/app.blade.php)
 *   - Custom CSS in resources/css/app.css
 *   - Custom JS  in resources/js/app.js
 *
 * Note: TailwindCSS has been removed — this project uses Bootstrap 5 + custom CSS.
 */
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
