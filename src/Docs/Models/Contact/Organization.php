<?php

namespace Webkul\RestApi\Docs\Models\Contact;

use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'Organization',
    description: 'Organization Model. Any user-defined custom fields are returned as additional top-level properties keyed by their attribute code.',
    additionalProperties: true,
)]
class Organization
{
    #[OA\Property(
        title: 'ID',
        description: 'Organization ID',
        format: 'int64',
        example: '1'
    )]
    /** @var string */
    private $id;

    #[OA\Property(
        property: 'name',
        type: 'string',
        description: 'Organization Name',
        example: 'Organization Name'
    )]
    /** @var string */
    private $name;

    #[OA\Property(
        property: 'address',
        type: 'array',
        description: 'Organization Address',
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
