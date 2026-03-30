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
            // 1. Les polices du template
            fontFamily: {
                sans: ['DM Sans', ...defaultTheme.fontFamily.sans],
                serif: ['Playfair Display', 'Georgia', 'serif'],
            },

            // 2. Les couleurs
            colors: {
                // Couleurs du Front (Directement à la racine de colors)
                ink: '#0e0c0a',
                parchment: '#f5f0e8',
                cream: '#faf7f2',
                amber: '#c8883a',
                amber2: '#e6a24e',
                rust: '#9c3d2e',
                sage: '#4a6741',
                slate: '#2d3748',

                // Couleurs de l'Admin
                admin: {
                    ink: '#0e0c0a',
                    parchment: '#f5f0e8',
                    cream: '#faf7f2',
                    amber: '#c8883a',
                    amber2: '#e6a24e',
                    rust: '#9c3d2e',
                    sage: '#4a6741',
                    slate: '#2d3748',
                }
            },

            // 3. Les animations
            animation: {
                'fade-up': 'fadeUp 0.6s ease forwards',
                'shimmer': 'shimmer 2s infinite',
            },
            keyframes: {
                fadeUp: {
                    from: { opacity: '0', transform: 'translateY(24px)' },
                    to: { opacity: '1', transform: 'translateY(0)' },
                },
                shimmer: {
                    '0%': { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
            },
        },
    },

    plugins: [forms],
};
