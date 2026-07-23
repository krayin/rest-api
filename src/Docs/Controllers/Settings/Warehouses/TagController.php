<?php

namespace Webkul\RestApi\Docs\Controllers\Settings\Warehouses;

use OpenApi\Attributes as OA;

class TagController
{
    #[OA\Delete(
        path: '/api/v1/settings/warehouses/{id}/tags',
        operationId: 'dettachWarehouseTags',
        tags: ['Warehouse'],
        summary: 'Dettached tags to the Warehouses',
        description: 'Dettached tags to the Warehouses',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Warehouse Id',
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
        path: '/api/v1/settings/warehouses/{id}/tags',
        operationId: 'attachWarehouseTags',
        tags: ['Warehouse'],
        summary: 'Attached tags to the Warehouses',
        description: 'Attached tags to the Warehouses',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Warehouse Id',
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
