# Information concerning dependencies

> This is a todo, the dependencies have not yet been described. This document
> will auto-update as time progresses...

## Webpack-encore

- **Full name:** [`@symfony/webpack-encore`][symfony__webpack-encore]
- **Requested:** `^0.11.0`

Webpack-encore is an abstraction layer over Webpack, which makes some tasks a
lot easier to configure.

[symfony__webpack-encore]: http://npmjs.com/package/@symfony/webpack-encore

## Autoprefixer

- **Full name:** [`autoprefixer`][autoprefixer]
- **Requested:** `^7.1.2`

Autoprefixer adds vendor prefixes to new technologies, which makes our sass
files easier and reduces file size by not including prefixes that are no longer
required or not in our browser scope.

## Babel-preset-env

- **Full name:** [`babel-preset-env`][babel-preset-env]
- **Requested:** `^1.6.0`

Babel-preset-env is a preset for Babel that causes it to read the .babelrc file
to determine which flavour of ECMAScript we're using.

## Bootstrap

- **Full name:** [`bootstrap`][bootstrap]
- **Requested:** `4.0.0-beta`

Bootstrap, the well know framework for Javascript and CSS in included as we're
using the screen reader tools included with it. The IDP does *not* use the
styling, just some accessibility features.

## Bulma

- **Full name:** [`bulma`][bulma]
- **Requested:** `^0.5`

Bulma is used as foundation for the IDP theme. It's used in favour of Bootstrap
as it has a very strict scope, which makes it easier for us as we don't have to
override their Javascript, but write our own instead.

## Copy-webpack-plugin

- **Full name:** [`copy-webpack-plugin`][copy-webpack-plugin]
- **Requested:** `^4.0.1`

Images, vectors and fonts are copied as-is to the output directory. Images are
minified by the imagemin plugin, which looks at all the copied files and
decides which files should be minified.

## Eslint

- **Full name:** [`eslint`][eslint]
- **Requested:** `^4.3.0`

ESLint is used to validate the Javascript files, they must use new techonlgies
such as `let` and `const` in favour of `var` and some other restrictions, such
as no semicolons are enforced as well.

All in all it ensures that the code remains maintainable, even in the scenario
the lead developer leaves, which is a worst-case, but still imaginable
scenario.

## Eslint-config-standard

- **Full name:** [`eslint-config-standard`][eslint-config-standard]
- **Requested:** `^10.2.1`

Eslint-config-standard is installed because it's in the package.json

## Eslint-loader

- **Full name:** [`eslint-loader`][eslint-loader]
- **Requested:** `^1.9.0`

Eslint-loader is installed because it's in the package.json

## Eslint-plugin-import

- **Full name:** [`eslint-plugin-import`][eslint-plugin-import]
- **Requested:** `^2.7.0`

This plugin is installed as it's required by the *Standard* coding standard for
Javascript.

## Eslint-plugin-node

- **Full name:** [`eslint-plugin-node`][eslint-plugin-node]
- **Requested:** `^5.1.1`

This plugin is installed as it's required by the *Standard* coding standard for
Javascript.

## Eslint-plugin-promise

- **Full name:** [`eslint-plugin-promise`][eslint-plugin-promise]
- **Requested:** `^3.5.0`

This plugin is installed as it's required by the *Standard* coding standard for
Javascript.

## Eslint-plugin-standard

- **Full name:** [`eslint-plugin-standard`][eslint-plugin-standard]
- **Requested:** `^3.0.1`

This plugin provides extensions on ESLint to ensure we can use the *Standard*
coding standard for Javascript.

## Imagemin-mozjpeg

- **Full name:** [`imagemin-mozjpeg`][imagemin-mozjpeg]
- **Requested:** `^6.0.0`

MozJPEG is an imagemin plugin that significantly improves the gains on
minifying JPEG files.

## Imagemin-webpack-plugin

- **Full name:** [`imagemin-webpack-plugin`][imagemin-webpack-plugin]
- **Requested:** `^1.5.0-beta.0`

Imagemin only works when you actually invoke it, this plguin takes care of that
by looking at affected files and minifying the files in the output directory.

## Node-sass

- **Full name:** [`node-sass`][node-sass]
- **Requested:** `^4.5.3`

Node-sass brings support for Sass files to the Node JS enviroment, which allows
us to convert it to css without requiring Ruby.

## Postcss-loader

- **Full name:** [`postcss-loader`][postcss-loader]
- **Requested:** `^2.0.6`

Postcss is a plugin that lets plugins like the AutoPrefixer to work their magic,
this is a wrapper to make it work with Webpack.

## Sass-loader

- **Full name:** [`sass-loader`][sass-loader]
- **Requested:** `^6.0.6`

This allows Webpack to compile sass files, it's required by webpack-encore

## Stylelint

- **Full name:** [`stylelint`][stylelint]
- **Requested:** `^8.0.0`

Stylelint validates sass files, making sure the files meet the given standard
as specified in the .stylelintrc file.

## Stylelint-config-standard

- **Full name:** [`stylelint-config-standard`][stylelint-config-standard]
- **Requested:** `^17.0.0`

This package contains default settings for Stylelint, which makes the
configuration in the .stylelintrc a whole lot shorter.

## Stylelint-webpack-plugin

- **Full name:** [`stylelint-webpack-plugin`][stylelint-webpack-plugin]
- **Requested:** `^0.9.0`

This plugin allows Webpack to load sasslint and validate the files *before*
compiling the code.

<!-- SOURCES OF THE PACKAGE FILES -->

[stylelint-webpack-plugin]: http://npmjs.com/package/stylelint-webpack-plugin
[autoprefixer]: http://npmjs.com/package/autoprefixer
[babel-preset-env]: http://npmjs.com/package/babel-preset-env
[bootstrap]: http://npmjs.com/package/bootstrap
[bulma]: http://npmjs.com/package/bulma
[copy-webpack-plugin]: http://npmjs.com/package/copy-webpack-plugin
[eslint]: http://npmjs.com/package/eslint
[eslint-config-standard]: http://npmjs.com/package/eslint-config-standard
[eslint-loader]: http://npmjs.com/package/eslint-loader
[postcss-loader]: http://npmjs.com/package/postcss-loader
[sass-loader]: http://npmjs.com/package/sass-loader
[stylelint]: http://npmjs.com/package/stylelint
[stylelint-config-standard]: http://npmjs.com/package/stylelint-config-standard
[eslint-plugin-import]: http://npmjs.com/package/eslint-plugin-import
[eslint-plugin-node]: http://npmjs.com/package/eslint-plugin-node
[eslint-plugin-promise]: http://npmjs.com/package/eslint-plugin-promise
[eslint-plugin-standard]: http://npmjs.com/package/eslint-plugin-standard
[imagemin-mozjpeg]: http://npmjs.com/package/imagemin-mozjpeg
[imagemin-webpack-plugin]: http://npmjs.com/package/imagemin-webpack-plugin
[node-sass]: http://npmjs.com/package/node-sass
