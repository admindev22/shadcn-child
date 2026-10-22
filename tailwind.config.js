/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php',
    './**/*.html',
    './src/**/*.{css,js}',
  ],
  theme: {
    extend: {
      borderRadius: {
        DEFAULT: 'var(--radius)',
      },
    },
  },
  corePlugins: {
    preflight: false,
  },
  plugins: [],
};
