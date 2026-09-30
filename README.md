# Krayin REST API

[![Playwright API Tests](https://github.com/sagarkumar-webkul/rest-api/actions/workflows/playwright-api-tests.yml/badge.svg)](https://github.com/sagarkumar-webkul/rest-api/actions/workflows/playwright-api-tests.yml)

Krayin REST API is a medium to use the features of the core Krayin System. By using Krayin REST API, you can integrate your application to serve the default content of Krayin.

## 1. Requirements

* **Krayin**: v2.2.6

## 2. Installation

### To install Krayin REST API from your console

#### For the latest version of rest api

~~~shell
composer require krayin/rest-api:"2.2.x-dev"
~~~

### Add the following options to your .env file

~~~env
SANCTUM_STATEFUL_DOMAINS="${APP_URL}"
~~~

~~~env
L5_SWAGGER_UI_PERSIST_AUTHORIZATION=true
~~~

### To configure the REST API with L5-Swagger documentation, run the following command

~~~shell
php artisan krayin-rest-api:install
~~~

After executing the above command, you will see the API endpoint displayed in the shell.

### Alternatively, you can check the API documentation by visiting the following URL in your browser

~~~shell
http://localhost/public/api/admin/documentation
~~~

* You can check the [L5-Swagger](https://github.com/DarkaOnLine/L5-Swagger) guidelines too regarding the configuration the API documentation.

## 3. Automated API Testing

This repository ships a [Playwright](https://playwright.dev/docs/api-testing) automation suite (466 tests in 24 spec files) that covers authentication, leads, contacts, products, quotes, activities, mails, dashboard and settings endpoints. The test code lives in [`tests/playwright-api`](tests/playwright-api).

A GitHub Actions workflow (`.github/workflows/playwright-api-tests.yml`) installs Krayin CRM + this REST API module from scratch, serves the application and runs the full suite on every push/PR — publishing an HTML report artifact on each run.

For setup instructions, how to run the tests locally and in CI, see the **[automation test suite README](tests/playwright-api/README.md)**.
