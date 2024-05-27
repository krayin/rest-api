<?php

namespace Webkul\RestApi\Docs\Controllers\Settings\Tags;


class TagController
{
    /**
     * @OA\Get(
     *      path="/api/v1/settings/tags",
     *      operationId="tagList",
     *      tags={"Settings"},
     *      summary="Get list of leads",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *               @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/Tag")
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
     * @OA\Post(
     *      path="/api/v1/settings/tags",
     *      operationId="storeTag",
     *      tags={"Settings"},
     *      summary="Store the Tags",
     *      description="Store the Tags",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="name",
     *                      description="Tag Name",
     *                      type="string",
     *                      example="Active"
     *                  ),
     *                  @OA\Property(
     *                      property="color",
     *                      description="Select the color for the tag",
     *                      type="string",
     *                      example="#FEBF00",
     *                      enum={"#337CFF", "#FEBF00", "#E5549F", "#27B6BB", "#FB8A3F", "#43AF52"}
     *                  ),
     *                  required={"name"},
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
     *                  example="Tags added successfully.",
     *              )
     *          )
     *      )
     * )
     */
    public function store()
    {
    }

    /**
     * @OA\Get(
     *      path="/api/v1/settings/tags/{id}",
     *      operationId="showTag",
     *      tags={"Settings"},
     *      summary="Get Tag by id",
     *      description="Get Tag by id",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="Tag Id",
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
     *                  ref="#/components/schemas/Tag"
     *              )
     *          )
     *      )
     * )
     */
    public function show()
    {
    }

    /**
     * @OA\Put(
     *      path="/api/v1/settings/tags/{id}",
     *      operationId="updateTag",
     *      tags={"Settings"},
     *      summary="Update the Tags",
     *      description="Update the Tags",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="Tag Id",
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
     *                      property="color",
     *                      description="Select the color for the tag",
     *                      type="string",
     *                      example="#FEBF00",
     *                      enum={"#337CFF", "#FEBF00", "#E5549F", "#27B6BB", "#FB8A3F", "#43AF52"}
     *                  ),
     *                  required={"name"},
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
     *                  example="Tags updated successfully.",
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
     *      path="/api/v1/settings/tags/{id}",
     *      operationId="deleteTag",
     *      tags={"Settings"},
     *      summary="Delete the Tags",
     *      description="Delete the Tags",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="Tag Id",
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
     *                  example="Tags deleted successfully.",
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
     *      path="/api/v1/settings/tags/mass-destroy",
     *      operationId="massDestroyTag",
     *      tags={"Settings"},
     *      summary="Mass Delete the Tags",
     *      description="Mass Delete the Tags",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="rows",
     *                      description="Tag Ids",
     *                      type="array",
     *                      @OA\Items(
     *                          type="integer",
     *                          example="1"
     *                      )
     *                  ),
     *                  required={"rows"},
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
     *                  example="Tags deleted successfully.",
     *              )
     *          )
     *      )
     * )
     */
    public function massDestroy()
    {
    }
}