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
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *               @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  ref="#/components/schemas/Lead"
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
     * @OA\Get(
     *      path="/api/v1/leads/{id}",
     *      operationId="getParticularLead",
     *      tags={"Leads"},
     *      summary="Get the particular Lead",
     *      description="Get the particular Lead",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="Lead ID",
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
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
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
     *                          example="Jhon Doe",
     *                     ),
     *                  @OA\Property(
     *                       property="emails",
     *                       type="array",
     *                       description="List of person email addresses",
     *                       @OA\Items(
     *                           type="object",
     *                           @OA\Property(
     *                               property="value",
     *                               type="string",
     *                               description="Email address",
     *                               example="jhon@mail.com"
     *                           ),
     *                           @OA\Property(
     *                               property="label",
     *                               type="string",
     *                               description="Email adderss",
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
     *                               description="Email adderss",
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
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Leads Created Successfully."),
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
     *      @OA\Parameter(
     *          name="id",
     *          description="Lead ID",
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
     *                          example="Jhon Doe",
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
     *                       @OA\Items(
     *                           type="object",
     *                           @OA\Property(
     *                               property="value",
     *                               type="string",
     *                               description="Email address",
     *                               example="jhon@mail.com"
     *                           ),
     *                           @OA\Property(
     *                               property="label",
     *                               type="string",
     *                               description="Email adderss",
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
     *                               description="Email adderss",
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
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Leads Updated Successfully."),
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
     *      @OA\Parameter(
     *          name="id",
     *          description="Lead ID",
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
     *              @OA\Property(property="message", type="string", example="Leads Deleted Successfully.")
     *          )
     *      ),
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
     *      path="/api/v1/leads/mass-destroy",
     *      operationId="massDeleteLeads",
     *      tags={"Leads"},
     *      summary="Mass delete Leads",
     *      description="Mass delete Leads",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="rows",
     *                      description="Leads's Ids `CommaSeperated`",
     *                      type="string",
     *                      example={1,2}
     *                  ),
     *                  required={"rows"}
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
     *                  example="Selected leads successfully deleted."),
     *              )
     *          )
     *      )
     * )
     */
    public function massDestroy()
    {
    }
}
