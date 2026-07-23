<?php

namespace Webkul\RestApi\Docs\Controllers\Contact\Persons;

use OpenApi\Attributes as OA;

class TagController
{
    #[OA\Delete(
        path: '/api/v1/contacts/persons/{id}/tags',
        operationId: 'detachPersonTags',
        tags: ['Contacts'],
        summary: 'Dettached tags to the Persons',
        description: 'Dettached tags to the Persons',
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
        path: '/api/v1/contacts/persons/{id}/tags',
        operationId: 'attachPersonTags',
        tags: ['Contacts'],
        summary: 'Attached tags to the Persons',
        description: 'Attached tags to the Persons',
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
