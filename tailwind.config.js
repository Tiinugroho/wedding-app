import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            // TAMBAHKAN WARNA CUSTOM ANDA DI SINI
            colors: {
                rRed: '#FF5A5A',
                rOrange: '#FF8B5A',
                rLightOrange: '#FFA95A',
                rYellow: '#FFD45A',
            }
        },
    },

    plugins: [forms],
};