% TEXTILE(1)
% Jukka Svahn
% July 2025

# NAME

textile -- Compile Textile markup documents to HTML

# SYNOPSIS

**textile** [*options*] `<`*file*`>`

# DESCRIPTION

CLI implementation of PHP-Textile, a modern Textile markup language parser for PHP. Textile is a humane web
text generator that takes lightweight, readable, plaintext-like markup language and converts it into well-formed
HTML.

# OPTIONS

`-h`, `--help`
: Print help.

`-V`, `--version`
: Print version.

`-d` `<`*type*`>`, `--document-type`[=]=`<`*type*`>`
: Specifies output document type that is generated from the given Textile input. Supported values are `html5` and
`xhtml`. Defaults to `html5`.

`--document-root-directory`[=]=`<`*directory*`>`
: Sets the directory path that is used to resolve relative file paths within local filesystem. For instance, this is
used for resolving image dimensions. If not set, defaults to the current working directory.

`-l`, `--lite`
: Enables lite mode. If enabled, allowed tags are limited. Parser will prevent the use of extra
Textile formatting, accepting only paragraphs and blockquotes as valid block tags.

: The option doesn't prevent the use of potentially unsafe document features. If you wish to parse untrusted user-given
Textile input, also enable the restricted mode with `--restricted` or `-r` option.

`-i`, `--no-images`
: Disables images. If disabled, image tags are not generated. This option can be used for minimalist output such as
text-only documents.

`-L`, `--link-relationship`
: Sets the HTML `rel` attribute value that are applied to links generated from Textile input. Good values could
include **nofollow** or **noreferrer**.

`-r`, `--restricted`
: Enables restricted mode. This option should be enabled when parsing untrusted user input, including comments or
social media posts. When enabled, the parser escapes any raw HTML input, ignores unsafe attributes and generates links
only for whitelisted safe URL schemes.

: If restricted mode is left disabled, the parser allows users to mix raw HTML and Textile markup. Using the parser in
non-restricted on untrusted input, like comments and forum posts, will lead to XSS issues, as users will be able to use
any HTML code, JavaScript links and Textile attributes in their markup input.

`-U`, `--raw-blocks`
: Enables raw blocks. When raw blocks are enabled, any paragraph blocks wrapped in a non-standard HTML or XML like tags
will be left as is, without parsing the paragraph's content. Recognized phrasing tags and block HTML tags are
excluded.

`-A`, `--align-classes`
: Force enables alignment classes. By default, in when using **html5** as the target `--document-type`, HTML img
elements are generated with **align-left**, **align-center** and **align-right** class selectors rather than align
attribute being added to the element. With this option you can enable that functionality for the **xhtml** document type
mode too.

`-a`, `--no-align-classes`
: Force disables alignment classes. Generated **html5** document will also end up using invalid align attributes,
instead of classes.

`-b`, `--no-block-tags`
: Disables rendering of block tags. The given Textile input will not be wrapped to paragraph blocks, allowing the
formatting of a single line of text.

`-w`, `--no-line-wrap`
: Disables line wrapping. Input document's line-breaks are ignored. This setting can be used if the input document's
lines are pre-wrapped. For instance, in case the input is from CLI content, or source code documentation.

`-p`, `--image-prefix`
: Sets image URL prefix. The given URL is used to prefix relative image paths.

`-P`, `--link-prefix`
: Sets link URL prefix. The given URL is used to prefix relative link paths.

`-z`, `--no-dimensions`
: Disable adding **width** and **height** dimensions to images.

`-o` `<`*file*`>`, `--output`[=]=`<`*file*`>`
: Path to where the resulting output HTML document is saved to. If not given, the results are printed to
**STDOUT**.

`-q`, `--quiet`
: Do not output any messages.

`--ansi`
: Force enable ANSI output.

`--no-ansi`
: Force disable ANSI output.

`--no-interaction`
: Do not ask any interactive question.

`-v`, `-vv`, `-vvv`, `--verbose`
: Increase verbosity. `-v` for verbose, `-vv` for more verbose and `-vvv` for debug.

*file*
: Input Textile document file to process. Alternatively, the input can be read from `STDIN`.

# ENVIRONMENT

`NO_COLOR`
: Variable is used to figure whether output should use colors.

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
