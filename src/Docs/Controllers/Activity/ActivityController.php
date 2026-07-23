<?php

namespace Webkul\RestApi\Docs\Controllers\Activity;

use OpenApi\Attributes as OA;

class ActivityController
{
    #[OA\Get(
        path: '/api/v1/activities',
        operationId: 'activityList',
        tags: ['Activity'],
        summary: 'Get list of activities',
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
                            items: new OA\Items(ref: '#/components/schemas/Activity')
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
        path: '/api/v1/activities/{id}',
        operationId: 'activityFetch',
        tags: ['Activity'],
        summary: 'Fetch activity',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'Activity Id',
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
                            ref: '#/components/schemas/Activity'
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
        path: '/api/v1/activities',
        operationId: 'activityStore',
        tags: ['Activity'],
        summary: 'Create activity',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: 'lead_id',
                                title: 'Lead ID',
                                description: 'ID of the Activity',
                                example: '1',
                                type: 'integer'
                            ),
                            new OA\Property(
                                property: 'title',
                                title: 'Title',
                                description: 'Title of the Activity',
                                example: 'Lorem Ipsum',
                                type: 'string'
                            ),
                            new OA\Property(
                                property: 'type',
                                title: 'Type',
                                description: 'Type of the Activity',
                                example: 'meeting',
                                enum: ['call', 'meeting', 'lunch', 'file', 'note'],
                                type: 'string'
                            ),
                            new OA\Property(
                                property: 'schedule_from',
                                title: 'Schedule From',
                                description: 'Schedule From of the Activity',
                                example: '2025-09-01 10:00:00',
                                type: 'string',
                                format: 'date-time'
                            ),
                            new OA\Property(
                                property: 'schedule_to',
                                title: 'Schedule To',
                                description: 'Schedule To of the Activity',
                                example: '2025-11-01 10:00:00',
                                type: 'string',
                                format: 'date-time'
                            ),
                            new OA\Property(
                                property: 'location',
                                title: 'Location',
                                description: 'Location of the Activity',
                                example: 'New York',
                                type: 'string'
                            ),
                            new OA\Property(
                                property: 'comment',
                                title: 'Comment',
                                description: 'Comment of the Activity',
                                example: 'Lorem Ipsum',
                                type: 'string'
                            ),
                            new OA\Property(
                                property: 'participants',
                                type: 'object',
                                properties: [
                                    new OA\Property(
                                        property: 'persons',
                                        type: 'array',
                                        items: new OA\Items(
                                            type: 'string',
                                            example: '1'
                                        ),
                                        description: 'List of person IDs'
                                    ),
                                    new OA\Property(
                                        property: 'users',
                                        type: 'array',
                                        items: new OA\Items(
                                            type: 'string',
                                            example: '1'
                                        ),
                                        description: 'List of user IDs'
                                    ),
                                ],
                                description: 'Participants. Either this {users, persons} object, or a flat array of user IDs (e.g. [1,2]) which are linked as users. Passing an empty value clears all participants. IDs are validated to exist.'
                            ),
                        ]
                    )
                ),
                new OA\MediaType(
                    mediaType: 'multipart/form-data',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: 'lead_id',
                                title: 'Lead ID',
                                description: 'ID of the Activity',
                                example: '1',
                                type: 'integer'
                            ),
                            new OA\Property(
                                property: 'title',
                                title: 'Title',
                                description: 'Title of the Activity',
                                example: 'Lorem Ipsum',
                                type: 'string'
                            ),
                            new OA\Property(
                                property: 'type',
                                title: 'Type',
                                description: 'Type of the Activity',
                                example: 'meeting',
                                enum: ['call', 'meeting', 'lunch', 'file', 'note'],
                                type: 'string'
                            ),
                            new OA\Property(
                                property: 'file',
                                type: 'file',
                                description: 'When you upload file type must be file.'
                            ),
                            new OA\Property(
                                property: 'schedule_from',
                                title: 'Schedule From',
                                description: 'Schedule From of the Activity',
                                example: '2025-09-01 10:00:00',
                                type: 'string',
                                format: 'date-time'
                            ),
                            new OA\Property(
                                property: 'schedule_to',
                                title: 'Schedule To',
                                description: 'Schedule To of the Activity',
                                example: '2025-11-01 10:00:00',
                                type: 'string',
                                format: 'date-time'
                            ),
                            new OA\Property(
                                property: 'location',
                                title: 'Location',
                                description: 'Location of the Activity',
                                example: 'New York',
                                type: 'string'
                            ),
                            new OA\Property(
                                property: 'comment',
                                title: 'Comment',
                                description: 'Comment of the Activity',
                                example: 'Lorem Ipsum',
                                type: 'string'
                            ),
                            new OA\Property(
                                property: 'participants[persons][]',
                                type: 'array',
                                items: new OA\Items(
                                    type: 'string',
                                    example: '1'
                                ),
                                description: 'List of person IDs'
                            ),
                            new OA\Property(
                                property: 'participants[users][]',
                                type: 'array',
                                items: new OA\Items(
                                    type: 'string',
                                    example: '1'
                                ),
                                description: 'List of user IDs'
                            ),
                        ]
                    )
                ),
            ]
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            ref: '#/components/schemas/Activity'
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
        path: '/api/v1/activities/{id}',
        operationId: 'activityUpdate',
        tags: ['Activity'],
        summary: 'Update activity',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'Activity Id',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'lead_id',
                            title: 'Lead ID',
                            description: 'ID of the Activity',
                            example: '1',
                            type: 'integer'
                        ),
                        new OA\Property(
                            property: 'title',
                            title: 'Title',
                            description: 'Title of the Activity',
                            example: 'Lorem Ipsum',
                            type: 'string'
                        ),
                        new OA\Property(
                            property: 'type',
                            title: 'Type',
                            description: 'Type of the Activity',
                            example: 'meeting',
                            enum: ['call', 'meeting', 'lunch', 'file', 'note'],
                            type: 'string'
                        ),
                        new OA\Property(
                            property: 'schedule_from',
                            title: 'Schedule From',
                            description: 'Schedule From of the Activity',
                            example: '2025-09-01 10:00:00',
                            type: 'string',
                            format: 'date-time'
                        ),
                        new OA\Property(
                            property: 'schedule_to',
                            title: 'Schedule To',
                            description: 'Schedule To of the Activity',
                            example: '2025-11-01 10:00:00',
                            type: 'string',
                            format: 'date-time'
                        ),
                        new OA\Property(
                            property: 'location',
                            title: 'Location',
                            description: 'Location of the Activity',
                            example: 'New York',
                            type: 'string'
                        ),
                        new OA\Property(
                            property: 'comment',
                            title: 'Comment',
                            description: 'Comment of the Activity',
                            example: 'Lorem Ipsum',
                            type: 'string'
                        ),
                        new OA\Property(
                            property: 'participants',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'persons',
                                    type: 'array',
                                    items: new OA\Items(
                                        type: 'string',
                                        example: '1'
                                    ),
                                    description: 'List of person IDs'
                                ),
                                new OA\Property(
                                    property: 'users',
                                    type: 'array',
                                    items: new OA\Items(
                                        type: 'string',
                                        example: '1'
                                    ),
                                    description: 'List of user IDs'
                                ),
                            ],
                            description: 'Participants. Either this {users, persons} object, or a flat array of user IDs (e.g. [1,2]) which are linked as users. Passing an empty value clears all participants. IDs are validated to exist.'
                        ),
                    ]
                )
            ),
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            ref: '#/components/schemas/Activity'
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

    #[OA\Get(
        path: '/api/v1/activities/file-download/{id}',
        operationId: 'activityDownload',
        tags: ['Activity'],
        summary: 'Download file',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'Activity File Id',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'File downloaded successfully',
                content: new OA\MediaType(
                    mediaType: 'application/octet-stream',
                    schema: new OA\Schema(
                        type: 'string',
                        format: 'binary'
                    )
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    public function download() {}

    #[OA\Delete(
        path: '/api/v1/activities/{id}',
        operationId: 'activityDelete',
        tags: ['Activity'],
        summary: 'Delete activity',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'Activity Id',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation'
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    public function destroy() {}

    #[OA\Post(
        path: '/api/v1/activities/mass-update',
        operationId: 'activityMassUpdate',
        tags: ['Activity'],
        summary: 'Mass update activities',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'indices',
                        type: 'array',
                        description: 'IDs of the Activities to be updated',
                        items: new OA\Items(
                            type: 'integer',
                            example: 1
                        )
                    ),
                    new OA\Property(
                        property: 'value',
                        type: 'string',
                        description: 'Value to be update',
                        example: '1'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation'
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    public function massUpdate() {}

    #[OA\Post(
        path: '/api/v1/activities/mass-destroy',
        operationId: 'activityMassDestroy',
        tags: ['Activity'],
        summary: 'Mass destroy activities',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'indices',
                        type: 'array',
                        description: 'IDs of the Activities to be deleted',
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
                description: 'Successful operation'
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    public function massDestroy() {}
}
