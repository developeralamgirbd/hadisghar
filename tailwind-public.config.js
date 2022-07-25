const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    mode:'jit',
    content: [
        './resources/views/**/*.blade.php',
    ],
    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
