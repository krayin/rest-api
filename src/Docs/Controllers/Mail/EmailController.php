<?php

namespace Webkul\RestApi\Docs\Controllers\Mail;

use OpenApi\Attributes as OA;

class EmailController
{
    #[OA\Get(
        path: '/api/v1/mails',
        operationId: 'mailList',
        tags: ['Mail'],
        summary: 'Get list of mails',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'sort',
                description: 'Sort column',
                example: 'id',
                required: false,
                in: 'query',
                schema: new OA\Schema(type: 'string')
            ),
            new OA\Parameter(
                name: 'order',
                description: 'Sort order',
                required: false,
                in: 'query',
                schema: new OA\Schema(type: 'string', enum: ['desc', 'asc'])
            ),
            new OA\Parameter(
                name: 'page',
                description: 'Page number',
                required: false,
                in: 'query',
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'limit',
                description: 'Limit',
                in: 'query',
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
                            items: new OA\Items(ref: '#/components/schemas/Email')
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

    #[OA\Post(
        path: '/api/v1/mails',
        tags: ['Mail'],
        summary: 'Store an email',
        description: 'Store an email with the provided data',
        operationId: 'storeEmail',
        security: [['sanctum_admin' => []]],
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
                            property: 'is_draft',
                            type: 'boolean',
                            description: 'Indicates if the email is a draft or not',
                            example: true
                        ),
                        new OA\Property(
                            property: 'reply_to[]',
                            type: 'array',
                            description: 'List of email addresses to reply to',
                            items: new OA\Items(
                                type: 'string',
                                format: 'email',
                                example: 'example@mail.com'
                            )
                        ),
                        new OA\Property(
                            property: 'cc[]',
                            type: 'array',
                            description: 'List of email addresses to cc',
                            items: new OA\Items(
                                type: 'string',
                                format: 'email',
                                example: 'example@mail.com'
                            )
                        ),
                        new OA\Property(
                            property: 'bcc[]',
                            type: 'array',
                            description: 'List of email addresses to bcc',
                            items: new OA\Items(
                                type: 'string',
                                format: 'email',
                                example: 'example@mail.com'
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
                                type: 'file'
                            )
                        ),
                    ]
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
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Email')
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

    #[OA\Get(
        path: '/api/v1/mails/{id}',
        operationId: 'mailGet',
        tags: ['Mail'],
        summary: 'Get mail information',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Mail Id',
                required: true,
                in: 'path',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation'
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    public function show() {}

    #[OA\Post(
        path: '/api/v1/mails/{id}',
        operationId: 'mailUpdate',
        tags: ['Mail'],
        summary: 'Update existing mail',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Mail Id',
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
                            property: 'is_draft',
                            type: 'boolean',
                            description: 'Indicates if the email is a draft or not',
                            example: true
                        ),
                        new OA\Property(
                            property: '_method',
                            type: 'string',
                            example: 'PUT',
                            description: 'Method to be used for the request'
                        ),
                        new OA\Property(
                            property: 'reply_to[]',
                            type: 'array',
                            description: 'List of email addresses to reply to',
                            items: new OA\Items(
                                type: 'string',
                                format: 'email',
                                example: 'example@mail.com'
                            )
                        ),
                        new OA\Property(
                            property: 'cc[]',
                            type: 'array',
                            description: 'List of email addresses to cc',
                            items: new OA\Items(
                                type: 'string',
                                format: 'email',
                                example: 'example@mail.com'
                            )
                        ),
                        new OA\Property(
                            property: 'bcc[]',
                            type: 'array',
                            description: 'List of email addresses to bcc',
                            items: new OA\Items(
                                type: 'string',
                                format: 'email',
                                example: 'example@mail.com'
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
                                type: 'file'
                            )
                        ),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(ref: '#/components/schemas/Email')
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    public function update() {}

    #[OA\Delete(
        path: '/api/v1/mails/{id}',
        operationId: 'mailDelete',
        tags: ['Mail'],
        summary: 'Delete existing mail',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Mail Id',
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
                        property: 'type',
                        type: 'string',
                        description: 'Type of delete',
                        example: 'trash'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(ref: '#/components/schemas/Email')
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    public function destroy() {}

    #[OA\Post(
        path: '/api/v1/mails/mass-update',
        operationId: 'mailMassUpdate',
        tags: ['Mail'],
        summary: 'Mass update mails',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'indices',
                        type: 'array',
                        items: new OA\Items(
                            type: 'integer'
                        )
                    ),
                    new OA\Property(
                        property: 'value',
                        type: 'string',
                        example: 'NA'
                    ),
                    new OA\Property(
                        property: 'folders',
                        type: 'array',
                        items: new OA\Items(
                            type: 'string',
                            example: 'inbox'
                        )
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
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Email')
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
    public function massUpdate() {}

    #[OA\Post(
        path: '/api/v1/mails/mass-destroy',
        operationId: 'mailMassDestroy',
        tags: ['Mail'],
        summary: 'Mass delete mails',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'indices',
                        type: 'array',
                        items: new OA\Items(
                            type: 'integer',
                            example: 1
                        )
                    ),
                    new OA\Property(
                        property: 'type',
                        type: 'string',
                        example: ''
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
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Email')
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
    public function massDestroy() {}

    #[OA\Get(
        path: '/api/v1/mails/attachment-download/{id}',
        operationId: 'mailAttachmentDownload',
        tags: ['Mail'],
        summary: 'Download attachment',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Attachment Id',
                required: true,
                in: 'path',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation'
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    public function download() {}
}
