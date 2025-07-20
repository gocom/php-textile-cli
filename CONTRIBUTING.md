Contributing
=====

License
-----

[MIT](https://raw.github.com/gocom/php-textile-cli/master/LICENSE).

Versioning
----

[Semantic Versioning](https://semver.org/).

Development environment
-----

The project uses Docker containers and docker-compose to provide development environment, and Makefile is used to wrap
commands. No other dependencies need to be installed to the host machine other than Docker and Make.

### Requirements

* Docker
* GNU Make

### Development

For available commands, see:

```shell
$ make help
```

### Coding style

To verify that your additions follows coding style, run:

```shell
$ make lint
```

### Run tests

To run integration and unit tests, and static analysis, run:

```shell
$ make test
```

### Functional testing

The `textile` CLI application and the shell autocomplete scripts can be manually tested within the container. First,
access the container by running:

```shell
$ make shell
```

The `textile` command and the included auto-complete scripts have been linked to the PATH and profile init scripts
respectively during the image build time, making them active during the session by default:

```shell
$ textile -<TAB>
```

The default shell is bash. To test the autocomplete in different shells, just launch the needed shell process:

```shell
$ fish
$ zsh
```

### Compiling phar

Compile the phar on your host system, by running:

```shell
$ make compile
```

You can then access and test it within the container. The phar by default is not added to the PATH, but can be found
under the default current working directory:

```shell
$ make shell
$ build/textile.phar --help
```
