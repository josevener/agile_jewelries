/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.{php,html,js}', // Scans index.php and scripts.js
    './admin/**/*.{php,html,js}', // Scans all PHP/JS files in admin/ and subfolders (e.g., components/)
    './assets/**/*.{php,html,js}', // Scans assets/
  ],
  safelist: [
    'hidden',
    'flex',
    'opacity-0',
    'opacity-100',
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}