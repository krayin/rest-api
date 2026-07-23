<?php

namespace Webkul\RestApi\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;

/**
 * Throwaway Eloquent model backed by an in-memory sqlite table, used to drive
 * the list-query sanitizer without pulling in the full Krayin CRM.
 */
class Widget extends Model
{
    protected $table = 'widgets';

    public $timestamps = false;

    protected $guarded = [];
}
