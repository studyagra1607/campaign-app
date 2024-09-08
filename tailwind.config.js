import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: [
                    // 'Figtree', ...defaultTheme.fontFamily.sans,
                    'Inter var,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica,Arial,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol',
                    {
                        fontFeatureSettings: '"cv02","cv03","cv04","cv11"',
                    },
                ],
            },
            keyframes: {
                fadeOut: {
                    from: {
                        opacity: 1
                    },
                    to: {
                        opacity: 0
                    }
                }
            },
            animation: {
                "fade-out": 'fadeOut 0.5s',
            }
        },
    },

    plugins: [forms],
    important: true,
};
