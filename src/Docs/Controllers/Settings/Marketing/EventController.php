<?php

namespace Webkul\RestApi\Docs\Controllers\Settings\Marketing;

use OpenApi\Attributes as OA;

class EventController
{
    #[OA\Get(
        path: '/api/v1/settings/marketing/events',
        operationId: 'marketingList',
        tags: ['MarketingEvent'],
        summary: 'Get list of Marketing Events',
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
                            items: new OA\Items(ref: '#/components/schemas/Event')
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

    #[OA\Post(
        path: '/api/v1/settings/marketing/events',
        operationId: 'marketingCreate',
        tags: ['MarketingEvent'],
        summary: 'Create new Marketing Event',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'name',
                            description: 'Marketing Event Name',
                            type: 'string',
                            example: 'Marketing Name'
                        ),
                        new OA\Property(
                            property: 'description',
                            description: 'Write marketing event description here',
                            type: 'string',
                            example: 'Marketing event Description',
                        ),
                        new OA\Property(
                            property: 'date',
                            description: 'Date',
                            type: 'string',
                            format: 'date',
                            example: '2025-05-01'
                        ),
                    ],
                    required: ['name', 'description', 'date'],
                )
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
                            ref: '#/components/schemas/Event'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error'
            ),
        ]
    )]
    public function store() {}

    #[OA\Get(
        path: '/api/v1/settings/marketing/events/{id}',
        operationId: 'marketingRead',
        tags: ['MarketingEvent'],
        summary: 'Get Marketing based on id',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Marketing Id',
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
                            type: 'Object',
                            ref: '#/components/schemas/Event'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Marketing not found'
            ),
        ]
    )]
    public function show() {}

    #[OA\Put(
        path: '/api/v1/settings/marketing/events/{id}',
        operationId: 'marketingUpdate',
        tags: ['MarketingEvent'],
        summary: 'Update existing Marketing Event',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Marketing Id',
                required: true,
                in: 'path',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'name',
                            description: 'Marketing event Name',
                            type: 'string',
                            example: 'Marketing Name'
                        ),
                        new OA\Property(
                            property: 'description',
                            description: 'Write marketing event description here',
                            type: 'string',
                            example: 'Marketing Description',
                        ),
                        new OA\Property(
                            property: 'date',
                            description: 'Date',
                            type: 'string',
                            format: 'date',
                            example: '2025-05-01'
                        ),
                    ],
                    required: ['name', 'description', 'date'],
                )
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
                            ref: '#/components/schemas/Event'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error'
            ),
        ]
    )]
    public function update() {}

    #[OA\Delete(
        path: '/api/v1/settings/marketing/events/{id}',
        operationId: 'marketingDelete',
        tags: ['MarketingEvent'],
        summary: 'Delete existing Marketing event',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Marketing Id',
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
                            property: 'message',
                            type: 'string',
                            example: 'Marketing event deleted successfully'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Marketing event not found'
            ),
        ]
    )]
    public function destroy() {}

    #[OA\Post(
        path: '/api/v1/settings/marketing/events/mass-destroy',
        operationId: 'marketingMassDestroy',
        tags: ['MarketingEvent'],
        summary: 'Delete multiple records of Marketing Event',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'indices',
                        type: 'array',
                        items: new OA\Items(
                            type: 'integer',
                            example: 1
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
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Event')
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
