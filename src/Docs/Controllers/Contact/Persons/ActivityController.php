<?php

namespace Webkul\RestApi\Docs\Controllers\Contact\Persons;

use OpenApi\Attributes as OA;

class ActivityController
{
    #[OA\Get(
        path: '/api/v1/contacts/persons/{id}/activities',
        operationId: 'getPersonActivities',
        tags: ['Contacts'],
        summary: 'Get list of persons',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Person ID',
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
}
