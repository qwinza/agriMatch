import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

//** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: "#DAD7CD", // warna hijau khas AgriMatch
        secondary: "#95d5b2",
      },
    },
  },
  plugins: [],
}
