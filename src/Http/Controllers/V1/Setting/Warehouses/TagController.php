<?php

namespace Webkul\RestApi\Http\Controllers\V1\Setting\Warehouses;

use Illuminate\Support\Facades\Event;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\Warehouse\Repositories\WarehouseRepository;

class TagController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected WarehouseRepository $warehouseRepository) {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function attach($id)
    {
        Event::dispatch('warehouse.tag.create.before', $id);

        $warehouse = $this->warehouseRepository->find($id);

        if (! $warehouse->tags->contains(request()->input('tag_id'))) {
            $warehouse->tags()->attach(request()->input('tag_id'));
        }

        Event::dispatch('warehouse.tag.create.after', $warehouse);

        return response()->json([
            'message' => trans('rest-api::app.settings.warehouses.view.tags.create-success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $warehouseId
     * @return \Illuminate\Http\Response
     */
    public function detach($warehouseId)
    {
        Event::dispatch('warehouse.tag.delete.before', $warehouseId);

        $warehouse = $this->warehouseRepository->find($warehouseId);

        $warehouse->tags()->detach(request()->input('tag_id'));

        Event::dispatch('warehouse.tag.delete.after', $warehouse);

        return response()->json([
            'message' => trans('rest-api::app.settings.warehouses.view.tags.delete-success'),
        ]);
    }
}
