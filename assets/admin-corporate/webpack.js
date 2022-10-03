/** @format */

const mix = require("laravel-mix");
const CompressionPlugin = require("compression-webpack-plugin");

// mix.browserSync('https://nyt.loc/admin/corporate/*')
mix.js("assets/admin-corporate/app.js", "public/admin-corporate/js/app.js")
    .vue({ version: 2 })
    .sass("assets/admin-corporate/sass/app.scss", "public/admin-corporate/css/app.css")
    .webpackConfig({
        output: {
            chunkFilename: "admin-corporate/chunk/[name].js",
        },
        module: {
            rules: [
                {
                    test: /\.html$/i,
                    loader: "html-loader",
                    options: {
                        attributes: false,
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
                {
                    test: /\.html$/,
                    loader: "vue-template-loader",
                    exclude: /index.html/,
                    options: {
                        transformToRequire: {
                            img: 'src'
                        }
                    }
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
    .sourceMaps();
