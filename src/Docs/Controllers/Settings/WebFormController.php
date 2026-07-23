<?php

namespace Webkul\RestApi\Docs\Controllers\Settings;

use OpenApi\Attributes as OA;

class WebFormController
{
    #[OA\Get(
        path: '/api/v1/settings/web-forms',
        operationId: 'webFormList',
        tags: ['WebForm'],
        summary: 'Get list of WebForm',
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
                            items: new OA\Items(ref: '#/components/schemas/WebForm')
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

    #[OA\Get(
        path: '/api/v1/settings/web-forms/{id}',
        operationId: 'webFormFind',
        tags: ['WebForm'],
        summary: 'Find WebForm by ID',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of WebForm to return',
                required: true,
                schema: new OA\Schema(type: 'integer', format: 'int64')
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
                            ref: '#/components/schemas/WebForm'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'WebForm not found'
            ),
        ]
    )]
    public function show() {}

    #[OA\Post(
        path: '/api/v1/settings/web-forms',
        operationId: 'webFormCreate',
        tags: ['WebForm'],
        summary: 'Create WebForm',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'title',
                        type: 'string',
                        description: 'Web form title',
                        example: 'Web form title'
                    ),
                    new OA\Property(
                        property: 'description',
                        type: 'string',
                        description: 'Web form description',
                        example: 'webform description'
                    ),
                    new OA\Property(
                        property: 'submit_button_label',
                        type: 'string',
                        description: 'Label for the submit button',
                        example: 'Submit Now'
                    ),
                    new OA\Property(
                        property: 'submit_success_action',
                        type: 'string',
                        description: 'Action to take upon successful submission',
                        example: 'message'
                    ),
                    new OA\Property(
                        property: 'submit_success_content',
                        type: 'string',
                        description: 'Content to show upon successful submission',
                        example: 'This is sample test message'
                    ),
                    new OA\Property(
                        property: 'create_lead',
                        type: 'string',
                        description: 'Create lead option',
                        example: 'on'
                    ),
                    new OA\Property(
                        property: 'background_color',
                        type: 'string',
                        description: 'Background color',
                        example: '#F7F8F9'
                    ),
                    new OA\Property(
                        property: 'form_background_color',
                        type: 'string',
                        description: 'Form background color',
                        example: '#FFFFFF'
                    ),
                    new OA\Property(
                        property: 'form_title_color',
                        type: 'string',
                        description: 'Form title color',
                        example: '#263238'
                    ),
                    new OA\Property(
                        property: 'form_submit_button_color',
                        type: 'string',
                        description: 'Form submit button color',
                        example: '#0E90D9'
                    ),
                    new OA\Property(
                        property: 'attribute_label_color',
                        type: 'string',
                        description: 'Attribute label color',
                        example: '#546E7A'
                    ),
                    new OA\Property(
                        property: 'attributes',
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'attribute_0',
                                type: 'object',
                                properties: [
                                    new OA\Property(
                                        property: 'attribute_id',
                                        type: 'string',
                                        description: 'ID of the attribute',
                                        example: '9'
                                    ),
                                    new OA\Property(
                                        property: 'name',
                                        type: 'string',
                                        description: 'Name of the attribute',
                                        example: 'Name'
                                    ),
                                    new OA\Property(
                                        property: 'sort_order',
                                        type: 'integer',
                                        description: 'Sort order of the attribute',
                                        example: 1
                                    ),
                                    new OA\Property(
                                        property: 'placeholder',
                                        type: 'string',
                                        nullable: true,
                                        description: 'Placeholder for the attribute',
                                        example: null
                                    ),
                                    new OA\Property(
                                        property: 'is_required',
                                        type: 'boolean',
                                        description: 'Is the attribute required',
                                        example: true
                                    ),
                                ]
                            ),
                        ],
                        description: 'Attributes of the web form'
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
                            ref: '#/components/schemas/WebForm'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error'
            ),
        ]
    )]
    public function store() {}

    #[OA\Put(
        path: '/api/v1/settings/web-forms/{id}',
        operationId: 'webFormUpdate',
        tags: ['WebForm'],
        summary: 'Update WebForm',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of WebForm to return',
                required: true,
                schema: new OA\Schema(type: 'integer', format: 'int64')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'title',
                        type: 'string',
                        description: 'Web form title',
                        example: 'Web form title'
                    ),
                    new OA\Property(
                        property: 'description',
                        type: 'string',
                        description: 'Web form description',
                        example: 'webform description'
                    ),
                    new OA\Property(
                        property: 'submit_button_label',
                        type: 'string',
                        description: 'Label for the submit button',
                        example: 'Submit Now'
                    ),
                    new OA\Property(
                        property: 'submit_success_action',
                        type: 'string',
                        description: 'Action to take upon successful submission',
                        example: 'message'
                    ),
                    new OA\Property(
                        property: 'submit_success_content',
                        type: 'string',
                        description: 'Content to show upon successful submission',
                        example: 'This is sample test message'
                    ),
                    new OA\Property(
                        property: 'create_lead',
                        type: 'string',
                        description: 'Create lead option',
                        example: 'on'
                    ),
                    new OA\Property(
                        property: 'background_color',
                        type: 'string',
                        description: 'Background color',
                        example: '#F7F8F9'
                    ),
                    new OA\Property(
                        property: 'form_background_color',
                        type: 'string',
                        description: 'Form background color',
                        example: '#FFFFFF'
                    ),
                    new OA\Property(
                        property: 'form_title_color',
                        type: 'string',
                        description: 'Form title color',
                        example: '#263238'
                    ),
                    new OA\Property(
                        property: 'form_submit_button_color',
                        type: 'string',
                        description: 'Form submit button color',
                        example: '#0E90D9'
                    ),
                    new OA\Property(
                        property: 'attribute_label_color',
                        type: 'string',
                        description: 'Attribute label color',
                        example: '#546E7A'
                    ),
                    new OA\Property(
                        property: 'attributes',
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'attribute_0',
                                type: 'object',
                                properties: [
                                    new OA\Property(
                                        property: 'attribute_id',
                                        type: 'string',
                                        description: 'ID of the attribute',
                                        example: '9'
                                    ),
                                    new OA\Property(
                                        property: 'name',
                                        type: 'string',
                                        description: 'Name of the attribute',
                                        example: 'Name'
                                    ),
                                    new OA\Property(
                                        property: 'sort_order',
                                        type: 'integer',
                                        description: 'Sort order of the attribute',
                                        example: 1
                                    ),
                                    new OA\Property(
                                        property: 'placeholder',
                                        type: 'string',
                                        nullable: true,
                                        description: 'Placeholder for the attribute',
                                        example: null
                                    ),
                                    new OA\Property(
                                        property: 'is_required',
                                        type: 'boolean',
                                        description: 'Is the attribute required',
                                        example: true
                                    ),
                                ]
                            ),
                        ],
                        description: 'Attributes of the web form'
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
                            ref: '#/components/schemas/WebForm'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'WebForm not found'
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error'
            ),
        ]
    )]
    public function update() {}

    #[OA\Delete(
        path: '/api/v1/settings/web-forms/{id}',
        operationId: 'webFormDelete',
        tags: ['WebForm'],
        summary: 'Delete WebForm',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of WebForm to return',
                required: true,
                schema: new OA\Schema(type: 'integer', format: 'int64')
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
                            example: 'Web form deleted successfully'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'WebForm not found'
            ),
        ]
    )]
    public function destroy() {}
}
