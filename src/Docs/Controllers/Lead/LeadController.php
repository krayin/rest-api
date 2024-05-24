<?php

namespace Webkul\RestApi\Docs\Controllers\Lead;

use Illuminate\Http\Request;

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
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *
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
     *                      type="integer",
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
     *                               example="jhone@mail.com"
     *                           ),
     *                           @OA\Property(
     *                               property="label",
     *                               type="string",
     *                               description="Email adderss",
     *                               example="work"
     *                           )
     *                        ),
     *                     ),
     *                  ),
     *                  required={"title", "description", "lead_value", "lead_source_id", "lead_type_id", "person[name]"}
     *              ),
     *          )
     *      ),
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
}