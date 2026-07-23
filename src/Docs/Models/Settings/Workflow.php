<?php

namespace Webkul\RestApi\Docs\Models\Settings;

use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'Attribute',
    description: 'Attribute Model',
)]
class Workflow
{
    #[OA\Property(
        title: 'ID',
        description: 'ID',
        format: 'int64',
        example: 1
    )]
    /** @var int */
    private $id;

    #[OA\Property(
        title: 'Name',
        description: 'Name',
        example: 'EmailTemplate Name'
    )]
    /** @var string */
    private $name;

    #[OA\Property(
        title: 'description',
        description: 'Description',
        example: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s."
    )]
    /** @var string */
    private $description;

    #[OA\Property(
        title: 'Entity Type',
        description: 'Entity Type',
        example: 'leads'
    )]
    /** @var string */
    private $entity_type;

    #[OA\Property(
        title: 'Event',
        description: 'Event',
        example: 'activity.update.after'
    )]
    /** @var string */
    private $event;

    #[OA\Property(
        title: 'Condition Type',
        description: 'Condition Type',
        example: 'and'
    )]
    /** @var string */
    private $condition_type;

    #[OA\Property(
        property: 'conditions',
        type: 'array',
        description: 'Conditions',
        items: new OA\Items(
            type: 'object',
            description: 'A condition item object',
            properties: [
                new OA\Property(
                    property: 'value',
                    type: 'array',
                    items: new OA\Items(type: 'string'),
                    description: 'The values associated with the condition',
                    example: ['call', 'meeting', 'lunch']
                ),
                new OA\Property(
                    property: 'operator',
                    type: 'string',
                    description: 'The operator for the condition',
                    example: '{}'
                ),
                new OA\Property(
                    property: 'attribute',
                    type: 'string',
                    description: 'The attribute for the condition',
                    example: 'type'
                ),
                new OA\Property(
                    property: 'attribute_type',
                    type: 'string',
                    description: 'The type of the attribute',
                    example: 'multiselect'
                ),
            ]
        ),
        example: [[
            'value'          => ['call', 'meeting', 'lunch'],
            'operator'       => '{}',
            'attribute'      => 'type',
            'attribute_type' => 'multiselect'
        ]]
    )]
    /** @var array */
    private $conditions;

    #[OA\Property(
        property: 'actions',
        type: 'array',
        description: 'Actions',
        items: new OA\Items(
            type: 'object',
            description: 'An action item object',
            properties: [
                new OA\Property(
                    property: 'id',
                    type: 'string',
                    description: 'The ID of the action'
                ),
                new OA\Property(
                    property: 'value',
                    type: 'string',
                    description: 'The value associated with the action'
                ),
            ]
        ),
        example: [['id' => 'send_email_to_participants', 'value' => '2']]
    )]
    /** @var array */
    private $actions;

    #[OA\Property(
        title: 'Created at',
        description: 'Created at',
        example: '2020-01-27 17:50:45',
        format: 'datetime',
        type: 'string'
    )]
    /** @var \DateTime */
    private $created_at;

    #[OA\Property(
        title: 'Updated at',
        description: 'Updated at',
        example: '2020-01-27 17:50:45',
        format: 'datetime',
        type: 'string'
    )]
    /** @var \DateTime */
    private $updated_at;
}
