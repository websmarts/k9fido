
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
		.webpack('call_planner.js')
		.webpack('stockadjuster.js')
		.webpack('clientprices.js')
		.webpack('freightcalc.js')
		.scripts(['app.js','SimpleAjaxUploader.js'])
		.copy('resources/assets/js/imageuploader.js', 'public/js/imageuploader.js')
		.copy('resources/assets/dist','public/dist')
		.version(['css/app.css','js/all.js','js/main.js','js/call_planner.js','js/orderpicker.js','js/stockadjuster.js','js/clientprices.js','js/freightcalc.js']);
});

