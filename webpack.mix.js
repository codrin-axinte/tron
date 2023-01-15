let mix = require('laravel-mix');


mix
    .js('resources/js/swagger.js', 'public/js')
    .version();
