<?php

namespace Webkul\RestApi\Docs\Controllers\Settings;

use OpenApi\Attributes as OA;

class RoleController
{
    #[OA\Get(
        path: '/api/v1/settings/roles',
        operationId: 'roleList',
        tags: ['Role'],
        summary: 'Get list of roles',
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
                            items: new OA\Items(ref: '#/components/schemas/Role')
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
        path: '/api/v1/settings/roles/{id}',
        operationId: 'roleFetch',
        tags: ['Role'],
        summary: 'Get role details',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Role ID',
                required: true,
                in: 'path',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(ref: '#/components/schemas/Role')
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    public function show() {}

    #[OA\Post(
        path: '/api/v1/settings/roles',
        operationId: 'roleCreate',
        tags: ['Role'],
        summary: 'Create new role',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Role details',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'name',
                        type: 'string',
                        description: 'Role name',
                        example: 'Sales Manager'
                    ),
                    new OA\Property(
                        property: 'description',
                        type: 'string',
                        description: 'Role Description',
                        example: 'Sales Manager'
                    ),
                    new OA\Property(
                        property: 'permission_type',
                        type: 'string',
                        description: 'Role type permission',
                        example: 'custom'
                    ),
                    new OA\Property(
                        property: 'permissions',
                        type: 'array',
                        description: 'List of permissions',
                        items: new OA\Items(
                            type: 'string',
                            example: 'dashboard'
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
                            ref: '#/components/schemas/Role'
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
        path: '/api/v1/settings/roles/{id}',
        operationId: 'roleUpdate',
        tags: ['Role'],
        summary: 'Update existing role',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Role ID',
                required: true,
                in: 'path',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Role details',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'name',
                        type: 'string',
                        description: 'Role name',
                        example: 'Sales Manager'
                    ),
                    new OA\Property(
                        property: 'description',
                        type: 'string',
                        description: 'Role Description',
                        example: 'Sales Manager'
                    ),
                    new OA\Property(
                        property: 'permission_type',
                        type: 'string',
                        description: 'Role type permission',
                        example: 'custom'
                    ),
                    new OA\Property(
                        property: 'permissions',
                        type: 'array',
                        description: 'List of permissions',
                        items: new OA\Items(
                            type: 'string',
                            example: 'dashboard'
                        )
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(ref: '#/components/schemas/Role')
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    public function update() {}

    #[OA\Delete(
        path: '/api/v1/settings/roles/{id}',
        operationId: 'roleDelete',
        tags: ['Role'],
        summary: 'Delete existing role',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Role ID',
                required: true,
                in: 'path',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(ref: '#/components/schemas/Role')
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    public function destroy() {}
}
