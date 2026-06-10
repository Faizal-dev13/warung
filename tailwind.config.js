import forms from '@tailwindcss/forms';

export default {
    darkMode: 'class',
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './app/Http/Controllers/**/*.php',
    ],
    safelist: [
        'shadow-soft',
        'line-clamp-1', 'line-clamp-2', 'line-clamp-3',
        'from-slate-950', 'to-blue-950', 'via-blue-950', 'to-slate-900',
        'from-slate-900', 'to-teal-900', 'from-slate-200', 'to-slate-300',
        'from-emerald-500', 'to-teal-600', 'from-blue-500', 'to-indigo-600',
        'from-violet-500', 'to-fuchsia-600', 'from-amber-400', 'to-orange-500',
        {
            pattern: /(bg|text|border|ring|from|via|to)-(slate|teal|emerald|blue|indigo|violet|fuchsia|amber|orange|rose|white|black)-(50|100|200|300|400|500|600|700|800|900|950)/,
            variants: ['dark', 'hover', 'focus', 'group-hover'],
        },
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'],
            },
            boxShadow: {
                soft: '0 24px 80px rgba(15, 23, 42, .10)',
            },
        },
    },
    plugins: [forms],
};
