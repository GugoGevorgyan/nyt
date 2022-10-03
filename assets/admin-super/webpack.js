/** @format */

const mix = require("laravel-mix");

mix.js("assets/admin-super/app.js", "public/admin-super/js/app.js")
    .vue({ version: 2 })
    .sass("assets/admin-super/sass/app.scss", "public/admin-super/css/app.css")
    .webpackConfig({
        output: {
            chunkFilename: "admin-super/chunk/[name].js",
        },
        module: {
            rules: [
                {
                    test: /\.html$/i,
                    loader: "html-loader",
                    options: {
                        attributes: [":data"],
                    },
                },
            ],
        },
    })
    .options({
        processCssUrls: true,
        imgLoaderOptions: {
            enabled: false,
        },
    })
    .version()
    .sourceMaps();
