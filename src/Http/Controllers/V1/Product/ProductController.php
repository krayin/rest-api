<?php

namespace Webkul\RestApi\Http\Controllers\V1\Product;

use Illuminate\Support\Facades\Event;
use Webkul\Attribute\Http\Requests\AttributeForm;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Resources\V1\Product\ProductResource;

class ProductController extends Controller
{
    /**
     * Product repository instance.
     *
     * @var \Webkul\Product\Repositories\ProductRepository
     */
    protected $productRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @return void
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;

        $this->addEntityTypeInRequest('products');
    }

    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->allResources($this->productRepository);

        return ProductResource::collection($products);
    }

    /**
     * Show resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $resource = $this->productRepository->find($id);

        return new ProductResource($resource);
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Webkul\Attribute\Http\Requests\AttributeForm $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttributeForm $request)
    {
        Event::dispatch('product.create.before');

        $product = $this->productRepository->create(request()->all());

        Event::dispatch('product.create.after', $product);

        return response([
            'data'    => new ProductResource($product),
            'message' => __('admin::app.products.create-success'),
        ]);
    }

    /**
     * Update the product in storage.
     *
     * @param  \Webkul\Attribute\Http\Requests\AttributeForm  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AttributeForm $request, $id)
    {
        Event::dispatch('product.update.before', $id);

        $product = $this->productRepository->update($request->all(), $id);

        Event::dispatch('product.update.after', $product);

        return response([
            'data'    => new ProductResource($product),
            'message' => __('admin::app.products.update-success'),
        ]);
    }

    /**
     * Remove the product from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Event::dispatch('settings.products.delete.before', $id);

            $this->productRepository->delete($id);

            Event::dispatch('settings.products.delete.after', $id);

            return response([
                'message' => __('admin::app.response.destroy-success', ['name' => __('admin::app.products.product')]),
            ]);
        } catch (\Exception $exception) {
            return response([
                'message' => __('admin::app.response.destroy-failed', ['name' => __('admin::app.products.product')]),
            ], 500);
        }
    }

    /**
     * Mass delete the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        foreach (request('rows') as $productId) {
            Event::dispatch('product.delete.before', $productId);

            $this->productRepository->delete($productId);

            Event::dispatch('product.delete.after', $productId);
        }

        return response([
            'message' => __('admin::app.response.destroy-success', ['name' => __('admin::app.products.title')]),
        ]);
    }
}
