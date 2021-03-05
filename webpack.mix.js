const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

 mix.sass('resources/sass/app.scss', 'public/css')
     .js('resources/js/app.js', 'public/js/app.js');

 mix.copy('node_modules/echarts/dist/echarts-en.min.js', 'public/js/echarts-en.min.js')
     .copy('node_modules/echarts/dist/echarts-en.common.min.js', 'public/js/echarts-en.common.min.js')
     .copy('node_modules/echarts/dist/echarts.min.js', 'public/js/echarts.min.js');
