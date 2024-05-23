<?php

namespace Webkul\RestApi\Docs\Controllers\Lead;

use Illuminate\Http\Request;

class LeadController
{
    /**
     * @OA\Get(
     *     path="/api/v1/leads",
     *     tags={"Leads"},
     *     summary="Get list of leads",
     *     @OA\Response(
     *         response=200,
     *         description="A list with leads",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     example="John Doe"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     example="john.doe@example.com"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index(Request $request)
    {
        // Your code to get and return leads
    }
}
