<?php

namespace Webkul\RestApi\Docs\Models\Settings;

/**
 * @OA\Schema(
 *     title="Tag",
 *     description="Tag Model",
 * )
 */
class Tag
{
    /**
     * @OA\Property(
     *     title="name",
     *     description="Name of the tag",
     *     example="Active",
     *     type="string"
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @OA\Property(
     *     title="color",
     *     description="Color of the tag",
     *     example="#FEBF00",
     *     type="string"
     * )
     *
     * @var string
     */
    private $color;

    /**
     * @OA\Property(
     *     title="user_id",
     *     description="User ID",
     * )
     *
     * @var \Webkul\RestApi\Docs\Models\User\User
     */
    private $user_id;
}
