<?php

namespace Webkul\RestApi\Docs\Controllers\Authentication;

use OpenApi\Attributes as OA;

class AuthController
{
    #[OA\Post(
        path: '/api/v1/login',
        operationId: 'adminLogin',
        tags: ['Authentication'],
        summary: 'Login admin user',
        description: 'Login admin user',
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'email',
                            type: 'string',
                            format: 'email',
                            example: 'admin@example.com'
                        ),
                        new OA\Property(
                            property: 'password',
                            type: 'string',
                            format: 'password',
                            example: 'admin123'
                        ),
                        new OA\Property(
                            property: 'device_name',
                            type: 'string',
                            example: 'android'
                        ),
                    ],
                    required: ['email', 'password', 'device_name']
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
                            example: 'Logged in successfully.'
                        ),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            ref: '#/components/schemas/User'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Bad Request',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Invalid Email or Password'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function login() {}
}
