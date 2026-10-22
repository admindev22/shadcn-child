/** @type {import('tailwindcss').Config} */
module.exports = {
  content: {
    relative: true,
    files: [
      './**/*.php',
      './**/*.html',
      './src/**/*.{css,js}',
      './assets/**/*.{js,ts,jsx,tsx}',
      './theme.json',
      '!./node_modules/**',
      '!./dist/**',
    ],
  },
  theme: {
    extend: {
      colors: {
        background: 'var(--background)',
        foreground: 'var(--foreground)',
        primary: 'var(--primary)',
        accent: 'var(--accent)',
        'accent-foreground': 'var(--accent-foreground)',
      },
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
