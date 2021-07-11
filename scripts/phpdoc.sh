#!/usr/bin/env bash

if [[ ! -f 'scripts/phpDocumentor.phar' ]]; then

    curl -L https://phpdoc.org/phpDocumentor.phar --output scripts/phpDocumentor.phar

fi
