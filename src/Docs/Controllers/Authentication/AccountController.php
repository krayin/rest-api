<?php

namespace Webkul\RestApi\Docs\Controllers\Authentication;

use OpenApi\Attributes as OA;

class AccountController
{
    #[OA\Get(
        path: '/api/v1/get',
        operationId: 'getAdminUser',
        tags: ['Authentication'],
        summary: "Get logged in admin user's details",
        description: "Get logged in admin user's details",
        security: [['sanctum_admin' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            ref: '#/components/schemas/User'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated'
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden'
            ),
        ]
    )]
    public function get() {}

    #[OA\Post(
        path: '/api/v1/update',
        operationId: 'updateAdminUser',
        tags: ['Authentication'],
        summary: "Update admin user's profile",
        description: "Update admin user's profile",
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: '_method',
                            type: 'string',
                            example: 'PUT'
                        ),
                        new OA\Property(
                            property: 'name',
                            type: 'string',
                            example: 'Kim Thomson'
                        ),
                        new OA\Property(
                            property: 'email',
                            type: 'string',
                            example: 'example@example.com'
                        ),
                        new OA\Property(
                            property: 'image',
                            type: 'file',
                        ),
                        new OA\Property(
                            property: 'password',
                            type: 'string',
                            example: 'admin123'
                        ),
                        new OA\Property(
                            property: 'password_confirmation',
                            type: 'string',
                            example: 'admin123'
                        ),
                        new OA\Property(
                            property: 'current_password',
                            type: 'string',
                            example: 'admin123'
                        ),
                    ],
                    required: ['name', 'current_password']
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Account updated successfully.'),
                        new OA\Property(property: 'data', type: 'object', ref: '#/components/schemas/User'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated'
            ),
            new OA\Response(
                response: 422,
                description: 'Error: Unprocessable Content',
                content: new OA\JsonContent(
                    examples: [
                        new OA\Examples(example: 'result', value: ['message' => 'The name field is required. (and 1 more error)'], summary: 'An result object.'),
                    ]
                )
            ),
        ]
    )]
    public function update() {}

    #[OA\Delete(
        path: '/api/v1/logout',
        operationId: 'logoutAdminUser',
        tags: ['Authentication'],
        summary: 'Logout admin user',
        description: 'Logout admin user',
        security: [['sanctum_admin' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Logged out successfully.'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated'
            ),
        ]
    )]
    public function logout() {}
}
