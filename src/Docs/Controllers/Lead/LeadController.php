<?php

namespace Webkul\RestApi\Docs\Controllers\Lead;

class LeadController
{
    /**
     * @OA\Get(
     *     path="/api/v1/leads",
     *     operationId="leadList",
     *     tags={"Leads"},
     *     summary="Get list of leads",
     *     security={ {"sanctum_admin": {} }},
     *
     *     @OA\Parameter(
     *          name="sort",
     *          description="Sort column",
     *          example="id",
     *          required=false,
     *          in="query",
     *
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *      @OA\Parameter(
     *          name="order",
     *          description="Sort order",
     *          required=false,
     *          in="query",
     *
     *          @OA\Schema(
     *              type="string",
     *              enum={"desc", "asc"}
     *          )
     *      ),
     *
     *      @OA\Parameter(
     *          name="page",
     *          description="Page number",
     *          required=false,
     *          in="query",
     *
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *      @OA\Parameter(
     *          name="limit",
     *          description="Limit",
     *          in="query",
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
     *               @OA\Property(
     *                  property="data",
     *                  type="array",
     *
     *                  @OA\Items(ref="#/components/schemas/Lead")
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
     *      path="/api/v1/leads/{id}",
     *      operationId="getParticularLead",
     *      tags={"Leads"},
     *      summary="Get the particular Lead",
     *      description="Get the particular Lead",
     *      security={ {"sanctum_admin": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Lead ID",
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
     *                  ref="#/components/schemas/Lead"
     *              )
     *          )
     *      )
     * )
     */
    public function show()
    {
    }

    /**
     * @OA\Post(
     *      path="/api/v1/leads",
     *      operationId="storeLead",
     *      tags={"Leads"},
     *      summary="Store the Leads",
     *      description="Store the Leads",
     *      security={ {"sanctum_admin": {} }},
     *
     *      @OA\RequestBody(
     *
     *          @OA\MediaType(
     *              mediaType="application/json",
     *
     *              @OA\Schema(
     *
     *                  @OA\Property(
     *                      property="title",
     *                      type="string",
     *                      description="Title",
     *                      example="Leads via phone"
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string",
     *                      description="Description",
     *                      example="Leads comes via phone call."
     *                  ),
     *                  @OA\Property(
     *                      property="lead_value",
     *                      type="string",
     *                      description="Lead Value",
     *                      example="55446"
     *                  ),
     *                  @OA\Property(
     *                      property="lead_pipeline_stage_id",
     *                      nullable=true,
     *                      example=null,
     *                      description="Lead Pipeline state ID",
     *                  ),
     *                  @OA\Property(
     *                       property="lead_source_id",
     *                       description="The source of the lead. Possible values:
     *                           1: Email
     *                           2: Web
     *                           3: Web Form
     *                           4: Phone
     *                           5: Direct
     *                      ",
     *                      example="1",
     *                      enum={1, 2, 3, 4, 5}
     *                  ),
     *                  @OA\Property(
     *                      property="lead_type_id",
     *                      type="integer",
     *                       description="The source of the lead. Possible values:
     *                           1: New Business
     *                           2: Existing Business
     *                      ",
     *                      example="1",
     *                      enum={1, 2}
     *                  ),
     *                  @OA\Property(
     *                      property="user_id",
     *                      type="integer",
     *                      description="User ID",
     *                      example="1"
     *                  ),
     *                  @OA\Property(
     *                      property="expected_close_date",
     *                      format="date",
     *                      type="date",
     *                      description="Expected Close Date",
     *                      example="2024-05-25"
     *                  ),
     *                  @OA\Property(
     *                      property="person",
     *                      description="Details of the person",
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string",
     *                          example="John Doe",
     *                     ),
     *                  @OA\Property(
     *                       property="emails",
     *                       type="array",
     *                       description="List of person email addresses",
     *
     *                       @OA\Items(
     *                           type="object",
     *
     *                           @OA\Property(
     *                               property="value",
     *                               type="string",
     *                               description="Email address",
     *                               example="John@mail.com"
     *                           ),
     *                           @OA\Property(
     *                               property="label",
     *                               type="string",
     *                               description="Email address",
     *                               example="work"
     *                           )
     *                        ),
     *                     ),
     *                   @OA\Property(
     *                       property="contact_numbers",
     *                       type="array",
     *                       description="List of person contacts",
     *
     *                       @OA\Items(
     *                           type="object",
     *
     *                           @OA\Property(
     *                               property="value",
     *                               type="string",
     *                               description="Email address",
     *                               example="9645785245"
     *                           ),
     *                           @OA\Property(
     *                               property="label",
     *                               type="string",
     *                               description="Email address",
     *                               example="work"
     *                           )
     *                        ),
     *                     ),
     *                    @OA\Property(
     *                          property="organization_id",
     *                          type="string",
     *                          nullable=true,
     *                          example=null
     *                     ),
     *                  ),
     *                  @OA\Property(
     *                      property="products",
     *                      type="object",
     *                      @OA\Property(
     *                          property="product_0",
     *                          type="object",
     *                          @OA\Property(property="name", type="string", example="iphone-14"),
     *                          @OA\Property(property="product_id", type="string", example="1"),
     *                          @OA\Property(property="price", type="string", example="423"),
     *                          @OA\Property(property="quantity", type="integer", example="324"),
     *                      ),
     *                  ),
     *                  @OA\Property(
     *                      property="entity_type",
     *                      type="string",
     *                      example="leads"
     *                  ),
     *                  required={"title", "description", "lead_value", "lead_source_id", "lead_type_id", "person[name]"}
     *              ),
     *          ),
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="message", type="string", example="Lead Created Successfully."),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/Lead")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      )
     * )
     */
    public function store()
    {
    }

    /**
     * @OA\Put(
     *      path="/api/v1/leads/{id}",
     *      operationId="updateLeads",
     *      tags={"Leads"},
     *      summary="Store the Leads",
     *      description="Store the Leads",
     *      security={ {"sanctum_admin": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Lead ID",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *      @OA\RequestBody(
     *
     *          @OA\MediaType(
     *              mediaType="application/json",
     *
     *              @OA\Schema(
     *
     *                  @OA\Property(
     *                      property="title",
     *                      type="string",
     *                      description="Title",
     *                      example="Leads via phone"
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string",
     *                      description="Description",
     *                      example="Leads comes via phone call."
     *                  ),
     *                  @OA\Property(
     *                      property="lead_value",
     *                      type="string",
     *                      description="Lead Value",
     *                      example="55446"
     *                  ),
     *                  @OA\Property(
     *                      property="lead_pipeline_stage_id",
     *                      nullable=true,
     *                      example=null,
     *                      description="Lead Pipeline state ID",
     *                  ),
     *                  @OA\Property(
     *                       property="lead_source_id",
     *                       description="The source of the lead. Possible values:
     *                           1: Email
     *                           2: Web
     *                           3: Web Form
     *                           4: Phone
     *                           5: Direct
     *                      ",
     *                      example="1",
     *                      enum={1, 2, 3, 4, 5}
     *                  ),
     *                  @OA\Property(
     *                      property="lead_type_id",
     *                      type="integer",
     *                       description="The source of the lead. Possible values:
     *                           1: New Business
     *                           2: Existing Business
     *                      ",
     *                      example="1",
     *                      enum={1, 2}
     *                  ),
     *                  @OA\Property(
     *                      property="user_id",
     *                      type="integer",
     *                      description="User ID",
     *                      example="1"
     *                  ),
     *                  @OA\Property(
     *                      property="expected_close_date",
     *                      format="date",
     *                      type="date",
     *                      description="Expected Close Date",
     *                      example="2024-05-25"
     *                  ),
     *                  @OA\Property(
     *                      property="person",
     *                      description="Details of the person",
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string",
     *                          example="John Doe",
     *                     ),
     *                     @OA\Property(
     *                          property="id",
     *                          type="string",
     *                          example="1",
     *                     ),
     *                  @OA\Property(
     *                       property="emails",
     *                       type="array",
     *                       description="List of person email addresses",
     *
     *                       @OA\Items(
     *                           type="object",
     *
     *                           @OA\Property(
     *                               property="value",
     *                               type="string",
     *                               description="Email address",
     *                               example="John@mail.com"
     *                           ),
     *                           @OA\Property(
     *                               property="label",
     *                               type="string",
     *                               description="Email address",
     *                               example="work"
     *                           )
     *                        ),
     *                     ),
     *                   @OA\Property(
     *                       property="contact_numbers",
     *                       type="array",
     *                       description="List of person contacts",
     *
     *                       @OA\Items(
     *                           type="object",
     *
     *                           @OA\Property(
     *                               property="value",
     *                               type="string",
     *                               description="Email address",
     *                               example="9645785245"
     *                           ),
     *                           @OA\Property(
     *                               property="label",
     *                               type="string",
     *                               description="Email address",
     *                               example="work"
     *                           )
     *                        ),
     *                     ),
     *                    @OA\Property(
     *                          property="organization_id",
     *                          type="string",
     *                          nullable=true,
     *                          example=null
     *                     ),
     *                  ),
     *                  @OA\Property(
     *                      property="products",
     *                      description="Details of the Products",
     *                      type="object",
     *                  ),
     *                  @OA\Property(
     *                          property="entity_type",
     *                          type="string",
     *                          example="leads"
     *                     ),
     *                  required={"title", "description", "lead_value", "lead_source_id", "lead_type_id", "person[name]"}
     *              ),
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="message", type="string", example="Lead Updated Successfully."),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/Lead")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      )
     * )
     */
    public function update()
    {
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/leads/{id}",
     *      operationId="deleteLeads",
     *      tags={"Leads"},
     *      summary="Delete the Leads",
     *      description="Delete the Leads",
     *      security={ {"sanctum_admin": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Lead ID",
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
     *              @OA\Property(property="message", type="string", example="Lead Deleted Successfully.")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      )
     * )
     */
    public function destroy()
    {
    }

    /**
     * @OA\Post(
     *      path="/api/v1/leads/mass-update",
     *      operationId="massUpdateLeads",
     *      tags={"Leads"},
     *      summary="Mass update Leads",
     *      description="Mass update Leads",
     *      security={ {"sanctum_admin": {} }},
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(
     *                  property="indices",
     *                  type="array",
     *                  description="IDs of the Leads to be updated",
     *
     *                  @OA\Items(
     *                      type="integer",
     *                      example=1
     *                  )
     *              ),
     *
     *              @OA\Property(
     *                  property="value",
     *                  type="string",
     *                  description="Value to be update",
     *                  example="1"
     *              )
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
     *                  property="message",
     *                  type="string",
     *                  example="Leads updated successfully."),
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
     *      path="/api/v1/leads/mass-destroy",
     *      operationId="massDeleteLeads",
     *      tags={"Leads"},
     *      summary="Mass delete Leads",
     *      description="Mass delete Leads",
     *      security={ {"sanctum_admin": {} }},
     *
     *      @OA\RequestBody(
     *
     *          @OA\MediaType(
     *              mediaType="application/json",
     *
     *              @OA\Schema(
     *
     *                  @OA\Property(
     *                      property="indices",
     *                      description="Leads's Ids `CommaSeparated`",
     *                      type="string",
     *                      example={1}
     *                  ),
     *                  required={"indices"}
     *              )
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
     *                  property="message",
     *                  type="string",
     *                  example="Leads deleted successfully."),
     *              )
     *          )
     *      )
     * )
     */
    public function massDestroy()
    {
    }
}
