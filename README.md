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

##### Add the following options to your .env file:


~~~
SANCTUM_STATEFUL_DOMAINS="${APP_URL}"
~~~

~~~
L5_SWAGGER_UI_PERSIST_AUTHORIZATION=true
~~~

##### To configure the REST API with L5-Swagger documentation, run the following command:

~~~
php artisan krayin-rest-api:install
~~~

After executing the above command, you will see the API endpoint displayed in the shell.


##### Alternatively, you can check the API documentation by visiting the following URL in your browser:


~~~
http://localhost/public/api/admin/documentation
~~~

* You can check the <a href="https://github.com/DarkaOnLine/L5-Swagger"> L5-Swagger </a> guidelines too regarding the configuration the API documentation.