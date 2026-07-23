<?php

namespace Webkul\RestApi\Docs\Models\Contact;

use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'Person',
    description: 'Person Model. Any user-defined custom fields are returned as additional top-level properties keyed by their attribute code.',
    additionalProperties: true,
)]
class Person
{
    #[OA\Property(
        title: 'ID',
        description: 'ID of the lead',
        format: 'int64',
        example: 1
    )]
    /** @var string */
    private $id;

    #[OA\Property(
        title: 'Name',
        description: 'Name of the lead',
        example: 'John Doe'
    )]
    /** @var string */
    private $name;

    #[OA\Property(
        property: 'emails',
        type: 'array',
        description: 'Contact Emails',
        items: new OA\Items(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'label',
                    type: 'string',
                    description: "Label for the contact emails (e.g., 'work', 'home')",
                    example: 'work'
                ),
                new OA\Property(
                    property: 'value',
                    type: 'string',
                    description: 'The contact email',
                    example: 'example2@gmail.com'
                )
            ]
        ),
        example: [
            ['label' => 'work', 'value' => 'example1@gmail.com'],
            ['label' => 'home', 'value' => 'example2@gmail.com']
        ]
    )]
    /** @var string */
    private $emails;

    #[OA\Property(
        property: 'contact_numbers',
        type: 'array',
        description: 'Contact Numbers',
        items: new OA\Items(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'label',
                    type: 'string',
                    description: "Label for the contact number (e.g., 'work', 'home')",
                    example: 'work'
                ),
                new OA\Property(
                    property: 'value',
                    type: 'string',
                    description: 'The contact number',
                    example: '9999999999'
                )
            ]
        ),
        example: [
            ['label' => 'work', 'value' => '9999999999'],
            ['label' => 'home', 'value' => '8888888888']
        ]
    )]
    /** @var string */
    private $contact_numbers;

    #[OA\Property(
        title: 'Organization',
        description: 'Organization',
    )]
    /** @var \Webkul\RestApi\Docs\Models\Contact\Organization */
    private $organization;

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
