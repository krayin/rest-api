<?php

namespace Webkul\RestApi\Docs\Models\Quote;

/**
 * @OA\Schema(
 *     title="Quote",
 *     description="Quote Model",
 * )
 */
class Quote
{
    /**
     * @OA\Property(
     *     title="Description",
     *     description="Quote Description",
     *     example="This is a quote description.",
     * )
     *
     * @var string
     */
    private $description;

    /**
     * @OA\Property(
     *     title="Billing Address",
     *     description="Quote Billing Address",
     *     example="This is a quote billing address.",
     * )
     *
     * @var string
     */
    private $billing_address;

    /**
     * @OA\Property(
     *     title="Shipping Address",
     *     description="Quote Shipping Address",
     *     example="This is a quote shipping address.",
     * )
     *
     * @var string
     */
    private $shipping_address;

    /**
     * @OA\Property(
     *     title="Discount Percent",
     *     description="Quote Discount Percent",
     *     example="This is a quote discount percent.",
     * )
     *
     * @var string
     */
    private $discount_percent;

    /**
     * @OA\Property(
     *     title="Discount Amount",
     *     description="Quote Discount Amount",
     *     example="This is a quote discount amount.",
     * )
     *
     * @var string
     */
    private $discount_amount;

    /**
     * @OA\Property(
     *     title="Tax Amount",
     *     description="Quote Tax Amount",
     *     example="This is a quote tax amount.",
     * )
     *
     * @var string
     */
    private $tax_amount;

    /**
     * @OA\Property(
     *     title="Adjustment Amount",
     *     description="Quote Adjustment Amount",
     *     example="This is a quote adjustment amount.",
     * )
     *
     * @var string
     */
    private $adjustment_amount;

    /**
     * @OA\Property(
     *     title="Sub Total",
     *     description="Quote Sub Total",
     *     example="This is a quote sub total.",
     * )
     *
     * @var string
     */
    private $sub_total;

    /**
     * @OA\Property(
     *     title="Grand Total",
     *     description="Quote Grand Total",
     *     example="This is a quote grand total.",
     * )
     *
     * @var string
     */
    private $grand_total;

    /**
     * @OA\Property(
     *     title="Expired At",
     *     description="Quote Expired At",
     *     example="This is a quote expired at.",
     * )
     *
     * @var string
     */
    private $expired_at;

    /**
     * @OA\Property(
     *     title="User ID",
     *     description="Quote User ID",
     *     example="This is a quote user id.",
     * )
     *
     * @var string
     */
    private $user_id;

    /**
     * @OA\Property(
     *     title="Person ID",
     *     description="Quote Person ID",
     *     example="This is a quote person id.",
     * )
     *
     * @var string
     */
    private $person_id;
}
