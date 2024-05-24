<?php

namespace Webkul\RestApi\Docs\Models\Lead;

/**
 * @OA\Schema(
 *     title="Lead Source",
 *     description="Lead Source Model",
 * )
 */
class Source
{
    /**
     * @OA\Property(
     *     property="id",
     *     description="ID of the Lead Source",
     *     format="int64",
     *     example="1"
     * )
     *
     * @var int
     */
    private $id;

    /**
     * @OA\Property(
     *     property="name",
     *     description="Name of the lead",
     *     example="Phone"
     * )
     *
     * @var string
     */
    private $name;
}
