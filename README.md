PHP-Textile CLI
=====

[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=gocom_php-textile-cli&metric=coverage)](https://sonarcloud.io/dashboard?id=gocom_php-textile-cli) [![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=gocom_php-textile-cli&metric=alert_status)](https://sonarcloud.io/dashboard?id=gocom_php-textile-cli)

**Work in progress, not yet installable.**

CLI application for parsing Textile markup using [PHP-Textile](https://github.com/textile/php-textile) library, written
in PHP. Accepts Textile markup input and converts it into well-formed HTML.

Install
-----

Using [Composer](https://getcomposer.org):

```shell
$ composer require rah/php-textile-cli
```

Or [download](https://github.com/gocom/php-textile-cli/releases/latest) a packaged textile.phar from the releases. The
downloadable ZIP also contains man pages and shell completion definitions for bash, fish and zsh.

Usage
-----

```shell
$ textile [
  [--document-type <type>]
  [--document-root-directory <directory>]
  [-l|--lite]
  [--no-images]
  [--link-relationship <rel>]
  [-r|--restricted]
  [--raw-blocks]
  [--align-classes]
  [--no-align-classes]
  [--no-block-tags]
  [--no-line-wrap]
  [--image-prefix <url>]
  [--link-prefix <url>]
  [--no-dimensions]
  [-o|--output <path>]
] [--] [<file>]
```

Examples:

```shell
$ echo "h1. Textile markup" | vendor/bin/textile
$ vendor/bin/textile -o path/to/README.html path/to/README.textile
```

Development
-----

See [CONTRIBUTING.md](https://github.com/gocom/php-textile-cli/blob/master/CONTRIBUTING.md).
