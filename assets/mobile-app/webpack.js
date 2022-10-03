/** @format */

const mix = require("laravel-mix");

mix.js("assets/mobile-app/app.js", "public/app-mobile/js")
    .vue({ version: 2 })
    .sass("assets/mobile-app/styles/app.scss", "public/app-mobile/css")
    .webpackConfig({
        output: {
            chunkFilename: "app-mobile/chunk/[name].js",
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
                {
                    test: /\.sass$/,
                    use: [
                        "vue-style-loader",
                        "css-loader",
                        {
                            loader: "sass-loader",
                            options: {
                                indentedSyntax: true,
                                sassOptions: {
                                    indentedSyntax: true,
                                },
                            },
                        },
                    ],
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
