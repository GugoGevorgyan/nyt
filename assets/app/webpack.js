/** @format */

const mix = require('laravel-mix');

mix.js('assets/app/app.js', 'public/app/js/app.js').
    sass('assets/app/sass/app.scss', 'public/app/css/app.css').
    vue({ version: 2 }).
    webpackConfig({
        output: {
            chunkFilename: 'app/chunk/[name].js',
        },
        module: {
            rules: [
                {
                    test: /\.html$/i,
                    loader: 'html-loader',
                    options: {
                        attributes: [':data'],
                    },
                },
            ],
        },
    }).
    options({
        processCssUrls: true,
        imgLoaderOptions: {
            enabled: false,
        },
    }).
    version().
    sourceMaps();
