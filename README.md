PHP-Textile CLI
=====

[![Packagist](https://img.shields.io/packagist/v/rah/php-textile-cli.svg)](https://packagist.org/packages/rah/php-textile-cli) [![Coverage](https://sonarcloud.io/api/project_badges/measure?project=gocom_php-textile-cli&metric=coverage)](https://sonarcloud.io/dashboard?id=gocom_php-textile-cli) [![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=gocom_php-textile-cli&metric=alert_status)](https://sonarcloud.io/dashboard?id=gocom_php-textile-cli)

CLI application for parsing Textile markup using [PHP-Textile](https://github.com/textile/php-textile) library, written
in PHP. Accepts Textile markup input and converts it into well-formed HTML.

Install
-----

Using [Composer](https://getcomposer.org):

```shell
$ composer require rah/php-textile-cli
```

Or [download](https://github.com/gocom/php-textile-cli/releases/latest) a textile.zip from the releases. The
ZIP contains a packaged textile.phar, man pages and shell completion definitions for bash, fish and zsh.

Usage
-----

```shell
$ textile [
  [-t|--document-type <type>]
  [-d|--document-root-directory <directory>]
  [-l|--lite]
  [-i|--no-images]
  [-L|--link-relationship <rel>]
  [-r|--restricted]
  [-U|--raw-blocks]
  [-A|--align-classes]
  [-a|--no-align-classes]
  [-b|--no-block-tags]
  [-w|--no-line-wrap]
  [-p|--image-prefix <url>]
  [-P|--link-prefix <url>]
  [-z|--no-dimensions]
  [-o|--output <file>]
] [--] [<file>]
```

Examples:

```shell
$ echo "h1. Textile markup" | vendor/bin/textile
$ vendor/bin/textile -o path/to/README.html path/to/README.textile
$ vendor/bin/textile --help
```

Development
-----

See [CONTRIBUTING.md](https://github.com/gocom/php-textile-cli/blob/master/CONTRIBUTING.md).
