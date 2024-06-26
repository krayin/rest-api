<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    /**
     * User routes.
     */
    require 'user-routes.php';

    /**
     * Lead routes.
     */
    require 'lead-routes.php';

    /**
     * Qoute routes.
     */
    require 'qoute-routes.php';

    /**
     * Mail routes.
     */
    require 'mail-routes.php';

    /**
     * Activity routes.
     */
    require 'activity-routes.php';

    /**
     * Contact routes.
     */
    require 'contact-routes.php';

    /**
     * Product routes.
     */
    require 'product-routes.php';

    /**
     * Setting routes.
     */
    require 'setting-routes.php';

    /**
     * Configuration routes.
     */
    require 'configuration-routes.php';
});
