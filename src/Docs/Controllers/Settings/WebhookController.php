<?php

namespace Webkul\RestApi\Docs\Controllers\Settings;

use OpenApi\Attributes as OA;

class WebhookController
{
    #[OA\Get(
        path: '/api/v1/settings/webhooks',
        operationId: 'webhooks',
        tags: ['Webhook'],
        summary: 'Get list of Webhooks',
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
                            items: new OA\Items(ref: '#/components/schemas/Webhook')
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
        path: '/api/v1/settings/webhooks/{id}',
        operationId: 'WebhookFetch',
        tags: ['Webhook'],
        summary: 'Get source by id',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Source Id',
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
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Webhook')
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
    public function show() {}

    #[OA\Post(
        path: '/api/v1/settings/webhooks',
        operationId: 'storeWebhook',
        tags: ['Webhook'],
        summary: 'Create a new webhook',
        description: 'Stores a new webhook resource.',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'name',
                        type: 'string',
                        description: 'Name of the webhook',
                        example: 'test'
                    ),
                    new OA\Property(
                        property: 'entity_type',
                        type: 'string',
                        description: 'Entity type associated with the webhook',
                        example: 'leads'
                    ),
                    new OA\Property(
                        property: 'description',
                        type: 'string',
                        description: 'Description of the webhook',
                        example: 'test'
                    ),
                    new OA\Property(
                        property: 'method',
                        type: 'string',
                        description: 'HTTP method to be used by the webhook',
                        example: 'post'
                    ),
                    new OA\Property(
                        property: 'end_point',
                        type: 'string',
                        description: 'Endpoint URL for the webhook',
                        example: 'http://test.com?lead_title={%leads.title%}'
                    ),
                    new OA\Property(
                        property: 'query_params',
                        type: 'array',
                        description: 'Query parameters for the webhook',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'key', type: 'string', example: 'lead_title'),
                                new OA\Property(property: 'value', type: 'string', example: '{%leads.title%}'),
                            ]
                        )
                    ),
                    new OA\Property(
                        property: 'headers',
                        type: 'array',
                        description: 'Headers to be sent with the webhook',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'key', type: 'string', example: 'Content Type'),
                                new OA\Property(property: 'value', type: 'string', example: 'text/plain;charset=UTF'),
                            ]
                        )
                    ),
                    new OA\Property(
                        property: 'payload_type',
                        type: 'string',
                        description: 'Type of payload sent by the webhook',
                        example: 'default'
                    ),
                    new OA\Property(
                        property: 'raw_payload_type',
                        type: 'string',
                        description: 'Type of raw payload sent by the webhook',
                        example: ''
                    ),
                    new OA\Property(
                        property: 'payload',
                        type: 'string',
                        description: 'Payload content sent by the webhook',
                        example: 'test'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Webhook created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            ref: '#/components/schemas/Webhook'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Bad request'
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    public function store() {}

    #[OA\Put(
        path: '/api/v1/settings/webhooks/{id}',
        operationId: 'updateWebhook',
        tags: ['Webhook'],
        summary: 'Update an existing webhook',
        description: 'Updates the specified webhook resource.',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID of the webhook to update',
                required: true,
                in: 'path',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'name',
                        type: 'string',
                        description: 'Name of the webhook',
                        example: 'test'
                    ),
                    new OA\Property(
                        property: 'entity_type',
                        type: 'string',
                        description: 'Entity type associated with the webhook',
                        example: 'leads'
                    ),
                    new OA\Property(
                        property: 'description',
                        type: 'string',
                        description: 'Description of the webhook',
                        example: 'test'
                    ),
                    new OA\Property(
                        property: 'method',
                        type: 'string',
                        description: 'HTTP method to be used by the webhook',
                        example: 'post'
                    ),
                    new OA\Property(
                        property: 'end_point',
                        type: 'string',
                        description: 'Endpoint URL for the webhook',
                        example: 'http://test.com?lead_title={%leads.title%}'
                    ),
                    new OA\Property(
                        property: 'query_params',
                        type: 'array',
                        description: 'Query parameters for the webhook',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'key', type: 'string', example: 'lead_title'),
                                new OA\Property(property: 'value', type: 'string', example: '{%leads.title%}'),
                            ]
                        )
                    ),
                    new OA\Property(
                        property: 'headers',
                        type: 'array',
                        description: 'Headers to be sent with the webhook',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'key', type: 'string', example: 'Content Type'),
                                new OA\Property(property: 'value', type: 'string', example: 'text/plain;charset=UTF'),
                            ]
                        )
                    ),
                    new OA\Property(
                        property: 'payload_type',
                        type: 'string',
                        description: 'Type of payload sent by the webhook',
                        example: 'default'
                    ),
                    new OA\Property(
                        property: 'raw_payload_type',
                        type: 'string',
                        description: 'Type of raw payload sent by the webhook',
                        example: ''
                    ),
                    new OA\Property(
                        property: 'payload',
                        type: 'string',
                        description: 'Payload content sent by the webhook',
                        example: 'test'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Webhook updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            ref: '#/components/schemas/Webhook'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Bad request'
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
            new OA\Response(
                response: 404,
                description: 'Not Found'
            ),
        ]
    )]
    public function update() {}

    #[OA\Delete(
        path: '/api/v1/settings/webhooks/{id}',
        operationId: 'deleteWebhook',
        tags: ['Webhook'],
        summary: 'Delete a webhook',
        description: 'Deletes the specified webhook resource.',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID of the webhook to delete',
                required: true,
                in: 'path',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Webhook deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'success',
                            type: 'boolean',
                            example: true
                        ),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Webhook deleted successfully.'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
            new OA\Response(
                response: 404,
                description: 'Not Found'
            ),
        ]
    )]
    public function destroy() {}
}
