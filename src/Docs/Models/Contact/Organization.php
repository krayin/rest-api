<?php

namespace Webkul\RestApi\Docs\Models\Contact;

/**
 * @OA\Schema(
 *     title="Organization",
 *     description="Organization Model",
 * )
 */
class Organization
{
    /**
     * @OA\Property(
     *     title="ID",
     *     description="Organization ID",
     *     format="int64",
     *     example="1"
     * )
     *
     * @var string
     */
    private $id;

    /**
     * @OA\Property(
     *     property="name",
     *     type="string",
     *     description="Organization Name",
     *     example="Organization Name"
     * ),
     *
     * @var string
     */
    private $name;

    /**
     * @OA\Property(
     *     property="address",
     *     type="array",
     *     description="Organization Address",
     *
     *     @OA\Items(
     *
     *        @OA\Property(
     *            property="city",
     *            type="string",
     *            description="City name",
     *            example="Noida"
     *        ),
     *        @OA\Property(
     *            property="state",
     *            type="string",
     *            description="State name",
     *            example="UP"
     *        ),
     *        @OA\Property(
     *            property="address",
     *            type="string",
     *            description="Street address",
     *            example="Bheem Nagar"
     *        ),
     *        @OA\Property(
     *            property="country",
     *            type="string",
     *            description="Country code",
     *            example="IN"
     *        ),
     *        @OA\Property(
     *            property="postcode",
     *            type="string",
     *            description="Postal code",
     *            example="201009"
     *       ),
     *     ),
     * )
     */
    private $address;

    /**
     * @OA\Property(
     *     title="Created at",
     *     description="Created at",
     *     example="2020-01-27 17:50:45",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var \DateTime
     */
    private $created_at;

    /**
     * @OA\Property(
     *     title="Updated at",
     *     description="Updated at",
     *     example="2020-01-27 17:50:45",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var \DateTime
     */
    private $updated_at;
}
