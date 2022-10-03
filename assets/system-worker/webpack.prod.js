/** @format */

const mix = require('laravel-mix');
const CompressionPlugin = require('compression-webpack-plugin');
const MomentLocalesPlugin = require('moment-locales-webpack-plugin');
const WebpackObfuscation = require('webpack-obfuscator');

mix.vue({ version: 2 }).
    js('assets/system-worker/app.js', 'public/admin-workers/js/app.js').
    sass('assets/system-worker/sass/app.scss', 'public/admin-workers/css/app.css').
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
            ],
        },
        plugins: [
            new WebpackObfuscation({
                rotateStringArray: true,
                rotateUnicodeArray: true,
            }),
            new CompressionPlugin({
                algorithm: 'gzip',
                test: /\.js$|\.css$|\.html$|\.jp2$|\.jpg$|\.jpeg$|\.svg$|\.png$|\.webp$|\.mp3$|\.ico$/,
                threshold: 10240,
                minRatio: 1,
                compressionOptions: {
                    level: 7,
                },
            }),
            new MomentLocalesPlugin(),
        ],
    }).
    options({
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
                    comments: false,
                },
            },
        },
    }).
    disableSuccessNotifications().
    disableNotifications().
    version();
