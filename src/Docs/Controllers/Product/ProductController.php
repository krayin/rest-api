<?php

namespace Webkul\RestApi\Docs\Controllers\Product;

class ProductController
{
    /**
     * @OA\Get(
     *      path="/api/v1/products",
     *      operationId="productList",
     *      tags={"Products"},
     *      summary="Get list of products",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *               @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/Product")
     *              )
     *          )
     *      ),
     *
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
     * @OA\Get(
     *      path="/api/v1/products/{id}",
     *      operationId="productShow",
     *      tags={"Products"},
     *      summary="Get product details",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="Product ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\Property(
     *             property="data",
     *             type="Object",
     *             ref="#/components/schemas/Product"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function show()
    {
    }

    /**
     * @OA\Post(
     *      path="/api/v1/products",
     *      operationId="productCreate",
     *      tags={"Products"},
     *      summary="Create new product",
     *      security={ {"sanctum_admin": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product details",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="Product name",
     *                 example="lorem"
     *             ),
     *             @OA\Property(
     *                 property="description",
     *                 type="string",
     *                 description="Product Description",
     *                 example="lorem ipsum"
     *             ),
     *             @OA\Property(
     *                 property="sku",
     *                 type="string",
     *                 description="Product Sku",
     *                 example="lorem-ipsum"
     *             ),
     *             @OA\Property(
     *                 property="quantity",
     *                 type="string",
     *                 description="Product Quantity",
     *                 example="1"
     *             ),
     *             @OA\Property(
     *                 property="price",
     *                 type="string",
     *                 description="Product Price",
     *                 example="25"
     *             ),
     *             @OA\Property(
     *                 property="entity_type",
     *                 type="string",
     *                 description="Product Type",
     *                 example="products"
     *             ),
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *               @OA\Property(
     *                  property="data",
     *                  type="Object",
     *                  ref="#/components/schemas/Product"
     *              )
     *          )
     *      ),
     *
     *     @OA\Response(
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
     *      path="/api/v1/products/{id}",
     *      operationId="productUpdate",
     *      tags={"Products"},
     *      summary="Update existing product",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="Product ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product details",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="Product name",
     *                 example="lorem"
     *             ),
     *             @OA\Property(
     *                 property="description",
     *                 type="string",
     *                 description="Product Description",
     *                 example="lorem ipsum"
     *             ),
     *             @OA\Property(
     *                 property="sku",
     *                 type="string",
     *                 description="Product Sku",
     *                 example="lorem-ipsum"
     *             ),
     *             @OA\Property(
     *                 property="quantity",
     *                 type="string",
     *                 description="Product Quantity",
     *                 example="1"
     *             ),
     *             @OA\Property(
     *                 property="price",
     *                 type="string",
     *                 description="Product Price",
     *                 example="25"
     *             ),
     *             @OA\Property(
     *                 property="entity_type",
     *                 type="string",
     *                 description="Product Type",
     *                 example="products"
     *             ),
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *               @OA\Property(
     *                  property="data",
     *                  type="Object",
     *                  ref="#/components/schemas/Product"
     *              )
     *          )
     *      ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *    )
     * )
     */
    public function update()
    {
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/products/{id}",
     *      operationId="productDelete",
     *      tags={"Products"},
     *      summary="Delete existing product",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="Product ID",
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
     *               @OA\Property(
     *                  property="data",
     *                  type="Object",
     *                  ref="#/components/schemas/Product"
     *              )
     *          )
     *      ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function destroy()
    {
    }

    /**
     * @OA\Post(
     *      path="/api/v1/products/mass-destroy",
     *      operationId="productMassDestroy",
     *      tags={"Products"},
     *      summary="Delete existing products",
     *      security={ {"sanctum_admin": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product details",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="rows",
     *                 type="array",
     *                 description="Product IDs",
     *                 @OA\Items(
     *                     type="integer",
     *                     example="1"
     *                 )
     *             ),
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *               @OA\Property(
     *                  property="data",
     *                  type="Object",
     *                  ref="#/components/schemas/Product"
     *              )
     *          )
     *      ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function massDestroy()
    {
    }
}
