<?php

namespace Webkul\RestApi\Docs\Controllers\Quote;

use OpenApi\Attributes as OA;

class QuoteController
{
    #[OA\Get(
        path: '/api/v1/quotes',
        operationId: 'quoteList',
        tags: ['Quotes'],
        summary: 'Get list of quotes',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'sort',
                description: 'Sort column',
                example: 'id',
                required: false,
                in: 'query',
                schema: new OA\Schema(type: 'string')
            ),
            new OA\Parameter(
                name: 'order',
                description: 'Sort order',
                required: false,
                in: 'query',
                schema: new OA\Schema(type: 'string', enum: ['desc', 'asc'])
            ),
            new OA\Parameter(
                name: 'page',
                description: 'Page number',
                required: false,
                in: 'query',
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'limit',
                description: 'Limit',
                in: 'query',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Quote')
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    public function index() {}

    #[OA\Get(
        path: '/api/v1/quotes/search',
        operationId: 'searchQuotes',
        tags: ['Quotes'],
        summary: 'search the quotes',
        description: 'search the quotes heres the webkul is the search keyword',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'search',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'subject:webkul;description:webkul;user.name:webkul;person.name:webkul;')
            ),
            new OA\Parameter(
                name: 'searchFields',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'subject:like;description:like;user.name:like;person.name:like;')
            ),
            new OA\Parameter(
                name: 'limit',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 10)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            ref: '#/components/schemas/Tag'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function search() {}

    #[OA\Post(
        path: '/api/v1/quotes',
        operationId: 'storeQuote',
        tags: ['Quotes'],
        summary: 'Store the Quote',
        description: 'Store the Quote',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Store Quote',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'description',
                        type: 'string',
                        description: 'Description of the quote',
                        example: 'School Management Quote'
                    ),
                    new OA\Property(
                        property: 'expired_at',
                        type: 'string',
                        format: 'date',
                        description: 'Expiration date of the quote',
                        example: '2024-05-31'
                    ),
                    new OA\Property(
                        property: 'person_id',
                        type: 'integer',
                        description: 'ID of the person',
                        example: 1
                    ),
                    new OA\Property(
                        property: 'subject',
                        type: 'string',
                        description: 'Subject of the quote',
                        example: 'Webkul'
                    ),
                    new OA\Property(
                        property: 'user_id',
                        type: 'integer',
                        description: 'ID of the user',
                        example: 1
                    ),
                    new OA\Property(
                        property: 'lead_id',
                        type: 'integer',
                        description: 'ID of the lead',
                        example: 1
                    ),
                    new OA\Property(
                        property: 'billing_address',
                        description: 'Billing address details',
                        properties: [
                            new OA\Property(
                                property: 'address',
                                type: 'string',
                                description: 'Street address',
                                example: '123 Main St'
                            ),
                            new OA\Property(
                                property: 'country',
                                type: 'string',
                                description: 'Country code',
                                example: 'US'
                            ),
                            new OA\Property(
                                property: 'state',
                                type: 'string',
                                description: 'State code',
                                example: 'CA'
                            ),
                            new OA\Property(
                                property: 'city',
                                type: 'string',
                                description: 'City name',
                                example: 'Los Angeles'
                            ),
                            new OA\Property(
                                property: 'postcode',
                                type: 'string',
                                description: 'Postal code',
                                example: '90001'
                            ),
                        ]
                    ),
                    new OA\Property(
                        property: 'shipping_address',
                        description: 'Shipping address details',
                        properties: [
                            new OA\Property(
                                property: 'address',
                                type: 'string',
                                description: 'Street address',
                                example: '123 Main St'
                            ),
                            new OA\Property(
                                property: 'country',
                                type: 'string',
                                description: 'Country code',
                                example: 'US'
                            ),
                            new OA\Property(
                                property: 'state',
                                type: 'string',
                                description: 'State code',
                                example: 'CA'
                            ),
                            new OA\Property(
                                property: 'city',
                                type: 'string',
                                description: 'City name',
                                example: 'Los Angeles'
                            ),
                            new OA\Property(
                                property: 'postcode',
                                type: 'string',
                                description: 'Postal code',
                                example: '90001'
                            ),
                        ]
                    ),
                    new OA\Property(
                        property: 'items',
                        type: 'object',
                        description: 'List of items',
                        properties: [
                            new OA\Property(
                                property: 'item_0',
                                type: 'object',
                                properties: [
                                    new OA\Property(
                                        property: 'product_id',
                                        type: 'string',
                                        example: '1'
                                    ),
                                    new OA\Property(
                                        property: 'quantity',
                                        type: 'string',
                                        example: '100'
                                    ),
                                    new OA\Property(
                                        property: 'price',
                                        type: 'string',
                                        example: '50'
                                    ),
                                    new OA\Property(
                                        property: 'total',
                                        type: 'string',
                                        example: '5000'
                                    ),
                                    new OA\Property(
                                        property: 'discount_amount',
                                        type: 'string',
                                        example: '0'
                                    ),
                                    new OA\Property(
                                        property: 'tax_amount',
                                        type: 'string',
                                        example: '0'
                                    ),
                                ]
                            ),
                        ]
                    ),
                    new OA\Property(
                        property: 'sub_total',
                        type: 'number',
                        format: 'float',
                        description: 'Subtotal amount',
                        example: 5000.0
                    ),
                    new OA\Property(
                        property: 'discount_amount',
                        type: 'number',
                        format: 'float',
                        description: 'Discount amount',
                        example: 0.0
                    ),
                    new OA\Property(
                        property: 'tax_amount',
                        type: 'number',
                        format: 'float',
                        description: 'Tax amount',
                        example: 0.0
                    ),
                    new OA\Property(
                        property: 'adjustment_amount',
                        type: 'number',
                        format: 'float',
                        description: 'Adjustment amount',
                        example: 0.0
                    ),
                    new OA\Property(
                        property: 'grand_total',
                        type: 'number',
                        format: 'float',
                        description: 'Grand total amount',
                        example: 5000.0
                    ),
                    new OA\Property(
                        property: 'entity_type',
                        type: 'string',
                        description: 'Type of the entity',
                        example: 'quotes'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Quote created successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Quote')
            ),
            new OA\Response(
                response: 400,
                description: 'Bad request'
            ),
        ]
    )]
    public function store() {}

    #[OA\Get(
        path: '/api/v1/quotes/{id}',
        operationId: 'getQuoteById',
        tags: ['Quotes'],
        summary: 'Get quote information',
        description: 'Get quote information',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Quote Id',
                required: true,
                in: 'path',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            ref: '#/components/schemas/Quote'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Quote not found'
            ),
        ]
    )]
    public function show() {}

    #[OA\Put(
        path: '/api/v1/quotes/{id}',
        operationId: 'updateQuote',
        tags: ['Quotes'],
        summary: 'Update the Quote',
        description: 'Update the Quote',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Quote Id',
                required: true,
                in: 'path',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Store Quote',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'description',
                        type: 'string',
                        description: 'Description of the quote',
                        example: 'School Management Quote'
                    ),
                    new OA\Property(
                        property: 'expired_at',
                        type: 'string',
                        format: 'date',
                        description: 'Expiration date of the quote',
                        example: '2024-05-31'
                    ),
                    new OA\Property(
                        property: 'person_id',
                        type: 'integer',
                        description: 'ID of the person',
                        example: 1
                    ),
                    new OA\Property(
                        property: 'subject',
                        type: 'string',
                        description: 'Subject of the quote',
                        example: 'Webkul'
                    ),
                    new OA\Property(
                        property: 'user_id',
                        type: 'integer',
                        description: 'ID of the user',
                        example: 1
                    ),
                    new OA\Property(
                        property: 'lead_id',
                        type: 'integer',
                        description: 'ID of the lead',
                        example: 1
                    ),
                    new OA\Property(
                        property: 'billing_address',
                        description: 'Billing address details',
                        properties: [
                            new OA\Property(
                                property: 'address',
                                type: 'string',
                                description: 'Street address',
                                example: '123 Main St'
                            ),
                            new OA\Property(
                                property: 'country',
                                type: 'string',
                                description: 'Country code',
                                example: 'US'
                            ),
                            new OA\Property(
                                property: 'state',
                                type: 'string',
                                description: 'State code',
                                example: 'CA'
                            ),
                            new OA\Property(
                                property: 'city',
                                type: 'string',
                                description: 'City name',
                                example: 'Los Angeles'
                            ),
                            new OA\Property(
                                property: 'postcode',
                                type: 'string',
                                description: 'Postal code',
                                example: '90001'
                            ),
                        ]
                    ),
                    new OA\Property(
                        property: 'shipping_address',
                        description: 'Shipping address details',
                        properties: [
                            new OA\Property(
                                property: 'address',
                                type: 'string',
                                description: 'Street address',
                                example: '123 Main St'
                            ),
                            new OA\Property(
                                property: 'country',
                                type: 'string',
                                description: 'Country code',
                                example: 'US'
                            ),
                            new OA\Property(
                                property: 'state',
                                type: 'string',
                                description: 'State code',
                                example: 'CA'
                            ),
                            new OA\Property(
                                property: 'city',
                                type: 'string',
                                description: 'City name',
                                example: 'Los Angeles'
                            ),
                            new OA\Property(
                                property: 'postcode',
                                type: 'string',
                                description: 'Postal code',
                                example: '90001'
                            ),
                        ]
                    ),
                    new OA\Property(
                        property: 'items',
                        type: 'object',
                        description: 'List of items',
                        properties: [
                            new OA\Property(
                                property: 'item_0',
                                type: 'object',
                                properties: [
                                    new OA\Property(
                                        property: 'product_id',
                                        type: 'string',
                                        example: '1'
                                    ),
                                    new OA\Property(
                                        property: 'quantity',
                                        type: 'string',
                                        example: '100'
                                    ),
                                    new OA\Property(
                                        property: 'price',
                                        type: 'string',
                                        example: '50'
                                    ),
                                    new OA\Property(
                                        property: 'total',
                                        type: 'string',
                                        example: '5000'
                                    ),
                                    new OA\Property(
                                        property: 'discount_amount',
                                        type: 'string',
                                        example: '0'
                                    ),
                                    new OA\Property(
                                        property: 'tax_amount',
                                        type: 'string',
                                        example: '0'
                                    ),
                                ]
                            ),
                        ]
                    ),
                    new OA\Property(
                        property: 'sub_total',
                        type: 'number',
                        format: 'float',
                        description: 'Subtotal amount',
                        example: 5000.0
                    ),
                    new OA\Property(
                        property: 'discount_amount',
                        type: 'number',
                        format: 'float',
                        description: 'Discount amount',
                        example: 0.0
                    ),
                    new OA\Property(
                        property: 'tax_amount',
                        type: 'number',
                        format: 'float',
                        description: 'Tax amount',
                        example: 0.0
                    ),
                    new OA\Property(
                        property: 'adjustment_amount',
                        type: 'number',
                        format: 'float',
                        description: 'Adjustment amount',
                        example: 0.0
                    ),
                    new OA\Property(
                        property: 'grand_total',
                        type: 'number',
                        format: 'float',
                        description: 'Grand total amount',
                        example: 5000.0
                    ),
                    new OA\Property(
                        property: 'entity_type',
                        type: 'string',
                        description: 'Type of the entity',
                        example: 'quotes'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'Object',
                            ref: '#/components/schemas/Quote'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Bad request'
            ),
        ]
    )]
    public function update() {}

    #[OA\Delete(
        path: '/api/v1/quotes/{id}',
        operationId: 'deleteQuote',
        tags: ['Quotes'],
        summary: 'Delete the Quote',
        description: 'Delete the Quote',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Quote Id',
                required: true,
                in: 'path',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Quote deleted successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Quote not found'
            ),
        ]
    )]
    public function destroy() {}

    #[OA\Post(
        path: '/api/v1/quotes/mass-destroy',
        operationId: 'massDeleteQuote',
        tags: ['Quotes'],
        summary: 'Delete the Quote',
        description: 'Delete the Quote',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Quote details',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'indices',
                        type: 'array',
                        description: 'Quote IDs',
                        items: new OA\Items(
                            type: 'integer',
                            example: '1'
                        )
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'Object',
                            ref: '#/components/schemas/Quote',
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    public function massDestroy() {}
}
