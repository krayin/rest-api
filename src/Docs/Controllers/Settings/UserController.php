<?php

namespace Webkul\RestApi\Docs\Controllers\Settings;

use OpenApi\Attributes as OA;

class UserController
{
    #[OA\Get(
        path: '/api/v1/settings/users',
        operationId: 'userList',
        tags: ['User'],
        summary: 'Get list of users',
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
                            items: new OA\Items(ref: '#/components/schemas/User')
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
        path: '/api/v1/settings/users/{id}',
        operationId: 'userFetch',
        tags: ['User'],
        summary: 'Get user details',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'User ID',
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
                            ref: '#/components/schemas/User'
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
        path: '/api/v1/settings/users',
        operationId: 'userCreate',
        tags: ['User'],
        summary: 'Create new user',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'name',
                            description: 'Name',
                            type: 'string',
                            example: 'John Doe'
                        ),
                        new OA\Property(
                            property: 'email',
                            description: 'Email',
                            type: 'string',
                            example: 'john@doe.com'
                        ),
                        new OA\Property(
                            property: 'status',
                            description: 'Status',
                            type: 'string',
                            example: '1'
                        ),
                        new OA\Property(
                            property: 'password',
                            description: 'Password',
                            type: 'string',
                            example: 'admin123'
                        ),
                        new OA\Property(
                            property: 'confirm_password',
                            description: 'confirm_password',
                            type: 'string',
                            example: 'admin123'
                        ),
                        new OA\Property(
                            property: 'role_id',
                            description: 'Role ID',
                            type: 'string',
                            example: '1'
                        ),
                        new OA\Property(
                            property: 'groups',
                            type: 'array',
                            description: 'List of group ids',
                            items: new OA\Items(
                                type: 'string',
                                example: '1'
                            )
                        ),
                        new OA\Property(
                            property: 'view_permission',
                            description: 'View Permission',
                            type: 'string',
                            example: 'global',
                            enum: ['global', 'group', 'individual']
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
                            items: new OA\Items(ref: '#/components/schemas/User')
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

    #[OA\Get(
        path: '/api/v1/settings/users/search',
        operationId: 'searchUser',
        tags: ['User'],
        summary: 'search the User',
        description: 'search the user heres the admin is the search keyword',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'search',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'name:admin;')
            ),
            new OA\Parameter(
                name: 'searchFields',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'name:like;')
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
                            ref: '#/components/schemas/User'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function search() {}

    #[OA\Put(
        path: '/api/v1/settings/users/{id}',
        operationId: 'userUpdate',
        tags: ['User'],
        summary: 'Update existing user.',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'User Id',
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
                            description: 'Name',
                            type: 'string',
                            example: 'John Doe'
                        ),
                        new OA\Property(
                            property: 'email',
                            description: 'Email',
                            type: 'string',
                            example: 'john@doe.com'
                        ),
                        new OA\Property(
                            property: 'status',
                            description: 'Status',
                            type: 'string',
                            example: '1'
                        ),
                        new OA\Property(
                            property: 'password',
                            description: 'Password',
                            type: 'string',
                            example: 'admin123'
                        ),
                        new OA\Property(
                            property: 'confirm_password',
                            description: 'confirm_password',
                            type: 'string',
                            example: 'admin123'
                        ),
                        new OA\Property(
                            property: 'role_id',
                            description: 'Role ID',
                            type: 'string',
                            example: '1'
                        ),
                        new OA\Property(
                            property: 'groups',
                            type: 'array',
                            description: 'List of group ids',
                            items: new OA\Items(
                                type: 'string',
                                example: '1'
                            )
                        ),
                        new OA\Property(
                            property: 'view_permission',
                            description: 'View Permission',
                            type: 'string',
                            example: 'global',
                            enum: ['global', 'group', 'individual']
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
                            items: new OA\Items(ref: '#/components/schemas/User')
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
        path: '/api/v1/settings/users/{id}',
        operationId: 'deleteUser',
        tags: ['User'],
        summary: 'Delete the Users',
        description: 'Delete the Users',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'User Id',
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
                            example: 'User deleted successfully.'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function destroy() {}

    #[OA\Post(
        path: '/api/v1/settings/users/mass-update',
        operationId: 'massUpdateUser',
        tags: ['User'],
        summary: 'Mass Update the Users',
        description: 'Mass Update the Users',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'indices',
                            description: 'User Ids',
                            type: 'array',
                            items: new OA\Items(
                                type: 'integer',
                                example: '1'
                            )
                        ),
                        new OA\Property(
                            property: 'value',
                            description: 'Status Value',
                            type: 'string',
                            example: '1'
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
                            property: 'message',
                            type: 'string',
                            example: 'User updated successfully.'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function massUpdate() {}

    #[OA\Post(
        path: '/api/v1/settings/users/mass-destroy',
        operationId: 'massDestroyUser',
        tags: ['User'],
        summary: 'Mass Delete the Users',
        description: 'Mass Delete the Users',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'indices',
                            description: 'User Ids',
                            type: 'array',
                            items: new OA\Items(
                                type: 'integer',
                                example: '1'
                            )
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
                            property: 'message',
                            type: 'string',
                            example: 'User deleted successfully.'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function massDestroy() {}
}
