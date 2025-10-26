import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import colors from 'tailwindcss/colors';
import wireuiPreset from './vendor/wireui/wireui/tailwind.config.js';

/** @type {import('tailwindcss').Config} */
export default {
    presets: [wireuiPreset],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './vendor/wireui/wireui/src/*.php',
        './vendor/wireui/wireui/ts/**/*.ts',
        './vendor/wireui/wireui/src/WireUi/**/*.php',
        './vendor/wireui/wireui/src/Components/**/*.php',
        './vendor/rappasoft/laravel-livewire-tables/resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: colors.blue,
                secondary: colors.indigo,
                success: colors.emerald,
                warning: colors.amber,
                danger: colors.rose,
                neutral: colors.slate,
            },
            borderRadius: {
                'xl': '0.9rem',
            },
            boxShadow: {
                'soft': '0 1px 2px rgba(16, 24, 40, 0.05), 0 1px 3px rgba(16, 24, 40, 0.08)',
            },
        },
    },

    plugins: [forms, typography],
};
