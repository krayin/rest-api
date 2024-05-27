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
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
    *                  @OA\Property(
     *                      property="name",
     *                      description="Tag Name",
     *                      type="string",
     *                      example="Active"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      description="Email",
     *                      type="string",
     *                      example="john@doe.com"
     *                  ),
     *                  @OA\Property(
     *                      property="status",
     *                      description="Status",
     *                      type="string",
     *                      example="1"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      description="Password",
     *                      type="string",
     *                      example="admin123"
     *                  ),
     *                  @OA\Property(
     *                      property="confirm_password",
     *                      description="confirm_password",
     *                      type="string",
     *                      example="admin123"
     *                  ),
     *                  @OA\Property(
     *                      property="role_id",
     *                      description="Role ID",
     *                      type="string",
     *                      example="1"
     *                  ),
     *                  @OA\Property(
     *                      property="view_permission",
     *                      description="View Permission",
     *                      type="string",
     *                      example="global"
     *                  ),
     *              )
     *          )
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

    /**
     * @OA\Put(
     *      path="/api/v1/settings/users/{id}",
     *      operationId="updateUser",
     *      tags={"User"},
     *      summary="Update the Users",
     *      description="Update the Users",
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
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="name",
     *                      description="Tag Name",
     *                      type="string",
     *                      example="Active"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      description="Email",
     *                      type="string",
     *                      example="john@doe.com"
     *                  ),
     *                  @OA\Property(
     *                      property="status",
     *                      description="Status",
     *                      type="string",
     *                      example="1"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      description="Password",
     *                      type="string",
     *                      example="admin123"
     *                  ),
     *                  @OA\Property(
     *                      property="confirm_password",
     *                      description="confirm_password",
     *                      type="string",
     *                      example="admin123"
     *                  ),
     *                  @OA\Property(
     *                      property="role_id",
     *                      description="Role ID",
     *                      type="string",
     *                      example="1"
     *                  ),
     *                  @OA\Property(
     *                      property="view_permission",
     *                      description="View Permission",
     *                      type="string",
     *                      example="global"
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="User updated successfully.",
     *              )
     *          )
     *      )
     * )
     */
    public function update()
    {
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/settings/users/{id}",
     *      operationId="deleteUser",
     *      tags={"User"},
     *      summary="Delete the Users",
     *      description="Delete the Users",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="User Id",
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
     *                  property="message",
     *                  type="string",
     *                  example="User deleted successfully.",
     *              )
     *          )
     *      )
     * )
     */
    public function destroy()
    {
    }

    /**
     * @OA\Post(
     *      path="/api/v1/settings/users/mass-update",
     *      operationId="massUpdateUser",
     *      tags={"User"},
     *      summary="Mass Update the Users",
     *      description="Mass Update the Users",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="rows",
     *                      description="User Ids",
     *                      type="array",
     *                      @OA\Items(
     *                          type="integer",
     *                          example="1"
     *                      )
     *                  ),
     *                  @OA\Property(
     *                      property="value",
     *                      description="Status Value",
     *                      type="string",
     *                      example="1"
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="User updated successfully.",
     *              )
     *          )
     *      )
     * )
     */
    public function massUpdate()
    {
    }

    /**
     * @OA\Post(
     *      path="/api/v1/settings/users/mass-destroy",
     *      operationId="massDestroyUser",
     *      tags={"User"},
     *      summary="Mass Delete the Users",
     *      description="Mass Delete the Users",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="rows",
     *                      description="User Ids",
     *                      type="array",
     *                      @OA\Items(
     *                          type="integer",
     *                          example="1"
     *                      )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="User deleted successfully.",
     *              )
     *          )
     *      )
     * )
     */
    public function massDestroy()
    {
    }
}
