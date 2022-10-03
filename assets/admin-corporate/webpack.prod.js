/** @format */

const mix = require("laravel-mix");
const CompressionPlugin = require("compression-webpack-plugin");
const WebpackObfuscator = require('webpack-obfuscator');

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
        plugins: [
            new WebpackObfuscator(
                {
                    rotateStringArray: true,
                    rotateUnicodeArray: true
                },
            ),
            new CompressionPlugin({
                algorithm: "gzip",
                test: /\.js$|\.css$|\.html$|\.jp2$|\.jpg$|\.jpeg$|\.svg$|\.png$|\.webp$|\.mp3$|\.ico$/,
                threshold: 10240,
                minRatio: 1,
                compressionOptions: {
                    level: 7,
                },
            }),
        ],
    })
    .options({
        processCssUrls: true,
        purifyCss: false,
        extractStyles: false,
        imgLoaderOptions: {
            enabled: false,
        },
        uglify: {
            uglifyOptions: {
                compress: {
                    drop_debugger: true,
                    sequences: true,
                    booleans: true,
                    loops: true,
                    unused: true,
                    warnings: false,
                    errors: false,
                    drop_console: true,
                    unsafe: true,
                },
            },
        },
    })
    .version();
