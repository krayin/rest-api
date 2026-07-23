<?php

namespace Webkul\RestApi\Docs\Controllers\Lead;

use OpenApi\Attributes as OA;

class TagController
{
    #[OA\Delete(
        path: '/api/v1/leads/{id}/tags',
        operationId: 'dettachTags',
        tags: ['Leads'],
        summary: 'Dettached tags to the Leads',
        description: 'Dettached tags to the Leads',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Lead ID',
                required: true,
                in: 'path',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'tag_id',
                        type: 'integer',
                        description: 'Tag Id',
                        example: 1
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
                            property: 'message',
                            type: 'string',
                            example: 'Tag attached successfully.'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function detach() {}

    #[OA\Post(
        path: '/api/v1/leads/{id}/tags',
        operationId: 'attachTags',
        tags: ['Leads'],
        summary: 'Attached tags to the Leads',
        description: 'Attached tags to the Leads',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Lead ID',
                required: true,
                in: 'path',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'tag_id',
                        type: 'integer',
                        description: 'Tag Id',
                        example: 1
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
                            property: 'message',
                            type: 'string',
                            example: 'Tag attached successfully.'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function attach() {}
}
