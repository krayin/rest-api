<?php

namespace Webkul\RestApi\Docs\Controllers\Settings\DataTransfer;

use OpenApi\Attributes as OA;

class ImportController
{
    #[OA\Get(
        path: '/api/v1/settings/data-transfer/imports',
        operationId: 'importList',
        tags: ['DataTransfer'],
        summary: 'Get list of Import',
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
                            items: new OA\Items(ref: '#/components/schemas/Import')
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
        path: '/api/v1/settings/data-transfer/imports/download-sample/{sample}',
        operationId: 'importSample',
        tags: ['DataTransfer'],
        summary: 'Download sample import file',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'sample',
                in: 'path',
                required: true,
                description: 'Type of sample file to download',
                schema: new OA\Schema(type: 'string', enum: ['persons', 'products', 'leads'])
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\MediaType(
                    mediaType: 'application/csv',
                    schema: new OA\Schema(type: 'string', format: 'binary')
                )
            ),
        ]
    )]
    public function downloadSample() {}

    #[OA\Post(
        path: '/api/v1/settings/data-transfer/imports',
        operationId: 'importCreate',
        tags: ['DataTransfer'],
        summary: 'Create new Import',
        security: [['sanctum_admin' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    type: 'object',
                    required: ['type', 'action', 'validation_strategy', 'allowed_errors', 'field_separator', 'file'],
                    properties: [
                        new OA\Property(
                            property: 'type',
                            description: 'Type of import data',
                            type: 'string',
                            enum: ['persons', 'products', 'leads'],
                            example: 'persons'
                        ),
                        new OA\Property(
                            property: 'action',
                            description: 'Action to perform',
                            type: 'string',
                            enum: ['append', 'delete'],
                            example: 'create/update'
                        ),
                        new OA\Property(
                            property: 'validation_strategy',
                            description: 'Validation strategy',
                            type: 'string',
                            enum: ['stop-on-errors', 'skip-erros'],
                            example: 'stop-on-errors'
                        ),
                        new OA\Property(
                            property: 'allowed_errors',
                            description: 'Number of allowed errors before stopping',
                            type: 'integer',
                            example: 10
                        ),
                        new OA\Property(
                            property: 'field_separator',
                            description: 'Field separator character in file',
                            type: 'string',
                            example: ','
                        ),
                        new OA\Property(
                            property: 'process_in_queue',
                            description: 'Whether to process in queue (optional)',
                            type: 'string',
                            example: 'on'
                        ),
                        new OA\Property(
                            property: 'file',
                            description: 'File to import',
                            type: 'string',
                            format: 'binary'
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
                            ref: '#/components/schemas/Import',
                            type: 'object'
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

    #[OA\Get(
        path: '/api/v1/settings/data-transfer/imports/validate/{importId}',
        operationId: 'validateImport',
        tags: ['DataTransfer'],
        summary: 'Validate import file',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'importId',
                in: 'path',
                required: true,
                description: 'ID of the import',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'is_valid', type: 'boolean', example: false),
                        new OA\Property(
                            property: 'import',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 2),
                                new OA\Property(property: 'state', type: 'string', example: 'validated'),
                                new OA\Property(property: 'process_in_queue', type: 'integer', example: 1),
                                new OA\Property(property: 'type', type: 'string', example: 'persons'),
                                new OA\Property(property: 'action', type: 'string', example: 'append'),
                                new OA\Property(property: 'validation_strategy', type: 'string', example: 'stop-on-errors'),
                                new OA\Property(property: 'allowed_errors', type: 'integer', example: 10),
                                new OA\Property(property: 'processed_rows_count', type: 'integer', example: 2),
                                new OA\Property(property: 'invalid_rows_count', type: 'integer', example: 2),
                                new OA\Property(property: 'errors_count', type: 'integer', example: 2),
                                new OA\Property(
                                    property: 'errors',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'Row(s) 1, 2: The selected organization id is invalid.')
                                ),
                                new OA\Property(property: 'field_separator', type: 'string', example: ','),
                                new OA\Property(property: 'file_path', type: 'string', example: 'imports/1746077606-persons.csv'),
                                new OA\Property(property: 'error_file_path', type: 'string', example: 'imports/1746077857-error-report.csv'),
                                new OA\Property(property: 'summary', type: 'string', nullable: true, example: null),
                                new OA\Property(property: 'started_at', type: 'string', format: 'date-time', nullable: true, example: null),
                                new OA\Property(property: 'completed_at', type: 'string', format: 'date-time', nullable: true, example: null),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2025-05-01T05:33:26.000000Z'),
                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2025-05-01T05:37:37.000000Z'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Import record not found'
            ),
        ]
    )]
    public function validateImport() {}

    #[OA\Get(
        path: '/api/v1/settings/data-transfer/imports/start/{importId}',
        operationId: 'startImport',
        tags: ['DataTransfer'],
        summary: 'Start importing data',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'importId',
                in: 'path',
                required: true,
                description: 'ID of the import',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'stats',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'batches',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'total', type: 'integer', example: 1),
                                        new OA\Property(property: 'completed', type: 'integer', example: 0),
                                        new OA\Property(property: 'remaining', type: 'integer', example: 1),
                                    ]
                                ),
                                new OA\Property(property: 'progress', type: 'integer', example: 0),
                                new OA\Property(
                                    property: 'summary',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'created', type: 'integer', example: 0),
                                        new OA\Property(property: 'updated', type: 'integer', example: 0),
                                        new OA\Property(property: 'deleted', type: 'integer', example: 0),
                                    ]
                                ),
                            ]
                        ),
                        new OA\Property(
                            property: 'import',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 4),
                                new OA\Property(property: 'state', type: 'string', example: 'processing'),
                                new OA\Property(property: 'process_in_queue', type: 'integer', example: 1),
                                new OA\Property(property: 'type', type: 'string', example: 'persons'),
                                new OA\Property(property: 'action', type: 'string', example: 'append'),
                                new OA\Property(property: 'validation_strategy', type: 'string', example: 'stop-on-errors'),
                                new OA\Property(property: 'allowed_errors', type: 'integer', example: 10),
                                new OA\Property(property: 'processed_rows_count', type: 'integer', example: 2),
                                new OA\Property(property: 'invalid_rows_count', type: 'integer', example: 0),
                                new OA\Property(property: 'errors_count', type: 'integer', example: 0),
                                new OA\Property(
                                    property: 'errors',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: '')
                                ),
                                new OA\Property(property: 'field_separator', type: 'string', example: ','),
                                new OA\Property(property: 'file_path', type: 'string', example: 'imports/1746077680-persons.csv'),
                                new OA\Property(property: 'error_file_path', type: 'string', nullable: true, example: null),
                                new OA\Property(
                                    property: 'summary',
                                    type: 'array',
                                    items: new OA\Items(type: 'string')
                                ),
                                new OA\Property(property: 'started_at', type: 'string', format: 'date-time', example: '2025-05-01T05:40:16.000000Z'),
                                new OA\Property(property: 'completed_at', type: 'string', format: 'date-time', nullable: true, example: null),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2025-05-01T05:34:40.000000Z'),
                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2025-05-01T05:40:16.000000Z'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Import record not found'
            ),
        ]
    )]
    public function start() {}

    #[OA\Get(
        path: '/api/v1/settings/data-transfer/imports/stats/{importId}/{state}',
        operationId: 'getStats',
        tags: ['DataTransfer'],
        summary: 'Get import stats',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'importId',
                in: 'path',
                required: true,
                description: 'ID of the import',
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'state',
                in: 'path',
                required: false,
                description: "State of the import (leave empty for 'processed').",
                schema: new OA\Schema(
                    type: 'string',
                    enum: [
                        'pending',
                        'validated',
                        'processing',
                        'processed',
                        'linking',
                        'linked',
                        'indexing',
                        'indexed',
                        'completed',
                    ],
                    default: 'processed'
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'stats',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'batches',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'total', type: 'integer', example: 1),
                                        new OA\Property(property: 'completed', type: 'integer', example: 0),
                                        new OA\Property(property: 'remaining', type: 'integer', example: 1),
                                    ]
                                ),
                                new OA\Property(property: 'progress', type: 'integer', example: 0),
                                new OA\Property(
                                    property: 'summary',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'created', type: 'integer', example: 0),
                                        new OA\Property(property: 'updated', type: 'integer', example: 0),
                                        new OA\Property(property: 'deleted', type: 'integer', example: 0),
                                    ]
                                ),
                            ]
                        ),
                        new OA\Property(
                            property: 'import',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 9),
                                new OA\Property(property: 'state', type: 'string', example: 'processing'),
                                new OA\Property(property: 'process_in_queue', type: 'integer', example: 1),
                                new OA\Property(property: 'type', type: 'string', example: 'products'),
                                new OA\Property(property: 'action', type: 'string', example: 'append'),
                                new OA\Property(property: 'validation_strategy', type: 'string', example: 'stop-on-errors'),
                                new OA\Property(property: 'allowed_errors', type: 'integer', example: 10),
                                new OA\Property(property: 'processed_rows_count', type: 'integer', example: 2),
                                new OA\Property(property: 'invalid_rows_count', type: 'integer', example: 0),
                                new OA\Property(property: 'errors_count', type: 'integer', example: 0),
                                new OA\Property(
                                    property: 'errors',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: '')
                                ),
                                new OA\Property(property: 'field_separator', type: 'string', example: ','),
                                new OA\Property(property: 'file_path', type: 'string', example: 'imports/1746079436-products.csv'),
                                new OA\Property(property: 'error_file_path', type: 'string', nullable: true, example: null),
                                new OA\Property(
                                    property: 'summary',
                                    type: 'array',
                                    items: new OA\Items(type: 'string')
                                ),
                                new OA\Property(property: 'started_at', type: 'string', format: 'date-time', example: '2025-05-01T06:04:09.000000Z'),
                                new OA\Property(property: 'completed_at', type: 'string', format: 'date-time', nullable: true, example: null),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2025-05-01T06:00:52.000000Z'),
                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2025-05-01T06:04:09.000000Z'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Import record not found'
            ),
        ]
    )]
    public function stats() {}

    #[OA\Get(
        path: '/api/v1/settings/data-transfer/imports/download-error-report/{importId}',
        operationId: 'downloadErrorReport',
        tags: ['DataTransfer'],
        summary: 'Download the error report CSV file for the import',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'importId',
                in: 'path',
                required: true,
                description: 'ID of the import',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'CSV file download',
                content: [
                    new OA\MediaType(
                        mediaType: 'text/csv',
                        schema: new OA\Schema(type: 'string', format: 'binary')
                    ),
                ]
            ),
            new OA\Response(
                response: 404,
                description: 'Import not found or no error report available'
            ),
        ]
    )]
    public function downloadErrorReport() {}

    #[OA\Post(
        path: '/api/v1/settings/data-transfer/imports/{id}',
        operationId: 'importUpdate',
        tags: ['DataTransfer'],
        summary: 'Update existing Import',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID of import to update',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    type: 'object',
                    required: ['_method', 'type', 'action', 'validation_strategy', 'allowed_errors', 'field_separator', 'file'],
                    properties: [
                        new OA\Property(
                            property: '_method',
                            description: 'HTTP method override',
                            type: 'string',
                            example: 'PUT'
                        ),
                        new OA\Property(
                            property: 'type',
                            description: 'Type of import data',
                            type: 'string',
                            enum: ['persons', 'products', 'leads'],
                            example: 'persons'
                        ),
                        new OA\Property(
                            property: 'action',
                            description: 'Action to perform',
                            type: 'string',
                            enum: ['create/update', 'append', 'delete'],
                            example: 'append'
                        ),
                        new OA\Property(
                            property: 'validation_strategy',
                            description: 'Validation strategy',
                            type: 'string',
                            enum: ['stop-on-errors', 'skip-erros'],
                            example: 'stop-on-errors'
                        ),
                        new OA\Property(
                            property: 'allowed_errors',
                            description: 'Number of allowed errors before stopping',
                            type: 'integer',
                            example: 10
                        ),
                        new OA\Property(
                            property: 'field_separator',
                            description: 'Field separator character in file',
                            type: 'string',
                            example: ','
                        ),
                        new OA\Property(
                            property: 'process_in_queue',
                            description: 'Whether to process in queue (optional)',
                            type: 'string',
                            example: 'on'
                        ),
                        new OA\Property(
                            property: 'file',
                            description: 'File to import',
                            type: 'string',
                            format: 'binary'
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
                            ref: '#/components/schemas/Import',
                            type: 'object'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Import not found'
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error'
            ),
        ]
    )]
    public function update() {}

    #[OA\Get(
        path: '/api/v1/settings/data-transfer/imports/{id}',
        operationId: 'showImport',
        tags: ['DataTransfer'],
        summary: 'Get details of a specific Import',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Import Id',
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
                            type: 'Object',
                            ref: '#/components/schemas/Import'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Import not found'
            ),
        ]
    )]
    public function show() {}

    #[OA\Delete(
        path: '/api/v1/settings/data-transfer/imports/{id}',
        operationId: 'importDelete',
        tags: ['DataTransfer'],
        summary: 'Delete one record of Data Transfer',
        security: [['sanctum_admin' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Import Id',
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
                            items: new OA\Items(ref: '#/components/schemas/Import')
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
    public function destroy() {}
}
