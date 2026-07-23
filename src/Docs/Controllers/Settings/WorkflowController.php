<?php

namespace Webkul\RestApi\Docs\Controllers\Settings;

use OpenApi\Attributes as OA;

class WorkflowController
{
    #[OA\Get(
        path: '/api/v1/settings/workflows',
        operationId: 'workFlowList',
        tags: ['Workflow'],
        summary: 'Get list of Workflow',
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
                            items: new OA\Items(ref: '#/components/schemas/Workflow')
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
        path: '/api/v1/settings/workflows/{id}',
        operationId: 'showWorkflow',
        tags: ['Workflow'],
        summary: 'Get Workflow by ID',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of the Workflow',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(ref: '#/components/schemas/Workflow')
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    public function show() {}

    #[OA\Post(
        path: '/api/v1/settings/workflows',
        summary: 'Create new workflow.',
        description: 'Create new workflow.',
        operationId: 'storeWorkflow',
        tags: ['Workflow'],
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    schema: 'WorkflowSchema',
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'name',
                            type: 'string',
                            description: 'Name of the workflow',
                            example: 'Lorem Ipsum'
                        ),
                        new OA\Property(
                            property: 'description',
                            type: 'string',
                            description: 'Description of the workflow',
                            example: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s."
                        ),
                        new OA\Property(
                            property: 'entity_type',
                            type: 'string',
                            description: 'The entity type for the workflow',
                            example: 'leads'
                        ),
                        new OA\Property(
                            property: 'event',
                            type: 'string',
                            description: 'The event that triggers the workflow',
                            example: 'activity.update.after'
                        ),
                        new OA\Property(
                            property: 'condition_type',
                            type: 'string',
                            description: 'The condition type for the workflow',
                            example: 'and'
                        ),
                        new OA\Property(
                            property: 'conditions',
                            type: 'array',
                            description: 'Conditions',
                            items: new OA\Items(),
                            example: [[
                                'value' => ['call', 'meeting', 'lunch'],
                                'operator' => '{}',
                                'attribute' => 'type',
                                'attribute_type' => 'multiselect',
                            ]]
                        ),
                        new OA\Property(
                            property: 'actions',
                            type: 'array',
                            description: 'Actions',
                            items: new OA\Items(),
                            example: [['id' => 'send_email_to_participants', 'value' => '2']]
                        ),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(ref: '#/components/schemas/Workflow')
            ),
            new OA\Response(
                response: 400,
                description: 'Bad request'
            ),
        ]
    )]
    public function store() {}

    #[OA\Put(
        path: '/api/v1/settings/workflows/{id}',
        operationId: 'workFlowUpdate',
        tags: ['Workflow'],
        summary: 'Update an existing Workflow',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of the Workflow',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    schema: 'WorkflowSchema',
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'name',
                            type: 'string',
                            description: 'Name of the workflow',
                            example: 'Lorem Ipsum'
                        ),
                        new OA\Property(
                            property: 'description',
                            type: 'string',
                            description: 'Description of the workflow',
                            example: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s."
                        ),
                        new OA\Property(
                            property: 'entity_type',
                            type: 'string',
                            description: 'The entity type for the workflow',
                            example: 'leads'
                        ),
                        new OA\Property(
                            property: 'event',
                            type: 'string',
                            description: 'The event that triggers the workflow',
                            example: 'activity.update.after'
                        ),
                        new OA\Property(
                            property: 'condition_type',
                            type: 'string',
                            description: 'The condition type for the workflow',
                            example: 'and'
                        ),
                        new OA\Property(
                            property: 'conditions',
                            type: 'array',
                            description: 'Conditions',
                            items: new OA\Items(),
                            example: [[
                                'value' => ['call', 'meeting', 'lunch'],
                                'operator' => '{}',
                                'attribute' => 'type',
                                'attribute_type' => 'multiselect',
                            ]]
                        ),
                        new OA\Property(
                            property: 'actions',
                            type: 'array',
                            description: 'Actions',
                            items: new OA\Items(),
                            example: [['id' => 'send_email_to_participants', 'value' => '2']]
                        ),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(ref: '#/components/schemas/Workflow')
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    public function update() {}

    #[OA\Delete(
        path: '/api/v1/settings/workflows/{id}',
        operationId: 'workFlowDelete',
        tags: ['Workflow'],
        summary: 'Delete an existing Workflow',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of the Workflow',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(ref: '#/components/schemas/Workflow')
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    public function destroy() {}
}
