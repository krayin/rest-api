<?php

namespace Webkul\RestApi\Docs\Models\Contact;

/**
 * @OA\Schema(
 *     title="Person",
 *     description="Person Model",
 * )
 */
class Person
{
        /**
     * @OA\Property(
     *     title="ID",
     *     description="ID of the lead",
     *     format="int64",
     *     example=1
     * )
     *
     * @var string
     */
    private $id;

    /**
     * @OA\Property(
     *     title="Name",
     *     description="Name of the lead",
     *     example="Jhon Doe"
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @OA\Property(
     *     title="Email",
     *     description="Email of the lead",
     *     example="john@doe.com"
     * )
     *
     * @var string
     */
    private $email;

    /*
     * @OA\Property(
     *    property="contact_numbers",
     *    type="array",
     *    description="Contact Numbers",
     *    @OA\Items(
     *        type="object",
     *        @OA\Property(
     *            property="label",
     *            type="string",
     *            description="Label for the contact number (e.g., 'work', 'home')"
     *        ),
     *        @OA\Property(
     *            property="value",
     *            type="string",
     *            description="The contact number"
     *        )
     *    ),
     *    example={
     *        {"label": "work", "value": "9999999999"},
     *        {"label": "home", "value": "8888888888"}
     *    }
     *)
     */
    private $contact_numbers;

    /**
     * @OA\Property(
     *     title="Organization",
     *     description="Organization",
     *     example={
     *       {"label": "work", "value": "9999999999"},
     *       {"label": "home", "value": "8888888888"}
     *    }
     * )
     *
     * @var object
     */
    private $organization_id;

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