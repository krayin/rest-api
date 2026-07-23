<?php

namespace Webkul\RestApi\Docs\Controllers\Lead;

use OpenApi\Attributes as OA;

class EmailController
{
    #[OA\Post(
        path: '/api/v1/leads/{id}/emails',
        operationId: 'sendEmailToLead',
        tags: ['Leads'],
        summary: 'Send an Email to a Lead',
        description: 'Send an Email to a Lead with attachments',
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
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'type',
                            type: 'string',
                            description: 'Type of the email',
                            example: 'email'
                        ),
                        new OA\Property(
                            property: 'lead_id',
                            type: 'integer',
                            description: 'Lead ID associated with the email',
                            example: 1
                        ),
                        new OA\Property(
                            property: 'reply_to[]',
                            type: 'array',
                            description: 'List of email addresses to reply to',
                            items: new OA\Items(
                                type: 'string',
                                format: 'email',
                                example: 'test@mail.com'
                            )
                        ),
                        new OA\Property(
                            property: 'subject',
                            type: 'string',
                            description: 'Subject of the email',
                            example: 'subject'
                        ),
                        new OA\Property(
                            property: 'reply',
                            type: 'string',
                            description: 'Message content of the email',
                            example: 'message'
                        ),
                        new OA\Property(
                            property: 'attachments[]',
                            type: 'array',
                            description: 'Attachments of the email',
                            items: new OA\Items(
                                type: 'file',
                            )
                        ),
                    ],
                    required: ['type', 'lead_id', 'subject', 'reply']
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Email sent successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Email sent successfully.'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated'
            ),
        ]
    )]
    public function store() {}

    #[OA\Delete(
        path: '/api/v1/leads/{id}/emails',
        operationId: 'detachEmailFromLead',
        tags: ['Leads'],
        summary: 'Detach an Email from a Lead',
        description: 'Delete an association of an email with a specific lead.',
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
                type: 'object',
                properties: [
                    new OA\Property(
                        property: 'email_id',
                        type: 'integer',
                        description: 'ID of the email to be detached',
                        example: 1
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Email detached successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Email detached successfully.'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated'
            ),
            new OA\Response(
                response: 404,
                description: 'Lead or Email not found'
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error'
            ),
        ]
    )]
    public function detach() {}
}
