<?php

namespace Webkul\RestApi\Docs\Controllers\Contact\Organizations;

use OpenApi\Attributes as OA;

class OrganizationController
{
    #[OA\Get(
        path: '/api/v1/contacts/organizations',
        operationId: 'organizationList',
        tags: ['Contacts'],
        summary: 'Get list of organizations',
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
                            items: new OA\Items(ref: '#/components/schemas/Organization')
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
        path: '/api/v1/contacts/organizations/{id}',
        operationId: 'organizationShow',
        tags: ['Contacts'],
        summary: 'Get organization by id',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'Organization ID',
                required: true,
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
                            ref: '#/components/schemas/Organization'
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
        path: '/api/v1/contacts/organizations',
        summary: 'Store a new organization',
        description: 'Create a new organization with the provided details',
        operationId: 'storeOrganization',
        security: [['sanctum_admin' => []]],
        tags: ['Contacts'],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Organization details',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'name',
                        type: 'string',
                        description: 'Organization name',
                        example: 'Amazon'
                    ),
                    new OA\Property(
                        property: 'address',
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'city', type: 'string', example: 'Los Angeles'),
                            new OA\Property(property: 'state', type: 'string', example: 'CA'),
                            new OA\Property(property: 'address', type: 'string', example: '123 Main St'),
                            new OA\Property(property: 'country', type: 'string', example: 'US'),
                            new OA\Property(property: 'postcode', type: 'string', example: '201309'),
                        ]
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Organization created successfully',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Organization'
                )
            ),
        ]
    )]
    public function store() {}

    #[OA\Put(
        path: '/api/v1/contacts/organizations/{id}',
        summary: 'Update an organization',
        description: 'Update an organization with the provided details',
        operationId: 'updateOrganization',
        security: [['sanctum_admin' => []]],
        tags: ['Contacts'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'Organization ID',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Organization details',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'name',
                        type: 'string',
                        description: 'Organization name',
                        example: 'Amazon'
                    ),
                    new OA\Property(
                        property: 'address',
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'city', type: 'string', example: 'Los Angeles'),
                            new OA\Property(property: 'state', type: 'string', example: 'CA'),
                            new OA\Property(property: 'address', type: 'string', example: '123 Main St'),
                            new OA\Property(property: 'country', type: 'string', example: 'US'),
                            new OA\Property(property: 'postcode', type: 'string', example: '201309'),
                        ]
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
                            ref: '#/components/schemas/Organization'
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
        path: '/api/v1/contacts/organizations/{id}',
        summary: 'Delete an organization',
        description: 'Delete an organization by id',
        operationId: 'deleteOrganization',
        security: [['sanctum_admin' => []]],
        tags: ['Contacts'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'Organization ID',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Organization deleted successfully'
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    public function destroy() {}

    #[OA\Post(
        path: '/api/v1/contacts/organizations/mass-destroy',
        summary: 'Delete multiple organizations',
        description: 'Delete multiple organizations by id',
        operationId: 'massDeleteOrganization',
        security: [['sanctum_admin' => []]],
        tags: ['Contacts'],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Organization IDs',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'indices',
                        type: 'array',
                        description: 'Organization IDs',
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
                response: 204,
                description: 'Organizations deleted successfully'
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    public function massDestroy() {}
}
