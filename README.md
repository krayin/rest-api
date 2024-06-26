# Krayin REST API

<p>Krayin REST API is a medium to use the features of the core Krayin System. By using Krayin REST API, you can integrate your application to serve the default content of Krayin.</p>

### 1. Requirements:

* **Krayin**: master

### 2. Installation:

##### To install Krayin REST API from your console:

#### For the master version:
~~~
composer require krayin/rest-api
~~~

##### Add below options in the .env file (i.e. http://localhost/public your domain):

~~~
SANCTUM_STATEFUL_DOMAINS=http://localhost/public
~~~

##### To configure the REST API L5-Swagger Documentation run below command:

~~~
php artisan krayin-rest-api:install
~~~

##### To check the API documentation:

~~~
http://localhost/public/api/documentation
~~~

* You can check the <a href="https://github.com/DarkaOnLine/L5-Swagger"> L5-Swagger </a> guidelines too regarding the configuration the API documentation.
