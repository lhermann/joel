const path = require("path");
const isdev = require("isdev");
const webpack = require("webpack");
const autoprefixer = require("autoprefixer");

const CopyPlugin = require("copy-webpack-plugin");
const ShellPlugin = require("webpack-shell-plugin");
const OnBuildPlugin = require("on-build-webpack");
const CleanPlugin = require("clean-webpack-plugin");
const StyleLintPlugin = require("stylelint-webpack-plugin");
const ExtractTextPlugin = require("extract-text-webpack-plugin");
const BrowserSyncPlugin = require("browser-sync-webpack-plugin");
const { default: ImageminPlugin } = require("imagemin-webpack-plugin");
const shell = require("shelljs");

const sassRule = require("./rules/sass");
const fontsRule = require("./rules/fonts");
const imagesRule = require("./rules/images");
const javascriptRule = require("./rules/javascript");
const externalFontsRule = require("./rules/external.fonts");
const externalImagesRule = require("./rules/external.images");

const config = require("./app.config");

module.exports = {
    /**
     * Should the source map be generated?
     * @type {string|undefined}
     */
    devtool: isdev && config.settings.sourceMaps ? "source-map" : undefined,

    /**
     * Application entry files for building.
     * @type {Object}
     */
    entry: config.assets,

    /**
     * Output settings for application scripts.
     * @type {Object}
     */
    output: {
        path: config.paths.public,
        filename: config.outputs.javascript.filename
    },

    /**
     * External objects which should be accessible inside application scripts.
     * @type {Object}
     */
    externals: config.externals,

    /**
     * Performance settings to speed up build times.
     * @type {Object}
     */
    performance: {
        hints: false
    },

    /**
     * Build rules to handle application assset files.
     * @type {Object}
     */
    module: {
        rules: [
            sassRule,
            fontsRule,
            imagesRule,
            javascriptRule,
            externalFontsRule,
            externalImagesRule
        ]
    },

    /**
     * Common plugins which should run on every build.
     * @type {Array}
     */
    plugins: [
        new webpack.LoaderOptionsPlugin({ minimize: !isdev }),
        new ExtractTextPlugin(config.outputs.css),
        new CleanPlugin(config.paths.public, { root: config.paths.root }),
        new CopyPlugin([
            {
                from: {
                    glob: `${config.paths.images}/**/*`,
                    flatten: true,
                    dot: false
                },
                to: config.outputs.image.filename
            }
        ])
    ],

    /**
     * Load vue including the compiler to compile in-DOM HTML
     */
    resolve: {
        alias: {
            vue$: "vue/dist/vue.esm.js"
        }
    }
};

/**
 * Adds Stylelint plugin if
 * linting is configured.
 */
if (config.settings.styleLint) {
    module.exports.plugins.push(new StyleLintPlugin());
}

/**
 * Adds BrowserSync plugin when
 * settings are configured.
 */
// if (config.settings.browserSync) {
//     module.exports.plugins.push(
//         new BrowserSyncPlugin(config.settings.browserSync)
//     );
// }

/**
 * Plugins only added on development build
 */
// if (isdev) {
//     // Syncing assets to patternlab
//     module.exports.plugins.push(
//         new OnBuildPlugin(function(stats) {
//             let command =
//                 "printf '[\033[0;31mrsync\033[0m] Syncing assets to patternlab\n'";
//             command +=
//                 "&& rsync -az --include='*/' --include='*.css' --include='*.js' --exclude='*' public/ ../patternlab/source";
//             shell.exec(command, { async: true });
//         })
//     );
// }

/**
 * Adds optimalizing plugins when
 * generating production build.
 */
if (!isdev) {
    module.exports.plugins.push(
        new webpack.optimize.UglifyJsPlugin({
            comments: isdev,
            compress: { warnings: false },
            sourceMap: isdev
        })
    );

    module.exports.plugins.push(
        new ImageminPlugin({
            test: /\.(jpe?g|png|gif|svg)$/i,
            optipng: { optimizationLevel: 7 },
            gifsicle: { optimizationLevel: 3 },
            pngquant: { quality: "65-90", speed: 4 },
            svgo: { removeUnknownsAndDefaults: false, cleanupIDs: false }
        })
    );
}
