/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './resources/js/**/*.vue',
  ],
  safelist: [
    // Blue color classes
    { pattern: /^bg-blue-(100|900)$/ },
    { pattern: /^text-blue-(600|400)$/ },
    { pattern: /^dark:bg-blue-(900|400)$/ },
    { pattern: /^dark:text-blue-(400)$/ },
    // Amber color classes
    { pattern: /^bg-amber-(100|900)$/ },
    { pattern: /^text-amber-(600|400)$/ },
    { pattern: /^dark:bg-amber-(900|400)$/ },
    { pattern: /^dark:text-amber-(400)$/ },
    // Green color classes
    { pattern: /^bg-green-(100|900)$/ },
    { pattern: /^text-green-(600|400)$/ },
    { pattern: /^dark:bg-green-(900|400)$/ },
    { pattern: /^dark:text-green-(400)$/ },
    // Indigo color classes
    { pattern: /^bg-indigo-(100|900)$/ },
    { pattern: /^text-indigo-(600|400)$/ },
    { pattern: /^dark:bg-indigo-(900|400)$/ },
    { pattern: /^dark:text-indigo-(400)$/ },
    // Orange color classes
    { pattern: /^bg-orange-(100|900)$/ },
    { pattern: /^text-orange-(600|400)$/ },
    { pattern: /^dark:bg-orange-(900|400)$/ },
    { pattern: /^dark:text-orange-(400)$/ },
    // Red color classes
    { pattern: /^bg-red-(100|900)$/ },
    { pattern: /^text-red-(600|400)$/ },
    { pattern: /^dark:bg-red-(900|400)$/ },
    { pattern: /^dark:text-red-(400)$/ },
    // Purple color classes
    { pattern: /^bg-purple-(100|900)$/ },
    { pattern: /^text-purple-(600|400)$/ },
    { pattern: /^dark:bg-purple-(900|400)$/ },
    { pattern: /^dark:text-purple-(400)$/ },
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#f0f9ff',
          100: '#e0f2fe',
          500: '#0ea5e9',
          600: '#0284c7',
          700: '#0369a1',
          900: '#082f49',
        },
        secondary: {
          700: '#2c3e50',
          800: '#1a252f',
        },
      },
      fontFamily: {
        sans: ['Segoe UI', 'Tahoma', 'Geneva', 'Verdana', 'sans-serif'],
      },
      boxShadow: {
        sm: '0 2px 4px rgba(0,0,0,0.1)',
        md: '0 2px 8px rgba(0,0,0,0.1)',
      },
      backgroundColor: {
        light: '#ffffff',
        'light-secondary': '#f9fafb',
      },
    },
  },
  plugins: [],
}
