#!/usr/bin/env bash

if [[ ! -f 'scripts/phpcs.phar' ]]; then

    curl -L https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar --output scripts/phpcs.phar

fi
