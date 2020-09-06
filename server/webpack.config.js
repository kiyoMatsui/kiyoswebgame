const path = require('path');
const webpack = require('webpack');
const CopyPlugin = require('copy-webpack-plugin');

module.exports = {
    entry: {
        //gamebundle: './app/assets/game.js',
        myMain: './app/assets/main.js'
    },
    output: {
        filename: '[name].js',
        path: __dirname + '/www/dist'
    },
    module: {
        rules: [{
                test: /\.html$/i,
                loader: 'html-loader',
                options: {
                    // Disables attributes processing (true is default)
                    attributes: true,
                },
            },
            {
                test: /\.(css)$/,
                use: [{
                        // creates style nodes from JS strings
                        loader: "style-loader",
                        options: {
                            //sourceMap: true
                        }
                    },
                    {
                        // translates CSS into CommonJS
                        loader: "css-loader",
                        options: {
                            //sourceMap: true
                        }
                    },
                    // Please note we are not running postcss here
                ]
            },
            {
                test: /\.(jpe?g|png|gif)$/i,
                loader: "file-loader",
                query: {
                    name: '[name].[ext]',
                    outputPath: 'images/'
                }
            },
            {
                test: /.(ttf|otf|eot|svg|woff(2)?)(\?[a-z0-9]+)?$/,
                use: [{
                    loader: 'file-loader',
                    options: {
                        name: '[name].[ext]',
                        outputPath: 'fonts/'
                    }
                }]
            },
        ],
    },
    devtool: 'source-map',
    devServer: {
        watchContentBase: true,
        contentBase: './www/',
        publicPath: '/dist',
        compress: true,
        disableHostCheck: true,
        hot: true,
        watchOptions: {
            aggregateTimeout: 500,
            poll: 1000
        }
    },
    plugins: [
        new webpack.BannerPlugin({
            banner: 'Copyright (C) 2020 Webgame.'
        }),
        new CopyPlugin({
            patterns: [
                { from: './app/assets/logo.svg', to: './' },
            ],
        }),
        new webpack.ProvidePlugin({
            "$": "jquery",
            "jQuery": "jquery",
            "window.jQuery": "jquery"
        })
    ],
};