import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['"Alexandria"', 'sans-serif'],
        lalezar: ['"Harmattan"', 'sans-serif'], // الخط الجديد
      },
      /* ===== هنا أضف خريطة الألوان المرتبطة بمتغيرات CSS ===== */
      colors: {
  bg: 'var(--color-bg)',
  surface: 'var(--color-surface)',
  text: 'var(--color-text)',
  muted: 'var(--color-muted)',
  primary: 'var(--color-primary)',
  'primary-strong': 'var(--color-primary-strong)',
  accent: 'var(--color-accent)',
  ring1: 'var(--ring-1)',
  ring2: 'var(--ring-2)',
  ringglow: 'var(--ring-glow)'
}

      /* ====================================================== */
    },
  },

  plugins: [forms],
};
