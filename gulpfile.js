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
    mix.sass('app.scss')
		.webpack('main.js')
		.webpack('orderpicker.js')
		.scripts(['app.js','SimpleAjaxUploader.js'])
		.copy('resources/assets/js/imageuploader.js', 'public/js/imageuploader.js')
		.version(['css/app.css','js/all.js','js/main.js','js/orderpicker.js']);
});

