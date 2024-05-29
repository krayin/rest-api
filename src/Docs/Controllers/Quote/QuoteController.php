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
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @OA\JsonContent(
     *
     *               @OA\Property(
     *                  property="data",
     *                  type="array",
     *
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
     *
     *      @OA\RequestBody(
     *         required=true,
     *         description="Store Quote",
     *
     *         @OA\JsonContent(
     *
     *              @OA\Property(
     *                  property="description",
     *                  type="string",
     *                  description="Description of the quote",
     *                  example="School Management Quote"
     *              ),
     *              @OA\Property(
     *                  property="expired_at",
     *                  type="string",
     *                  format="date",
     *                  description="Expiration date of the quote",
     *                  example="2024-05-31"
     *              ),
     *              @OA\Property(
     *                  property="person_id",
     *                  type="integer",
     *                  description="ID of the person",
     *                  example=1
     *              ),
     *              @OA\Property(
     *                  property="subject",
     *                  type="string",
     *                  description="Subject of the quote",
     *                  example="Webkul"
     *              ),
     *              @OA\Property(
     *                  property="user_id",
     *                  type="integer",
     *                  description="ID of the user",
     *                  example=1
     *              ),
     *              @OA\Property(
     *                  property="lead_id",
     *                  type="integer",
     *                  description="ID of the lead",
     *                  example=1
     *              ),
     *              @OA\Property(
     *                  property="billing_address",
     *                  description="Billing address details",
     *                  @OA\Property(
     *                      property="address",
     *                      type="string",
     *                      description="Street address",
     *                      example="Bheem Nagar"
     *                  ),
     *                  @OA\Property(
     *                      property="country",
     *                      type="string",
     *                      description="Country code",
     *                      example="IN"
     *                  ),
     *                  @OA\Property(
     *                      property="state",
     *                      type="string",
     *                      description="State code",
     *                      example="UP"
     *                  ),
     *                  @OA\Property(
     *                      property="city",
     *                      type="string",
     *                      description="City name",
     *                      example="Ghaziabad"
     *                  ),
     *                  @OA\Property(
     *                      property="postcode",
     *                      type="string",
     *                      description="Postal code",
     *                      example="201009"
     *                  )
     *              ),
     *              @OA\Property(
     *                  property="shipping_address",
     *                  description="Shipping address details",
     *                  @OA\Property(
     *                      property="address",
     *                      type="string",
     *                      description="Street address",
     *                      example="Bheem Nagar"
     *                  ),
     *                  @OA\Property(
     *                      property="country",
     *                      type="string",
     *                      description="Country code",
     *                      example="IN"
     *                  ),
     *                  @OA\Property(
     *                      property="state",
     *                      type="string",
     *                      description="State code",
     *                      example="UP"
     *                  ),
     *                  @OA\Property(
     *                      property="city",
     *                      type="string",
     *                      description="City name",
     *                      example="Ghaziabad"
     *                  ),
     *                  @OA\Property(
     *                      property="postcode",
     *                      type="string",
     *                      description="Postal code",
     *                      example="201009"
     *                  )
     *              ),
     *              @OA\Property(
     *                  property="items",
     *                  type="object",
     *                  description="List of items",
     *                      @OA\Property(
     *                          property="item_0",
     *                          type="object",
     *                          @OA\Property(
     *                              property="product_id",
     *                              type="string",
     *                              example="1"
     *                          ),
     *                          @OA\Property(
     *                              property="quantity",
     *                              type="string",
     *                              example="100"
     *                          ),
     *                          @OA\Property(
     *                              property="price",
     *                              type="string",
     *                              example="50"
     *                          ),
     *                          @OA\Property(
     *                              property="total",
     *                              type="string",
     *                              example="5000"
     *                          ),
     *                          @OA\Property(
     *                              property="discount_amount",
     *                              type="string",
     *                              example="0"
     *                          ),
     *                          @OA\Property(
     *                              property="tax_amount",
     *                              type="string",
     *                              example="0"
     *                          )
     *                  )
     *              ),
     *              @OA\Property(
     *                  property="sub_total",
     *                  type="number",
     *                  format="float",
     *                  description="Subtotal amount",
     *                  example=5000.0
     *              ),
     *              @OA\Property(
     *                  property="discount_amount",
     *                  type="number",
     *                  format="float",
     *                  description="Discount amount",
     *                  example=0.0
     *              ),
     *              @OA\Property(
     *                  property="tax_amount",
     *                  type="number",
     *                  format="float",
     *                  description="Tax amount",
     *                  example=0.0
     *              ),
     *              @OA\Property(
     *                  property="adjustment_amount",
     *                  type="number",
     *                  format="float",
     *                  description="Adjustment amount",
     *                  example=0.0
     *              ),
     *              @OA\Property(
     *                  property="grand_total",
     *                  type="number",
     *                  format="float",
     *                  description="Grand total amount",
     *                  example=5000.0
     *              ),
     *              @OA\Property(
     *                  property="entity_type",
     *                  type="string",
     *                  description="Type of the entity",
     *                  example="quotes"
     *              )
     *           )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Quote created successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/Quote")
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function store()
    {
    }

    /**
     * @OA\Get(
     *      path="/api/v1/quotes/{id}",
     *      operationId="getQuoteById",
     *      tags={"Quotes"},
     *      summary="Get quote information",
     *      description="Get quote information",
     *      security={ {"sanctum_admin": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Quote Id",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  ref="#/components/schemas/Quote"
     *              )
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=404,
     *          description="Quote not found"
     *      )
     * )
     */
    public function show()
    {
    }

    /**
     * @OA\Put(
     *      path="/api/v1/quotes/{id}",
     *      operationId="updateQuote",
     *      tags={"Quotes"},
     *      summary="Update the Quote",
     *      description="Update the Quote",
     *      security={ {"sanctum_admin": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Quote Id",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *      @OA\RequestBody(
     *         required=true,
     *         description="Store Quote",
     *
     *         @OA\JsonContent(
     *
     *              @OA\Property(
     *                  property="description",
     *                  type="string",
     *                  description="Description of the quote",
     *                  example="School Management Quote"
     *              ),
     *              @OA\Property(
     *                  property="expired_at",
     *                  type="string",
     *                  format="date",
     *                  description="Expiration date of the quote",
     *                  example="2024-05-31"
     *              ),
     *              @OA\Property(
     *                  property="person_id",
     *                  type="integer",
     *                  description="ID of the person",
     *                  example=1
     *              ),
     *              @OA\Property(
     *                  property="subject",
     *                  type="string",
     *                  description="Subject of the quote",
     *                  example="Webkul"
     *              ),
     *              @OA\Property(
     *                  property="user_id",
     *                  type="integer",
     *                  description="ID of the user",
     *                  example=1
     *              ),
     *              @OA\Property(
     *                  property="lead_id",
     *                  type="integer",
     *                  description="ID of the lead",
     *                  example=1
     *              ),
     *              @OA\Property(
     *                  property="billing_address",
     *                  description="Billing address details",
     *                  @OA\Property(
     *                      property="address",
     *                      type="string",
     *                      description="Street address",
     *                      example="Bheem Nagar"
     *                  ),
     *                  @OA\Property(
     *                      property="country",
     *                      type="string",
     *                      description="Country code",
     *                      example="IN"
     *                  ),
     *                  @OA\Property(
     *                      property="state",
     *                      type="string",
     *                      description="State code",
     *                      example="UP"
     *                  ),
     *                  @OA\Property(
     *                      property="city",
     *                      type="string",
     *                      description="City name",
     *                      example="Ghaziabad"
     *                  ),
     *                  @OA\Property(
     *                      property="postcode",
     *                      type="string",
     *                      description="Postal code",
     *                      example="201009"
     *                  )
     *              ),
     *              @OA\Property(
     *                  property="shipping_address",
     *                  description="Shipping address details",
     *                  @OA\Property(
     *                      property="address",
     *                      type="string",
     *                      description="Street address",
     *                      example="Bheem Nagar"
     *                  ),
     *                  @OA\Property(
     *                      property="country",
     *                      type="string",
     *                      description="Country code",
     *                      example="IN"
     *                  ),
     *                  @OA\Property(
     *                      property="state",
     *                      type="string",
     *                      description="State code",
     *                      example="UP"
     *                  ),
     *                  @OA\Property(
     *                      property="city",
     *                      type="string",
     *                      description="City name",
     *                      example="Ghaziabad"
     *                  ),
     *                  @OA\Property(
     *                      property="postcode",
     *                      type="string",
     *                      description="Postal code",
     *                      example="201009"
     *                  )
     *              ),
     *              @OA\Property(
     *                  property="items",
     *                  type="object",
     *                  description="List of items",
     *                      @OA\Property(
     *                          property="item_0",
     *                          type="object",
     *                          @OA\Property(
     *                              property="product_id",
     *                              type="string",
     *                              example="1"
     *                          ),
     *                          @OA\Property(
     *                              property="quantity",
     *                              type="string",
     *                              example="100"
     *                          ),
     *                          @OA\Property(
     *                              property="price",
     *                              type="string",
     *                              example="50"
     *                          ),
     *                          @OA\Property(
     *                              property="total",
     *                              type="string",
     *                              example="5000"
     *                          ),
     *                          @OA\Property(
     *                              property="discount_amount",
     *                              type="string",
     *                              example="0"
     *                          ),
     *                          @OA\Property(
     *                              property="tax_amount",
     *                              type="string",
     *                              example="0"
     *                          )
     *                  )
     *              ),
     *              @OA\Property(
     *                  property="sub_total",
     *                  type="number",
     *                  format="float",
     *                  description="Subtotal amount",
     *                  example=5000.0
     *              ),
     *              @OA\Property(
     *                  property="discount_amount",
     *                  type="number",
     *                  format="float",
     *                  description="Discount amount",
     *                  example=0.0
     *              ),
     *              @OA\Property(
     *                  property="tax_amount",
     *                  type="number",
     *                  format="float",
     *                  description="Tax amount",
     *                  example=0.0
     *              ),
     *              @OA\Property(
     *                  property="adjustment_amount",
     *                  type="number",
     *                  format="float",
     *                  description="Adjustment amount",
     *                  example=0.0
     *              ),
     *              @OA\Property(
     *                  property="grand_total",
     *                  type="number",
     *                  format="float",
     *                  description="Grand total amount",
     *                  example=5000.0
     *              ),
     *              @OA\Property(
     *                  property="entity_type",
     *                  type="string",
     *                  description="Type of the entity",
     *                  example="quotes"
     *              )
     *           )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Quote updated successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/Quote")
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function update()
    {
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/quotes/{id}",
     *      operationId="deleteQuote",
     *      tags={"Quotes"},
     *      summary="Delete the Quote",
     *      description="Delete the Quote",
     *      security={ {"sanctum_admin": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Quote Id",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Quote deleted successfully"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Quote not found"
     *      )
     * )
     */
    public function destroy()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/quotes/mass-destroy",
     *     operationId="massDeleteQuote",
     *     tags={"Quotes"},
     *     summary="Delete the Quote",
     *     description="Delete the Quote",
     *     security={ {"sanctum_admin": {} }},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Quote details",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="rows",
     *                 type="array",
     *                 description="Quote IDs",
     *
     *                 @OA\Items(
     *                     type="integer",
     *                     example="1"
     *                 )
     *             ),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(
     *
     *              @OA\Property(
     *                 property="data",
     *                 type="Object",
     *                 ref="#/components/schemas/Quote",
     *             )
     *         )
     *     ),
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
