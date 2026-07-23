<?php

namespace Webkul\RestApi\Docs\Controllers\Lead;

use OpenApi\Attributes as OA;

class QuoteController
{
    #[OA\Delete(
        path: '/api/v1/leads/{id}/quotes/{quote_id}',
        operationId: 'deleteQuoteFromLead',
        tags: ['Leads'],
        summary: 'Delete a Quote from a Lead',
        description: 'Remove a specific quote associated with a lead.',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'Lead ID',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'quote_id',
                in: 'path',
                description: 'Quote ID',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Quote deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Quote deleted successfully.'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Quote not found'
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated'
            ),
        ]
    )]
    public function destroy() {}
}
