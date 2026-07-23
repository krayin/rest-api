<?php

namespace Webkul\RestApi\Docs\Controllers\Cofiguration;

use OpenApi\Attributes as OA;

class ConfigurationController
{
    #[OA\Post(
        path: '/api/v1/configuration',
        operationId: 'storeConfiguration',
        tags: ['Configuration'],
        summary: 'Create new Configuration',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'general',
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'general',
                                type: 'object',
                                properties: [
                                    new OA\Property(
                                        property: 'locale_settings',
                                        type: 'object',
                                        properties: [
                                            new OA\Property(
                                                property: 'locale',
                                                type: 'string',
                                                description: 'Locale',
                                                example: 'en'
                                            ),
                                        ]
                                    ),
                                ]
                            ),
                        ]
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
                            ref: '#/components/schemas/CoreConfig'
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
}
