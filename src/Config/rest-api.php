<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Override the application exception handler
    |--------------------------------------------------------------------------
    |
    | The package rebinds the application-global exception handler so that
    | `api/*` requests always receive a clean JSON error (never an HTML error
    | page), even in debug mode. A host application that ships its own handler
    | can set this to false to keep full control of exception rendering.
    |
    */
    'override_exception_handler' => true,
];
