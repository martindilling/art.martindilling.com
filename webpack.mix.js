let mix = require('laravel-mix');
require('laravel-mix-purgecss');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .options({
        processCssUrls: false,
        postCss: [
            require('tailwindcss')(/* './path/to/tailwind.js' */),
        ],
    })
    .purgeCss();

mix.copyDirectory('resources/assets/images', 'public/i');

if (mix.inProduction()) {
    mix.version();
}
