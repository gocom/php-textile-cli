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

The project uses Docker containers and docker-compose to provide development
environment, and Makefile is used to wrap commands. No other dependencies need
to be installed to the host machine other than Docker and Make.

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
