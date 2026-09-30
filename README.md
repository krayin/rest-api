# Krayin REST API

<a href="https://packagist.org/packages/krayin/rest-api"><img src="https://poser.pugx.org/krayin/rest-api/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/krayin/rest-api"><img src="https://poser.pugx.org/krayin/rest-api/downloads.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/krayin/rest-api"><img src="https://poser.pugx.org/krayin/rest-api/license.svg" alt="License"></a>

Krayin REST API is a medium to use the features of the core Krayin System. By using Krayin REST API, you can integrate your application to serve the default content of Krayin.

## 1. Requirements

* **Krayin**: v2.2.6

## 2. Installation

### To install Krayin REST API from your console

#### For the latest version of rest api

~~~shell
composer require krayin/rest-api
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

## 3. API Collection

An importable collection covering every `/api/v1` endpoint lives in
[`collections/`](collections). Import
`collections/krayin-rest-api.collection.json` into any client that reads the v2.1 collection
format, set `base_url`, and run the **Login** request — it stores the bearer token and looks up a
real record id per resource, so the remaining requests work against your database without editing
ids by hand.

`collections/krayin-local.environment.json` is an optional environment holding `base_url` and
`token`. See [`collections/RUNNING.md`](collections/RUNNING.md) for running the collection from
the command line with [Newman](https://github.com/postmanlabs/newman).

> Running the whole collection in one pass also sends the delete and mass-destroy requests, which
> remove the records the earlier requests created. `RUNNING.md` describes a non-destructive run.

## 4. Localization

The package ships API response messages in the same locales as Krayin: Arabic (`ar`), English
(`en`), Spanish (`es`), Persian (`fa`), Japanese (`ja`), Korean (`ko`), Brazilian Portuguese
(`pt_BR`), Turkish (`tr`), Vietnamese (`vi`) and Chinese Simplified (`zh_CN`).

Messages follow the application locale, so a request made while the app is set to `ja` receives
Japanese responses. The files live in [`src/Resources/lang`](src/Resources/lang), one `app.php`
per locale, and every locale carries the same key set.

## 5. Automated API Testing

This repository ships a [Playwright](https://playwright.dev/docs/api-testing) automation suite
(482 tests in 26 spec files) that covers authentication, leads, contacts, products, quotes,
activities, mails, dashboard and settings endpoints. The test code lives in
[`tests/playwright-api`](tests/playwright-api).

### Toolchain

| Package | Version |
| --- | --- |
| `@playwright/test` | ^1.40.0 (tested on 1.62.1) |
| `typescript` | ^7.0.2 |
| `zod` | ^4.6.5 — response schema contracts |
| `dotenv` | ^17.4.2 |
| `@types/node` | ^26.5.1 |

Node.js 20 or newer is required; the suite is developed against Node 24.

### Reports

Every run writes a timestamped directory under `tests/playwright-api/reports/`, so results are
never overwritten between runs:

```
tests/playwright-api/reports/<ISO timestamp>/
├── html/            browsable report — open with `npm run test:report`
├── results.json     machine readable, for dashboards and triage scripts
└── results.xml      JUnit, for CI test reporting
```

A trace is retained for every failed test (`trace: 'retain-on-failure'`), viewable from the HTML
report or with `npx playwright show-trace <trace.zip>`.

The suite runs single-worker and serially on purpose: Krayin revokes a user's existing tokens on
every login, so parallel workers authenticating as the same account would invalidate each other
mid-run.

A GitHub Actions workflow (`.github/workflows/playwright-api-tests.yml`) installs Krayin CRM +
this REST API module from scratch, serves the application and runs the full suite on every
push/PR — publishing the HTML report as an artifact on each run.

For setup instructions, how to run the tests locally and in CI, see the
**[automation test suite README](tests/playwright-api/README.md)**.
