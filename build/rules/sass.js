const isdev = require('isdev')
const stylelint = require("stylelint")
const autoprefixer = require('autoprefixer')

const ExtractTextPlugin = require("extract-text-webpack-plugin")

const config = require('../app.config')

module.exports = {
    test: /\.s[ac]ss$/,
    include: config.paths.sass,
    loader: ExtractTextPlugin.extract({
        use: [
            {
                loader: 'css-loader',
                options: {
                    sourceMap: isdev
                }
            },

            {
                loader: 'postcss-loader',
                options: {
                    sourceMap: true,
                    plugins: () => [autoprefixer]
                }
            },

            {
                loader: 'sass-loader',
                options: {
                    sourceMap: true
                }
            }
        ],
        fallback: 'style-loader'
    })
}
