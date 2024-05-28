<?php

namespace Webkul\RestApi\Docs\Controllers\Settings;

class WebFormController
{
    /**
     *  @OA\Get(
     *      path="/api/v1/settings/web-forms",
     *      operationId="webFormList",
     *      tags={"WebForm"},
     *      summary="Get list of WebForm",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *               @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/WebForm")
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
     *      path="/api/v1/settings/web-forms/{id}",
     *      operationId="webFormFind",
     *      tags={"WebForm"},
     *      summary="Find WebForm by ID",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of WebForm to return",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  ref="#/components/schemas/WebForm"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="WebForm not found"
     *      )
     *  )
     */
    public function show()
    {
    }

    /**
     *  @OA\Post(
     *      path="/api/v1/settings/web-forms",
     *      operationId="webFormCreate",
     *      tags={"WebForm"},
     *      summary="Create WebForm",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/WebForm")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  ref="#/components/schemas/WebForm"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      )
     *  )
     */
    public function store()
    {
    }

    /**
     *  @OA\Put(
     *      path="/api/v1/settings/web-forms/{id}",
     *      operationId="webFormUpdate",
     *      tags={"WebForm"},
     *      summary="Update WebForm",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of WebForm to return",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/WebForm")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  ref="#/components/schemas/WebForm"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="WebForm not found"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      )
     *  )
     */
    public function update()
    {
    }

    /**
     *  @OA\Delete(
     *      path="/api/v1/settings/web-forms/{id}",
     *      operationId="webFormDelete",
     *      tags={"WebForm"},
     *      summary="Delete WebForm",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of WebForm to return",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Web form deleted successfully",
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="WebForm not found"
     *      )
     *  )
     */
    public function destroy()
    {
    }
}
