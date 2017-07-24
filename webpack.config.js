/**
 * Encore configuration, handles creating all static assets
 *
 * @author Roelof Roos
 */

// webpack.config.js
const Encore = require('@symfony/webpack-encore')
const CopyWebpackPlugin = require('copy-webpack-plugin')
const ImageminPlugin = require('imagemin-webpack-plugin').default
const ImageminMozjpeg = require('imagemin-mozjpeg')
const StyleLintPlugin = require('stylelint-webpack-plugin')

Encore
  // directory where all compiled assets will be stored
  .setOutputPath('web/public/')

  // what's the public path to this directory (relative to your project's document root dir)
  .setPublicPath('/public')

  // empty the outputPath dir before each build
  .cleanupOutputBeforeBuild()

  // will output as web/build/app.js
  .addEntry('app', './app/Resources/js/main.js')

  // will output as web/build/main.css
  .addStyleEntry('main', './app/Resources/scss/main.scss')

  // allow sass/scss files to be processed
  .enableSassLoader()

  // Enable source maps on production
  .enableSourceMaps(!Encore.isProduction())

  // create hashed filenames (e.g. app.abc123.css)
  .enableVersioning()

  // Enable PostCSS processing
  .enablePostCssLoader()

  // Add ESLint
  .addLoader({
    enforce: 'pre',
    test: /\.jsx?$/,
    exclude: /node_modules/,
    loader: 'eslint-loader',
    options: {
      cache: true
    }
  })

  // Add StyleLint
  .addPlugin(new StyleLintPlugin())

  // Copy images to destination
  .addPlugin(new CopyWebpackPlugin([
    { from: './app/Resources/images/*.{jpg,jpeg,png,gif,svg}' }
  ]))

  // Apply imagemin, using mozjpeg for JPEG minification
  .addPlugin(new ImageminPlugin({
    disable: !Encore.isProduction(),
    test: /\.(jpe?g|png|gif|svg)$/i,
    plugins: [
      ImageminMozjpeg({
        quality: 90,
        progressive: true
      })
    ]
  }))

// export the final configuration
module.exports = Encore.getWebpackConfig()
