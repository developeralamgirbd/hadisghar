const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 */

/*
 |--------------------------------------------------------------------------
 | Default Mix
 |--------------------------------------------------------------------------
 mix.js('resources/js/app.js', 'public/js').vue()
     .postCss('resources/css/app.css', 'public/css', [
         require('postcss-import'),
       require('tailwindcss'),
    ]).alias({
    '@': 'resources/js',
})
*/

mix.js("resources/js/app.js", "public/js")
    .postCss("resources/css/app.css", "public/css", [
        require('postcss-import'),
        tailwindcss("tailwind-public.config.js")
    ])
    .postCss("resources/css/admin.css", "public/css", [
        require('postcss-import'),
        tailwindcss("tailwind-admin.config.js")
    ]).vue().alias({
    '@': 'resources/js',
});

if (mix.inProduction()) {
    mix.version();
}
