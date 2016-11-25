const elixir = require('laravel-elixir');
require('laravel-elixir-vue-2');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(mix => {
    mix.sass('main.scss');

    mix.styles([
        'resources/assets/vendor/animate.css',
        'public/css/main.css'
    ], 'public/css/app.css', './');

    mix.webpack('app.js');
    mix.webpack('dashboard.js');
    mix.webpack('details.js');
});
