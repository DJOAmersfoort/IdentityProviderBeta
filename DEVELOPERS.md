# DJO Identity Provider - Developer Guide

This guide will explain how to help developing of the DJO Identity Provider.

The IDP is built using Symfony 3, and uses some features such as Webpack,
Docker and linters to ensure consistent development environments. We'll run
you through these, after the requirements.

## Requirements

To run the IDP, you'll need the following:

- PHP 7.1 or later (from [Ondřej Surý][r1], for example)

- The following PHP extensions:
   - `ext-zip`
   - `ext-xml`
   - `ext-mbstring`
   - `ext-json`

- Composer ([getcomposer.org][r2])

- PHP Unit (`composer global require phpunit/phpunit`)

- PHP_CodeSniffer (`composer global require squizlabs/php_codesniffer`)

- Docker-compose ([docker.com][r3])

- Yarn ([yarnpk.com][r4])

[r1]: https://deb.sury.org/#php-packages
[r2]: https://getcomposer.org
[r3]: https://docs.docker.com/compose/install/
[r4]: https://yarnpkg.com/en/docs/install
