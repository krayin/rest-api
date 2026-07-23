<?php

namespace Webkul\RestApi\Docs\Models\Settings;

use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'Warehouse',
    description: 'Warehouse model',
)]
class Warehouse
{
    #[OA\Property(
        title: 'ID',
        description: 'Warehouse ID',
        format: 'int64',
        example: '1'
    )]
    /** @var string */
    private $id;

    #[OA\Property(
        property: 'name',
        type: 'string',
        description: 'Warehouse Name',
        example: 'Warehouse Name'
    )]
    /** @var string */
    private $name;

    #[OA\Property(
        property: 'contact_name',
        type: 'string',
        description: 'Contact Name',
        example: 'Jane Doe'
    )]
    /** @var string */
    private $contact_name;

    #[OA\Property(
        property: 'description',
        type: 'string',
        description: 'Description',
        example: 'Description'
    )]
    /** @var string */
    private $description;

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
        property: 'address',
        type: 'array',
        description: 'Warehouse Address',
        items: new OA\Items(
            properties: [
                new OA\Property(
                    property: 'city',
                    type: 'string',
                    description: 'City name',
                    example: 'Los Angeles'
                ),
                new OA\Property(
                    property: 'state',
                    type: 'string',
                    description: 'State name',
                    example: 'CA'
                ),
                new OA\Property(
                    property: 'address',
                    type: 'string',
                    description: 'Street address',
                    example: '123 Main St'
                ),
                new OA\Property(
                    property: 'country',
                    type: 'string',
                    description: 'Country code',
                    example: 'US'
                ),
                new OA\Property(
                    property: 'postcode',
                    type: 'string',
                    description: 'Postal code',
                    example: '90001'
                ),
            ]
        ),
    )]
    /** @var string */
    private $address;

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
