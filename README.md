PHP-Textile CLI
=====

[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=gocom_php-textile-cli&metric=coverage)](https://sonarcloud.io/dashboard?id=gocom_php-textile-cli) [![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=gocom_php-textile-cli&metric=alert_status)](https://sonarcloud.io/dashboard?id=gocom_php-textile-cli)

**Work in progress, not yet installable.**

CLI application for parsing Textile markup using [PHP-Textile](https://github.com/textile/php-textile) library. Written
in PHP.

Install
-----

Using [Composer](https://getcomposer.org):

```shell
$ composer require rah/php-textile-cli
```

Or [download](https://github.com/gocom/php-textile-cli/releases/latest) a packaged textile.phar from releases.

Usage
-----

```shell
$ vendor/bin/textile [[-h|--help][-c|--compress][--outdir=<path>]] <file>
```

Examples:

```shell
$ echo "h1. Textile markup" | vendor/bin/textile
$ vendor/bin/textile --o path/to/README.html path/to/README.textile
```

Development
-----

See [CONTRIBUTING.md](https://github.com/gocom/php-textile-cli/blob/master/CONTRIBUTING.md).
