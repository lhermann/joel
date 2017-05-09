const config = require('../app.config')

module.exports = {
    test: /\.(eot|ttf|woff|woff2|svg)(\?\S*)?$/,
    include: config.paths.fonts,
    loader: 'file-loader',
    options: {
        publicPath: config.paths.relative,
        name: config.outputs.font.filename,
    }
}