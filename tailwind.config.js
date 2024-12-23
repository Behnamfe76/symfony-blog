/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./templates/pages/**/*.html.twig",
  ],
  theme: {
    extend: {},
  },
  plugins: [ require('@tailwindcss/forms'), require('@tailwindcss/aspect-ratio')],
}

