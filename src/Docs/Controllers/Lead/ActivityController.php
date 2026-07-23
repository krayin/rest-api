<?php

namespace Webkul\RestApi\Docs\Controllers\Lead;

use OpenApi\Attributes as OA;

class ActivityController
{
    #[OA\Get(
        path: '/api/v1/leads/{id}/activities',
        operationId: 'getLeadActivities',
        tags: ['Leads'],
        summary: 'Get list of leads',
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
