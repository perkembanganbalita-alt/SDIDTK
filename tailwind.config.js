/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                primary: '#22c55e',
                secondary: '#14b8a6',
                danger: '#ef4444',
                warning: '#f59e0b',
                success: '#10b981',
                surface: '#ffffff',
                background: '#f8fafc',
            },
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
        },
    },
    plugins: [],
};
