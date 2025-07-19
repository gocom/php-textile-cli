% TEXTILE(1)
% Jukka Svahn
% July 2025

# NAME

textile -- Compile Textile markup documents to HTML

# SYNOPSIS

**textile** [*options*] `<`*file*`>`

# DESCRIPTION

CLI implementation for PHP-Textile, a modern Textile markup language parser for PHP. Textile is a humane web
text generator that takes lightweight, readable, plaintext-like markup language and converts it into well-formed
HTML.

# OPTIONS

`-h`, `--help`
: Print help.

`-v`, `--version`
: Print version.

*file*
: Input Textile document file to process. Alternative the input can be read from `STDIN`.

# ENVIRONMENT

`TERM`
: Is used to figure out terminal color support.

# FILES

Depends on external programs `php` as the runtime. Requires PHP version 8.1 or newer.

# EXAMPLES

Source Textile document can be specified with an argument, and the location
where the HTML output documented is saved to with `--output` option:

    $ textile --output=generated.html source.textile

Compiles piped standard input containing Textile markup into HTML:

    $ echo "Hello world" | textile

The `--restricted` flag can be used when processing untrusted user-input, it
will disable features that could generate potentially dangerous HTML:

    $ textile --restricted path/to/untrusted.textile
