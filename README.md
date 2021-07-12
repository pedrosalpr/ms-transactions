# Transactions Microservice

Transaction microservice is a service to make deposit and transfer transactions between users

## Services

### Endpoints

There are only three endpoints:

- Transfer between users
- Make deposit
- Return balance

## Development

### Setup

To run the project, run the following commands:

```bash
make docker-up
make docker-install
make docker-test
```

Go to url [http://ms_transactions_app:8012](http://ms_transactions_app:8012)

## Debugging

### Linters

There are 2 linters you can run in development mode to check the source code: `Code Sniffer` and `Mess Detector`

### Code Sniffer

Detects coding style issues.

There is a configuration file called `phpcs.xml` which has all the Code Sniffer settings.

You can run via composer:

```bash
composer phplint-cs
```

Or by Makefile

```bash
make docker-phplint-cs
```

This script runs only in the `./app` directory, applying the `PSR-2` style.

### Mess Detector

Detects violations via static code analysis.

There is a configuration file called `phpmd.xml` which has all the Code Sniffer settings.

You can run via composer:

```bash
composer phplint-md
```

Or by Makefile

```bash
make docker-phplint-md
```

This script runs only in the `./app` directory, applying the settings from the file.

## Reports and Metrics

### Dox Report

There are a set of scripts that are run to generate the PHP Dox report.

The intent is to generate project documentation using the following tools

- PHP Loc
- PHP Unit
- PHP Mess Detector
- PHP Checkstyle
- PHP CodeSniffer

You can run it via composer to generate the report:

```bash
composer php-dox-full
```

### PHP Metrics Report

Provides metrics about the project and PHP classes in an HTML report.

You can run it via composer to generate the report:

```bash
composer php-metrics
```

### PHP Doc Report

It is an application that is able to parse PHP source code and DocBlock comments to generate a complete set of Transaction Microservice Documentation.

You can run it via composer to generate the report:

```bash
composer php-doc
```

However, it is necessary to download the file [`phpDocumentor.phar`](http://phpdoc.org/phpDocumentor.phar) and place it in the root of the project.

### Report

You can generate the complete report using the command via composer

```bash
composer php-report
```

Or by Makefile

```bash
make docker-phpreport
```

To access you will need to get the absolute path of the `index.html` file that is inside the `doc/build/` folder and put it in the browser.
