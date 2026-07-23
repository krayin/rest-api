<?php

namespace Webkul\RestApi\Docs\Controllers\Settings;

use OpenApi\Attributes as OA;

class PipelineController
{
    #[OA\Get(
        path: '/api/v1/settings/pipelines',
        operationId: 'getPipelines',
        tags: ['Pipeline'],
        summary: 'Get all pipelines',
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
                description: 'Pipelines fetched successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Pipeline')
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
        path: '/api/v1/settings/pipelines',
        operationId: 'createPipeline',
        tags: ['Pipeline'],
        summary: 'Create a new pipeline',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Pipeline details',
            content: new OA\JsonContent(
                required: ['name', 'stages'],
                properties: [
                    new OA\Property(
                        property: 'name',
                        type: 'string',
                        description: 'Name of the pipeline',
                        example: 'Test'
                    ),
                    new OA\Property(
                        property: 'rotten_days',
                        type: 'integer',
                        description: 'Number of days after which the pipeline is considered rotten',
                        example: 30
                    ),
                    new OA\Property(
                        property: 'is_default',
                        type: 'string',
                        description: 'Indicates if the pipeline is the default one',
                        example: 'on'
                    ),
                    new OA\Property(
                        property: 'stages',
                        type: 'array',
                        description: 'Ordered list of stages to create (send at least one).',
                        items: new OA\Items(
                            type: 'object',
                            required: ['name', 'code'],
                            properties: [
                                new OA\Property(property: 'name', type: 'string', description: 'Stage name', example: 'New'),
                                new OA\Property(property: 'code', type: 'string', description: 'Stage code (unique within the pipeline)', example: 'new'),
                                new OA\Property(property: 'sort_order', type: 'integer', description: 'Display order (defaults to the array index)', example: 1),
                                new OA\Property(property: 'probability', type: 'integer', description: 'Win probability 0-100 (defaults to 100)', example: 100),
                            ]
                        )
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Pipeline created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            ref: '#/components/schemas/Pipeline'
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
        path: '/api/v1/settings/pipelines/{id}',
        operationId: 'updatePipeline',
        tags: ['Pipeline'],
        summary: 'Update a pipeline',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID of the pipeline',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Pipeline details',
            content: new OA\JsonContent(
                required: ['name', 'stages'],
                properties: [
                    new OA\Property(
                        property: 'name',
                        type: 'string',
                        description: 'Name of the pipeline',
                        example: 'Test'
                    ),
                    new OA\Property(
                        property: 'rotten_days',
                        type: 'integer',
                        description: 'Number of days after which the pipeline is considered rotten',
                        example: 30
                    ),
                    new OA\Property(
                        property: 'is_default',
                        type: 'string',
                        description: 'Indicates if the pipeline is the default one',
                        example: 'on'
                    ),
                    new OA\Property(
                        property: 'stages',
                        type: 'array',
                        description: 'The desired set of stages (sync). Include a stage\'s "id" to update it in place; omit "id" to create a new stage; any existing stage not present in the list is deleted. Send at least one.',
                        items: new OA\Items(
                            type: 'object',
                            required: ['name', 'code'],
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', description: 'Existing stage id to update in place; omit to create a new stage', example: 7),
                                new OA\Property(property: 'name', type: 'string', description: 'Stage name', example: 'New'),
                                new OA\Property(property: 'code', type: 'string', description: 'Stage code (unique within the pipeline)', example: 'new'),
                                new OA\Property(property: 'sort_order', type: 'integer', description: 'Display order (defaults to the array index)', example: 1),
                                new OA\Property(property: 'probability', type: 'integer', description: 'Win probability 0-100 (defaults to 100)', example: 100),
                            ]
                        )
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Pipeline updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            ref: '#/components/schemas/Pipeline'
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
        path: '/api/v1/settings/pipelines/{id}',
        operationId: 'getPipeline',
        tags: ['Pipeline'],
        summary: 'Get a pipeline',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID of the pipeline',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Pipeline fetched successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            ref: '#/components/schemas/Pipeline'
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

    #[OA\Delete(
        path: '/api/v1/settings/pipelines/{id}',
        operationId: 'deletePipeline',
        tags: ['Pipeline'],
        summary: 'Delete a pipeline',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID of the pipeline',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Pipeline deleted successfully'
            ),
            new OA\Response(
                response: 400,
                description: 'Default pipeline cannot be deleted'
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    public function destroy() {}
}
