<?php

return [
    'common' => [
        'resource-not-found'    => 'The requested resource could not be found.',
        'forbidden-error'       => 'You do not have permission to access this resource.',
        'unauthenticated'       => 'You are not authenticated. Please log in to continue.',
        'internal-server-error' => 'An unexpected error occurred on the server. Please try again later.',
    ],

    'products' => [
        'create-success'  => 'Product created successfully.',
        'updated-success' => 'Product updated successfully.',
        'delete-success'  => 'Product deleted successfully.',
        'delete-failed'   => 'Product delete failed.',
    ],

    'leads' => [
        'create-success'  => 'Lead created successfully.',
        'updated-success' => 'Lead updated successfully.',
        'delete-success'  => 'Lead deleted successfully.',
        'delete-failed'   => 'Lead delete failed.',

        'view' => [
            'tags' => [
                'create-success' => 'Tag attached successfully.',
                'delete-success' => 'Tag detached successfully.',
            ],

            'quotes' => [
                'create-success' => 'Quote attached successfully.',
                'delete-success' => 'Quote detached successfully.',
            ],
        ],
    ],

    'mail' => [
        'create-success' => 'Email created successfully.',
        'update-success' => 'Email updated successfully.',
        'delete-success' => 'Email deleted successfully.',
        'delete-failed'  => 'Email delete failed.',
    ],
];
