<?php

namespace Webkul\RestApi\Docs\Controllers\Settings;

use OpenApi\Attributes as OA;

class AttributeController
{
    #[OA\Get(
        path: '/api/v1/settings/attributes',
        operationId: 'attributeList',
        tags: ['Attribute'],
        summary: 'Get list of Attribute',
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
                            items: new OA\Items(ref: '#/components/schemas/Attribute')
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
        path: '/api/v1/settings/attributes/{id}',
        operationId: 'attributeShow',
        tags: ['Attribute'],
        summary: 'Get Attribute',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Attribute Id',
                required: true,
                in: 'path',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(ref: '#/components/schemas/Attribute')
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    public function show() {}

    #[OA\Get(
        path: '/api/v1/settings/attributes/lookup/{lookup}',
        operationId: 'attributeLookup',
        tags: ['Attribute'],
        summary: 'Search attribute lookup results',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'lookup',
                description: 'Attribute Lookup',
                required: true,
                in: 'path',
                schema: new OA\Schema(type: 'string')
            ),
            new OA\Parameter(
                name: 'query',
                description: 'Query',
                required: true,
                in: 'query',
                schema: new OA\Schema(type: 'string')
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
                            items: new OA\Items(ref: '#/components/schemas/Attribute')
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
    public function lookup() {}

    #[OA\Get(
        path: '/api/v1/settings/attributes/lookup-entity/{lookup}',
        operationId: 'attributeEntityLookup',
        tags: ['Attribute'],
        summary: 'Search attribute lookup results',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'lookup',
                description: 'Attribute Lookup',
                required: true,
                in: 'path',
                schema: new OA\Schema(type: 'string')
            ),
            new OA\Parameter(
                name: 'query',
                description: 'query',
                required: true,
                in: 'query',
                schema: new OA\Schema(type: 'string')
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
                            type: 'file'
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
    public function download() {}

    #[OA\Post(
        path: '/api/v1/settings/attributes',
        operationId: 'attributeCreate',
        tags: ['Attribute'],
        summary: 'Create new Attribute',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'code',
                            description: 'Code',
                            type: 'string',
                            example: 'tax'
                        ),
                        new OA\Property(
                            property: 'name',
                            description: 'Name of the Attribute',
                            type: 'string',
                            example: 'Tax'
                        ),
                        new OA\Property(
                            property: 'type',
                            description: 'Type of the Attribute',
                            type: 'string',
                            example: 'select'
                        ),
                        new OA\Property(
                            property: 'lookup_type',
                            description: 'Lookup Type',
                            type: 'string',
                            example: 'lead_types'
                        ),
                        new OA\Property(
                            property: 'entity_type',
                            description: 'Entity Type',
                            type: 'string',
                            example: 'persons'
                        ),
                        new OA\Property(
                            property: 'sort_order',
                            description: 'Order of the Attribute',
                            type: 'string',
                            example: '1'
                        ),
                        new OA\Property(
                            property: 'validation',
                            description: 'Validation',
                            type: 'string',
                            example: ''
                        ),
                        new OA\Property(
                            property: 'is_required',
                            description: 'Is Required',
                            type: 'string',
                            example: '1'
                        ),
                        new OA\Property(
                            property: 'is_unique',
                            description: 'Is Unique',
                            type: 'string',
                            example: '1'
                        ),
                        new OA\Property(
                            property: 'quick_add',
                            description: 'Quick Add',
                            type: 'string',
                            example: '1'
                        ),
                        new OA\Property(
                            property: 'is_user_defined',
                            description: 'Is User defined Attribute',
                            type: 'string',
                            example: '1'
                        ),
                        new OA\Property(
                            property: 'option_type',
                            description: 'Options Type',
                            type: 'string',
                            example: 'options'
                        ),
                    ]
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
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Attribute')
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
    public function store() {}

    #[OA\Put(
        path: '/api/v1/settings/attributes/{id}',
        operationId: 'attributeUpdate',
        tags: ['Attribute'],
        summary: 'Update an existing Attribute',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Attribute Id',
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
                            property: 'code',
                            description: 'Code',
                            type: 'string',
                            example: 'tax'
                        ),
                        new OA\Property(
                            property: 'name',
                            description: 'Name of the Attribute',
                            type: 'string',
                            example: 'Tax'
                        ),
                        new OA\Property(
                            property: 'type',
                            description: 'Type of the Attribute',
                            type: 'string',
                            example: 'select'
                        ),
                        new OA\Property(
                            property: 'lookup_type',
                            description: 'Lookup Type',
                            type: 'string',
                            example: 'lead_types'
                        ),
                        new OA\Property(
                            property: 'entity_type',
                            description: 'Entity Type',
                            type: 'string',
                            example: 'persons'
                        ),
                        new OA\Property(
                            property: 'sort_order',
                            description: 'Order of the Attribute',
                            type: 'string',
                            example: '1'
                        ),
                        new OA\Property(
                            property: 'validation',
                            description: 'Validation',
                            type: 'string',
                            example: ''
                        ),
                        new OA\Property(
                            property: 'is_required',
                            description: 'Is Required',
                            type: 'string',
                            example: '1'
                        ),
                        new OA\Property(
                            property: 'is_unique',
                            description: 'Is Unique',
                            type: 'string',
                            example: '1'
                        ),
                        new OA\Property(
                            property: 'quick_add',
                            description: 'Quick Add',
                            type: 'string',
                            example: '1'
                        ),
                        new OA\Property(
                            property: 'is_user_defined',
                            description: 'Is User defined Attribute',
                            type: 'string',
                            example: '1'
                        ),
                        new OA\Property(
                            property: 'option_type',
                            description: 'Options Type',
                            type: 'string',
                            example: 'options'
                        ),
                    ]
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
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Attribute')
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
    public function update() {}

    #[OA\Delete(
        path: '/api/v1/settings/attributes/{id}',
        operationId: 'attributeDelete',
        tags: ['Attribute'],
        summary: 'Delete one record of Attribute',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Attribute Id',
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
                            items: new OA\Items(ref: '#/components/schemas/Attribute')
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
    public function destroy() {}

    #[OA\Post(
        path: '/api/v1/settings/attributes/mass-destroy',
        operationId: 'attributeMassDestroy',
        tags: ['Attribute'],
        summary: 'Delete multiple records of Attribute',
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
                            items: new OA\Items(ref: '#/components/schemas/Attribute')
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
