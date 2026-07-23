<?php

namespace Webkul\RestApi\Docs\Controllers\Settings\Marketing;

use OpenApi\Attributes as OA;

class CampaignController
{
    #[OA\Get(
        path: '/api/v1/settings/marketing/campaigns',
        operationId: 'campaignList',
        tags: ['MarketingCampaign'],
        summary: 'Get list of marketing Campaigns',
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
                            items: new OA\Items(ref: '#/components/schemas/Campaign')
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
        path: '/api/v1/settings/marketing/campaigns',
        operationId: 'campaignCreate',
        tags: ['MarketingCampaign'],
        summary: 'Create new Marketing Campaign',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    required: ['name', 'subject', 'marketing_event_id', 'marketing_template_id'],
                    properties: [
                        new OA\Property(
                            property: 'name',
                            description: 'Name of the Campaign',
                            type: 'string',
                            example: 'Spring Sale'
                        ),
                        new OA\Property(
                            property: 'subject',
                            description: 'Subject of the Campaign',
                            type: 'string',
                            example: 'Get Ready for Our Biggest Sale of the Year!'
                        ),
                        new OA\Property(
                            property: 'status',
                            description: 'Status',
                            type: 'string',
                            example: '1'
                        ),
                        new OA\Property(
                            property: 'marketing_template_id',
                            description: 'Marketing Email Template Id',
                            type: 'string',
                            example: '1'
                        ),
                        new OA\Property(
                            property: 'marketing_event_id',
                            description: 'Marketing Event Id',
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
                            property: 'data',
                            ref: '#/components/schemas/Campaign',
                            type: 'object'
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
        path: '/api/v1/settings/marketing/campaigns/{id}',
        operationId: 'campaignRead',
        tags: ['MarketingCampaign'],
        summary: 'Get marketing campaigns based on id',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Campaign Id',
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
                            ref: '#/components/schemas/Campaign'
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
        path: '/api/v1/settings/marketing/campaigns/{id}',
        operationId: 'campaignUpdate',
        tags: ['MarketingCampaign'],
        summary: 'Update existing Marketing Campaign',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Marketing Campaign Id',
                required: true,
                in: 'path',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    required: ['name', 'subject', 'marketing_event_id', 'marketing_template_id'],
                    properties: [
                        new OA\Property(
                            property: 'name',
                            description: 'Name of the Campaign',
                            type: 'string',
                            example: 'Spring Sale'
                        ),
                        new OA\Property(
                            property: 'subject',
                            description: 'Subject of the Campaign',
                            type: 'string',
                            example: 'Get Ready for Our Biggest Sale of the Year!'
                        ),
                        new OA\Property(
                            property: 'status',
                            description: 'Status',
                            type: 'string',
                            example: '1'
                        ),
                        new OA\Property(
                            property: 'marketing_template_id',
                            description: 'Marketing Email Template Id',
                            type: 'string',
                            example: '1'
                        ),
                        new OA\Property(
                            property: 'marketing_event_id',
                            description: 'Marketing Event Id',
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
                            property: 'data',
                            ref: '#/components/schemas/Campaign',
                            type: 'object'
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
        path: '/api/v1/settings/marketing/campaigns/{id}',
        operationId: 'campaignDelete',
        tags: ['MarketingCampaign'],
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
                            example: 'Marketing campaign deleted successfully'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Marketing campaign not found'
            ),
        ]
    )]
    public function destroy() {}

    #[OA\Post(
        path: '/api/v1/settings/marketing/campaigns/mass-destroy',
        operationId: 'campaignMassDestroy',
        tags: ['MarketingCampaign'],
        summary: 'Delete multiple records of Marketing Campaign',
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
                            items: new OA\Items(ref: '#/components/schemas/Campaign')
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
