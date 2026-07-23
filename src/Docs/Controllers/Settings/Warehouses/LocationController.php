<?php

namespace Webkul\RestApi\Docs\Controllers\Settings\Warehouses;

use OpenApi\Attributes as OA;

class LocationController
{
    #[OA\Get(
        path: '/api/v1/settings/locations/search',
        operationId: 'searhLocations',
        tags: ['Warehouse'],
        summary: 'search the locations',
        description: 'search the locations',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'query',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'Los Angeles')
            ),
            new OA\Parameter(
                name: 'search',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'warehouse_id:1;name:Los Angeles')
            ),
            new OA\Parameter(
                name: 'searchFields',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'warehouse_id:=;name:like')
            ),
            new OA\Parameter(
                name: 'searchJoin',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'and')
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
                            ref: '#/components/schemas/WarehouseLocation'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function search() {}

    #[OA\Post(
        path: '/api/v1/settings/locations',
        operationId: 'storeLocations',
        tags: ['Warehouse'],
        summary: 'Store the locations',
        description: 'Store the locations',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'warehouse_id',
                        type: 'string',
                        description: 'The ID of the warehouse',
                        example: '1'
                    ),
                    new OA\Property(
                        property: 'name',
                        type: 'string',
                        description: 'The name of the location',
                        example: 'San Francisco'
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
                            type: 'object',
                            ref: '#/components/schemas/WarehouseLocation'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function store() {}

    #[OA\Put(
        path: '/api/v1/settings/locations/{id}',
        operationId: 'updateLocation',
        tags: ['Warehouse'],
        summary: 'Update the location',
        description: 'Update the location by ID',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID of the location to update',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'warehouse_id',
                        type: 'string',
                        description: 'The ID of the warehouse',
                        example: '1'
                    ),
                    new OA\Property(
                        property: 'name',
                        type: 'string',
                        description: 'The name of the location',
                        example: 'San Francisco'
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
                            type: 'object',
                            ref: '#/components/schemas/WarehouseLocation'
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
        path: '/api/v1/settings/locations/{id}',
        operationId: 'deleteLocation',
        tags: ['Warehouse'],
        summary: 'Delete a location',
        description: 'Delete a location by ID',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID of the location to delete',
                schema: new OA\Schema(type: 'integer', example: 1)
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
                            example: 'Location deleted successfully.'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Location not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'error',
                            type: 'string',
                            example: 'Location not found.'
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
    public function destroy() {}
}
