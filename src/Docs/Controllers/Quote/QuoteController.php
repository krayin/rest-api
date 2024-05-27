<?php

namespace Webkul\RestApi\Docs\Controllers\Quote;

class QuoteController
{
    /**
     * @OA\Get(
     *      path="/api/v1/quotes",
     *      operationId="quoteList",
     *      tags={"Quotes"},
     *      summary="Get list of quotes",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *               @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/Quote")
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
     * @OA\Post(
     *      path="/api/v1/quotes",
     *      operationId="storeQuote",
     *      tags={"Quotes"},
     *      summary="Store the Quote",
     *      description="Store the Quote",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                   @OA\Property(
     *                        property="description",
     *                        description="Quote Description",
     *                        type="string",
     *                        example="This is a quote description."
     *                    ),
     *                    @OA\Property(
     *                        property="billing_address",
     *                        description="Quote Billing Address",
     *                        type="string",
     *                        example="California, USA."
     *                    ),
     *                    @OA\Property(
     *                        property="shipping_address",
     *                        description="Quote Shipping Address",
     *                        type="string",
     *                        example="California, USA."
     *                    ),
     *                    @OA\Property(
     *                        property="discount_percent",
     *                        description="Quote Discount Percent",
     *                        type="string",
     *                        example="10%"
     *                    ),
     *                    @OA\Property(
     *                        property="tax_amount",
     *                        description="Quote tax Amount",
     *                        type="string",
     *                        example="10"
     *                    ),
     *                    @OA\Property(
     *                        property="adjustment_amount",
     *                        description="Quote adjustment Amount",
     *                        type="string",
     *                        example="10"
     *                    ),
     *                    @OA\Property(
     *                        property="sub_total",
     *                        description="Sub Total",
     *                        type="string",
     *                        example="100"
     *                    ),
     *                    @OA\Property(
     *                        property="grand_total",
     *                        description="Quote Discount Amount",
     *                        type="string",
     *                        example="100"
     *                    ),
     *                    @OA\Property(
     *                        property="expired_at",
     *                        description="Quote Discount Amount",
     *                        type="string",
     *                        example="25-02-2002"
     *                    ),
     *                    @OA\Property(
     *                        property="user_id",
     *                        description="Quote Discount Amount",
     *                        type="string",
     *                        example="1"
     *                    ),
     *                    @OA\Property(
     *                        property="person_id",
     *                        description="Quote Discount Amount",
     *                        type="string",
     *                        example="1"
     *                    ),
     *                    @OA\Property(
     *                        property="discount_amount",
     *                        description="Quote Discount Amount",
     *                        type="string",
     *                        example="10"
     *                    ),
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
}
