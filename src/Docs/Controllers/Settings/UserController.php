<?php

namespace Webkul\RestApi\Docs\Controllers\Settings;

class UserController
{
    /**
     *  @OA\Get(
     *      path="/api/v1/settings/users",
     *      operationId="userList",
     *      tags={"User"},
     *      summary="Get list of users",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *               @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/User")
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index()
    {
    }

    /**
     *  @OA\Get(
     *      path="/api/v1/settings/users/{id}",
     *      operationId="userFetch",
     *      tags={"User"},
     *      summary="Get user details",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="User ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  ref="#/components/schemas/User"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function show()
    {
    }

    /**
     *  @OA\Post(
     *      path="/api/v1/settings/users",
     *      operationId="userCreate",
     *      tags={"User"},
     *      summary="Create new user",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *               @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/User")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function store()
    {
    }
}
