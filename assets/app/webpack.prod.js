/** @format */

const mix = require("laravel-mix");
const CompressionPlugin = require("compression-webpack-plugin");
const WebpackObfuscator = require("webpack-obfuscator");

mix.vue({ version: 2 })
    .js("assets/app/app.js", "public/app/js/app.js")
    .sass("assets/app/sass/app.scss", "public/app/css/app.css")
    .webpackConfig({
        output: {
            chunkFilename: "app/chunk/[name].js",
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
        plugins: [
            new WebpackObfuscator({
                rotateStringArray: true,
                rotateUnicodeArray: true,
            }),
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
                    drop_console: true,
                    unsafe: true,
                },
            },
        },
    })
    .version();
