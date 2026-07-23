<?php

namespace Webkul\RestApi\Docs\Controllers\Lead;

use OpenApi\Attributes as OA;

class LeadController
{
    #[OA\Get(
        path: '/api/v1/leads',
        operationId: 'leadList',
        tags: ['Leads'],
        summary: 'Get list of leads',
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
                            items: new OA\Items(ref: '#/components/schemas/Lead')
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
        path: '/api/v1/leads/{id}',
        operationId: 'getParticularLead',
        tags: ['Leads'],
        summary: 'Get the particular Lead',
        description: 'Get the particular Lead',
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
                            type: 'object',
                            ref: '#/components/schemas/Lead'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function show() {}

    #[OA\Get(
        path: '/api/v1/leads/search',
        operationId: 'searchLeads',
        tags: ['Leads'],
        summary: 'Search the Leads',
        description: 'Search the Leads',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'title',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'Leads via phone')
            ),
            new OA\Parameter(
                name: 'user.name',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'admin')
            ),
            new OA\Parameter(
                name: 'person.name',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'admin')
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
                            ref: '#/components/schemas/Lead'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function search() {}

    #[OA\Get(
        path: '/api/v1/leads/get',
        operationId: 'Get the Leads',
        tags: ['Leads'],
        summary: 'Get the Leads',
        description: 'Get the Leads',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'search',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'lead_value:454;')
            ),
            new OA\Parameter(
                name: 'searchFields',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'lead_value:in;')
            ),
            new OA\Parameter(
                name: 'pipeline_id',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: null)
            ),
            new OA\Parameter(
                name: 'limit',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 10)
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
                            ref: '#/components/schemas/Lead'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function get() {}

    #[OA\Get(
        path: '/api/v1/leads/kanban/look-up',
        operationId: 'dataGridSearchableDropdown',
        tags: ['Leads'],
        summary: 'Get the Datagird Searchable dropdown Leads',
        description: 'Get the Datagird Searchable dropdown Leads',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'search',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'admin')
            ),
            new OA\Parameter(
                name: 'column',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'user_id')
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
                            ref: '#/components/schemas/Lead'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function kanbanLookup() {}

    #[OA\Post(
        path: '/api/v1/leads',
        operationId: 'storeLead',
        tags: ['Leads'],
        summary: 'Store the Leads',
        description: 'Store the Leads',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'title',
                            type: 'string',
                            description: 'Title',
                            example: 'Leads via phone'
                        ),
                        new OA\Property(
                            property: 'description',
                            type: 'string',
                            description: 'Description',
                            example: 'Leads comes via phone call.'
                        ),
                        new OA\Property(
                            property: 'lead_value',
                            type: 'string',
                            description: 'Lead Value',
                            example: '55446'
                        ),
                        new OA\Property(
                            property: 'lead_pipeline_stage_id',
                            nullable: true,
                            example: 1,
                            description: 'Lead Pipeline state ID'
                        ),
                        new OA\Property(
                            property: 'lead_source_id',
                            description: 'The source of the lead. Possible values:
                                1: Email
                                2: Web
                                3: Web Form
                                4: Phone
                                5: Direct
                            ',
                            example: '1',
                            enum: [1, 2, 3, 4, 5]
                        ),
                        new OA\Property(
                            property: 'lead_type_id',
                            type: 'integer',
                            description: 'The source of the lead. Possible values:
                                1: New Business
                                2: Existing Business
                            ',
                            example: '1',
                            enum: [1, 2]
                        ),
                        new OA\Property(
                            property: 'user_id',
                            type: 'integer',
                            description: 'User ID',
                            example: '1'
                        ),
                        new OA\Property(
                            property: 'expected_close_date',
                            format: 'date',
                            type: 'date',
                            description: 'Expected Close Date',
                            example: '2024-05-25'
                        ),
                        new OA\Property(
                            property: 'person',
                            description: 'Details of the person',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'name',
                                    type: 'string',
                                    example: 'John Doe'
                                ),
                                new OA\Property(
                                    property: 'emails',
                                    type: 'array',
                                    description: 'List of person email addresses',
                                    items: new OA\Items(
                                        type: 'object',
                                        properties: [
                                            new OA\Property(
                                                property: 'value',
                                                type: 'string',
                                                description: 'Email address',
                                                example: 'John@mail.com'
                                            ),
                                            new OA\Property(
                                                property: 'label',
                                                type: 'string',
                                                description: 'Email address',
                                                example: 'work'
                                            ),
                                        ]
                                    )
                                ),
                                new OA\Property(
                                    property: 'contact_numbers',
                                    type: 'array',
                                    description: 'List of person contacts',
                                    items: new OA\Items(
                                        type: 'object',
                                        properties: [
                                            new OA\Property(
                                                property: 'value',
                                                type: 'string',
                                                description: 'Email address',
                                                example: '9645785245'
                                            ),
                                            new OA\Property(
                                                property: 'label',
                                                type: 'string',
                                                description: 'Email address',
                                                example: 'work'
                                            ),
                                        ]
                                    )
                                ),
                                new OA\Property(
                                    property: 'organization_id',
                                    type: 'string',
                                    nullable: true,
                                    example: null
                                ),
                                new OA\Property(
                                    property: 'user_id',
                                    type: 'string',
                                    nullable: true,
                                    example: null
                                ),
                                new OA\Property(
                                    property: 'job_title',
                                    type: 'string',
                                    nullable: true,
                                    example: 'Sales Executive'
                                ),
                            ]
                        ),
                        new OA\Property(
                            property: 'products',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'product_0',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'name', type: 'string', example: 'iphone-14'),
                                        new OA\Property(property: 'product_id', type: 'string', example: '1'),
                                        new OA\Property(property: 'price', type: 'string', example: '423'),
                                        new OA\Property(property: 'quantity', type: 'integer', example: '324'),
                                    ]
                                ),
                            ]
                        ),
                        new OA\Property(
                            property: 'entity_type',
                            type: 'string',
                            example: 'leads'
                        ),
                    ],
                    required: ['title', 'description', 'lead_value', 'lead_source_id', 'lead_type_id', 'person[name]']
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Lead Created Successfully.'),
                        new OA\Property(property: 'data', type: 'object', ref: '#/components/schemas/Lead'),
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

    #[OA\Post(
        path: '/api/v1/leads/create-by-ai',
        operationId: 'storeLeadFiles',
        tags: ['Leads'],
        summary: 'Upload files for Leads',
        description: 'Upload multiple files for the Leads',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'files[]',
                            type: 'array',
                            description: 'Array of files to upload',
                            items: new OA\Items(
                                type: 'string',
                                format: 'binary'
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
                        new OA\Property(property: 'message', type: 'string', example: 'Lead Created Successfully.'),
                        new OA\Property(property: 'data', type: 'object', ref: '#/components/schemas/Lead'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated'
            ),
        ]
    )]
    public function storeFiles() {}

    #[OA\Put(
        path: '/api/v1/leads/{id}',
        operationId: 'updateLeads',
        tags: ['Leads'],
        summary: 'Update the Leads',
        description: 'Update the Leads',
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
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'title',
                            type: 'string',
                            description: 'Title',
                            example: 'Leads via phone'
                        ),
                        new OA\Property(
                            property: 'description',
                            type: 'string',
                            description: 'Description',
                            example: 'Leads comes via phone call.'
                        ),
                        new OA\Property(
                            property: 'lead_value',
                            type: 'string',
                            description: 'Lead Value',
                            example: '55446'
                        ),
                        new OA\Property(
                            property: 'lead_pipeline_stage_id',
                            nullable: true,
                            example: 1,
                            description: 'Lead Pipeline state ID'
                        ),
                        new OA\Property(
                            property: 'lead_source_id',
                            description: 'The source of the lead. Possible values:
                                1: Email
                                2: Web
                                3: Web Form
                                4: Phone
                                5: Direct
                            ',
                            example: '1',
                            enum: [1, 2, 3, 4, 5]
                        ),
                        new OA\Property(
                            property: 'lead_type_id',
                            type: 'integer',
                            description: 'The source of the lead. Possible values:
                                1: New Business
                                2: Existing Business
                            ',
                            example: '1',
                            enum: [1, 2]
                        ),
                        new OA\Property(
                            property: 'user_id',
                            type: 'integer',
                            description: 'User ID',
                            example: '1'
                        ),
                        new OA\Property(
                            property: 'expected_close_date',
                            format: 'date',
                            type: 'date',
                            description: 'Expected Close Date',
                            example: '2024-05-25'
                        ),
                        new OA\Property(
                            property: 'person',
                            description: 'Details of the person',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'name',
                                    type: 'string',
                                    example: 'John Doe'
                                ),
                                new OA\Property(
                                    property: 'id',
                                    type: 'string',
                                    example: '1'
                                ),
                                new OA\Property(
                                    property: 'emails',
                                    type: 'array',
                                    description: 'List of person email addresses',
                                    items: new OA\Items(
                                        type: 'object',
                                        properties: [
                                            new OA\Property(
                                                property: 'value',
                                                type: 'string',
                                                description: 'Email address',
                                                example: 'John@mail.com'
                                            ),
                                            new OA\Property(
                                                property: 'label',
                                                type: 'string',
                                                description: 'Email address',
                                                example: 'work'
                                            ),
                                        ]
                                    )
                                ),
                                new OA\Property(
                                    property: 'contact_numbers',
                                    type: 'array',
                                    description: 'List of person contacts',
                                    items: new OA\Items(
                                        type: 'object',
                                        properties: [
                                            new OA\Property(
                                                property: 'value',
                                                type: 'string',
                                                description: 'Email address',
                                                example: '9645785245'
                                            ),
                                            new OA\Property(
                                                property: 'label',
                                                type: 'string',
                                                description: 'Email address',
                                                example: 'work'
                                            ),
                                        ]
                                    )
                                ),
                                new OA\Property(
                                    property: 'organization_id',
                                    type: 'string',
                                    nullable: true,
                                    example: null
                                ),
                                new OA\Property(
                                    property: 'user_id',
                                    type: 'string',
                                    nullable: true,
                                    example: null
                                ),
                                new OA\Property(
                                    property: 'job_title',
                                    type: 'string',
                                    nullable: true,
                                    example: 'Sales Executive'
                                ),
                            ]
                        ),
                        new OA\Property(
                            property: 'products',
                            description: 'Details of the Products',
                            type: 'object'
                        ),
                        new OA\Property(
                            property: 'entity_type',
                            type: 'string',
                            example: 'leads'
                        ),
                    ],
                    required: ['title', 'description', 'lead_value', 'lead_source_id', 'lead_type_id', 'person[name]']
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Lead Updated Successfully.'),
                        new OA\Property(property: 'data', type: 'object', ref: '#/components/schemas/Lead'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated'
            ),
        ]
    )]
    public function update() {}

    #[OA\Put(
        path: '/api/v1/leads/product/{id}',
        operationId: 'updateProductRelatedtoLead',
        tags: ['Leads'],
        summary: 'Store the products related to Leads',
        description: 'Store the products related to Leads',
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
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'amount',
                            type: 'number',
                            description: 'Amount related to the lead',
                            example: 1500.75
                        ),
                        new OA\Property(
                            property: 'id',
                            type: 'integer',
                            description: 'Unique identifier for the product',
                            example: null
                        ),
                        new OA\Property(
                            property: 'is_new',
                            type: 'boolean',
                            description: 'Indicates if the product is new',
                            example: true
                        ),
                        new OA\Property(
                            property: 'name',
                            type: 'string',
                            description: 'Name of the product',
                            example: 'Sample Product'
                        ),
                        new OA\Property(
                            property: 'price',
                            type: 'number',
                            description: 'Price of the product',
                            example: 299.99
                        ),
                        new OA\Property(
                            property: 'product_id',
                            type: 'integer',
                            description: 'Product ID associated with the lead',
                            example: 10
                        ),
                        new OA\Property(
                            property: 'quantity',
                            type: 'integer',
                            description: 'Quantity of the product',
                            example: 2
                        ),
                    ],
                    required: ['amount', 'id', 'is_new', 'name', 'price', 'product_id', 'quantity']
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Lead Updated Successfully.'),
                        new OA\Property(property: 'data', type: 'object', ref: '#/components/schemas/Lead'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated'
            ),
        ]
    )]
    public function addProduct() {}

    #[OA\Delete(
        path: '/api/v1/leads/product/{id}',
        operationId: 'deleteProductRelatedtoLead',
        tags: ['Leads'],
        summary: 'Delete the products related to Leads',
        description: 'Delete the products related to Leads',
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
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'product_id',
                            type: 'integer',
                            description: 'Product ID associated with the lead',
                            example: 1
                        ),
                    ],
                    required: ['product_id']
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Lead Updated Successfully.'),
                        new OA\Property(property: 'data', type: 'object', ref: '#/components/schemas/Lead'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated'
            ),
        ]
    )]
    public function removeProduct() {}

    #[OA\Put(
        path: '/api/v1/leads/attributes/edit/{id}',
        operationId: 'updateLeadAttribute',
        tags: ['Leads'],
        summary: 'Update the lead attributes',
        description: 'Update the lead attributes',
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
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'lead_pipeline_stage_id',
                            nullable: true,
                            example: 1,
                            description: 'Lead Pipeline state ID'
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
                        new OA\Property(property: 'message', type: 'string', example: 'Lead Updated Successfully.'),
                        new OA\Property(property: 'data', type: 'object', ref: '#/components/schemas/Lead'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated'
            ),
        ]
    )]
    public function updateAttributes() {}

    #[OA\Put(
        path: '/api/v1/leads/stage/edit/{id}',
        operationId: 'updateLeadStage',
        tags: ['Leads'],
        summary: 'Update the lead stage',
        description: 'Update the lead stage',
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
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'lead_pipeline_stage_id',
                            nullable: true,
                            example: 1,
                            description: 'Lead Pipeline state ID'
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
                        new OA\Property(property: 'message', type: 'string', example: 'Lead Updated Successfully.'),
                        new OA\Property(property: 'data', type: 'object', ref: '#/components/schemas/Lead'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated'
            ),
        ]
    )]
    public function updateStage() {}

    #[OA\Delete(
        path: '/api/v1/leads/{id}',
        operationId: 'deleteLeads',
        tags: ['Leads'],
        summary: 'Delete the Leads',
        description: 'Delete the Leads',
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
                        new OA\Property(property: 'message', type: 'string', example: 'Lead Deleted Successfully.'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated'
            ),
        ]
    )]
    public function destroy() {}

    #[OA\Post(
        path: '/api/v1/leads/mass-update',
        operationId: 'massUpdateLeads',
        tags: ['Leads'],
        summary: 'Mass update Leads',
        description: 'Mass update Leads, here you can update the leads stages.',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'indices',
                        type: 'array',
                        description: 'IDs of the Leads to be updated',
                        items: new OA\Items(
                            type: 'integer',
                            example: 1
                        )
                    ),
                    new OA\Property(
                        property: 'value',
                        type: 'string',
                        description: 'Value to be update',
                        example: '1'
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
                            example: 'Leads updated successfully.'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function massUpdate() {}

    #[OA\Post(
        path: '/api/v1/leads/mass-destroy',
        operationId: 'massDeleteLeads',
        tags: ['Leads'],
        summary: 'Mass delete Leads',
        description: 'Mass delete Leads',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'indices',
                            description: "Leads's Ids `CommaSeparated`",
                            type: 'string',
                            example: [1]
                        ),
                    ],
                    required: ['indices']
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
                            example: 'Leads deleted successfully.'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function massDestroy() {}
}
