.PHONY: all dist docker-build lint lint-fix test test-integration test-unit test-static repl process-reports compile clean help

HOST_UID ?= `id -u`
HOST_GID ?= `id -g`
IMAGE ?= latest
PHP = docker compose run --rm -u $(HOST_UID):$(HOST_GID) php

all: test

dist: build-man compile
	$(PHP) bash -c 'rm -rf dist && mkdir -p dist/extra && cp LICENSE dist/ && cp -r build/man dist/man && mv build/textile.phar dist && cp -r extra/* dist/extra && cd dist && zip -r ../dist/textile.zip .'

docker-build:
	docker-compose build php

vendor:
	$(PHP) composer install

lint: vendor
	$(PHP) composer lint

lint-fix: vendor
	$(PHP) composer lint-fix

test: vendor
	$(PHP) composer test

test-integration: vendor
	$(PHP) composer test:integration

test-unit: vendor
	$(PHP) composer test:unit

test-static: vendor
	$(PHP) composer test:static

repl: vendor
	$(PHP) composer repl

compile: vendor
	$(PHP) composer compile

shell:
	$(PHP) bash

clean:
	$(PHP) rm -rf build composer.lock dist .phpunit.result.cache vendor .test-result

build-man:
	$(PHP) bash -c 'mkdir -p build/man/man1/ && pandoc -s -f markdown -t man -o build/man/man1/textile.1 man/man1/textile.1.md'

process-reports:
	$(PHP) bash -c "test -e build/logs/clover.xml && sed -i 's/\/app\///' build/logs/clover.xml"

help:
	@echo "Manage project"
	@echo ""
	@echo "Usage:"
	@echo "  make [command] [IMAGE=<image>]"
	@echo ""
	@echo "Commands:"
	@echo ""
	@echo "  $$ make help"
	@echo "  Display this message"
	@echo ""
	@echo "  $$ make docker-build"
	@echo "  Re-builds Docker image"
	@echo ""
	@echo "  $$ make compile"
	@echo "  Build Phar"
	@echo ""
	@echo "  $$ make clean"
	@echo "  Delete Composer dependencies"
	@echo ""
	@echo "  $$ make install"
	@echo "  Install Composer dependencies"
	@echo ""
	@echo "  $$ make lint"
	@echo "  Check code style"
	@echo ""
	@echo "  $$ make lint-fix"
	@echo "  Try to fix code style"
	@echo ""
	@echo "  $$ make test"
	@echo "  Run linter, static and unit tests"
	@echo ""
	@echo "  $$ make test-unit"
	@echo "  Run only unit tests"
	@echo ""
	@echo "  $$ make test-static"
	@echo "  Run only static tests"
	@echo ""
	@echo "  $$ make repl"
	@echo "  Start read-eval-print loop shell"
	@echo ""
	@echo "  $$ make generate-fixtures"
	@echo "  Generates test fixtures"
	@echo ""
	@echo "  $$ make process-reports"
	@echo "  Formats test reports to use relative local file paths"
	@echo ""
	@echo "Images:"
	@echo ""
	@echo "  latest"
