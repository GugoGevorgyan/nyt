/** @format */

const mix = require('laravel-mix');

mix.js('assets/system-worker/app.js', 'public/admin-workers/js/app.js').
    sass('assets/system-worker/sass/app.scss', 'public/admin-workers/css/app.css').
    vue({ version: 2 }).
    webpackConfig({
        output: {
            chunkFilename: 'admin-workers/chunk/[name].js',
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
                // {
                //     test: /\.s[ac]ss$/i,
                //     use: [
                //         "vue-style-loader",
                //         "css-loader",
                //         "style-loader",
                //         {
                //             loader: "sass-loader",
                //             options: {
                //                 indentedSyntax: true,
                //                 sassOptions: {
                //                     indentedSyntax: true,
                //                 },
                //             },
                //         },
                //     ],
                // },
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
