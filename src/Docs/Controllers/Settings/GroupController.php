<?php

namespace Webkul\RestApi\Docs\Controllers\Settings;

use OpenApi\Attributes as OA;

class GroupController
{
    #[OA\Get(
        path: '/api/v1/settings/groups',
        operationId: 'groupList',
        tags: ['Group'],
        summary: 'Get list of Group',
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
                            items: new OA\Items(ref: '#/components/schemas/Group')
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
        path: '/api/v1/settings/groups',
        operationId: 'groupCreate',
        tags: ['Group'],
        summary: 'Create new Group',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'name',
                            description: 'Group Name',
                            type: 'string',
                            example: 'Group Name'
                        ),
                        new OA\Property(
                            property: 'description',
                            description: 'Write group description here',
                            type: 'string',
                            example: 'Group Description'
                        ),
                    ],
                    required: ['name', 'description']
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
                            ref: '#/components/schemas/Group'
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
        path: '/api/v1/settings/groups/{id}',
        operationId: 'groupRead',
        tags: ['Group'],
        summary: 'Get Group based on id',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Group Id',
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
                            ref: '#/components/schemas/Group'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Group not found'
            ),
        ]
    )]
    public function show() {}

    #[OA\Put(
        path: '/api/v1/settings/groups/{id}',
        operationId: 'groupUpdate',
        tags: ['Group'],
        summary: 'Update existing Group',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Group Id',
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
                            description: 'Group Name',
                            type: 'string',
                            example: 'Group Name'
                        ),
                        new OA\Property(
                            property: 'description',
                            description: 'Write group description here',
                            type: 'string',
                            example: 'Group Description'
                        ),
                    ],
                    required: ['name', 'description']
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
                            ref: '#/components/schemas/Group'
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
        path: '/api/v1/settings/groups/{id}',
        operationId: 'groupDelete',
        tags: ['Group'],
        summary: 'Delete existing Group',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Group Id',
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
                            example: 'Group deleted successfully'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Group not found'
            ),
        ]
    )]
    public function destroy() {}
}
