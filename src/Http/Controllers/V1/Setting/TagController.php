<?php

namespace Webkul\RestApi\Http\Controllers\V1\Setting;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Prettus\Repository\Criteria\RequestCriteria;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Request\MassDestroyRequest;
use Webkul\RestApi\Http\Resources\V1\Setting\TagResource;
use Webkul\Tag\Repositories\TagRepository;

class TagController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected TagRepository $tagRepository) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = $this->allResources($this->tagRepository);

        return TagResource::collection($tags);
    }

    /**
     * Show resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $resource = $this->findOrFailResource($this->tagRepository, $id);

        return new TagResource($resource);
    }

    /**
     * Search tag results
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        $tags = $this->tagRepository
            ->pushCriteria(app(RequestCriteria::class))
            ->limit(request()->input('limit') ?? 10)
            ->all();

        return TagResource::collection($tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'name' => 'required|unique:tags,name',
        ]);

        Event::dispatch('settings.tag.create.before');

        $tag = $this->tagRepository->create(array_merge([
            'user_id' => auth()->guard()->user()->id,
        ], request()->all()));

        Event::dispatch('settings.tag.create.after', $tag);

        return new JsonResource([
            'data'    => new TagResource($tag),
            'message' => trans('rest-api::app.settings.tags.create-success'),
        ]);
    }

    /**
     * Update the specified tag in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->findOrFailResource($this->tagRepository, $id);

        $this->validate(request(), [
            'name' => 'required|unique:tags,name,'.$id,
        ]);

        Event::dispatch('settings.tag.update.before', $id);

        $tag = $this->tagRepository->update(request()->all(), $id);

        Event::dispatch('settings.tag.update.after', $tag);

        return new JsonResource([
            'data'    => new TagResource($tag),
            'message' => trans('rest-api::app.settings.tags.update-success'),
        ]);
    }

    /**
     * Remove the specified type from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->destroyResource(
            $this->tagRepository,
            $id,
            'rest-api::app.settings.tags.delete-success',
            'settings.tag',
            'rest-api::app.settings.tags.delete-failed',
        );
    }

    /**
     * Mass Delete the specified resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest)
    {
        $result = $this->massDestroyResources(
            $this->tagRepository,
            $massDestroyRequest->input('indices', []),
            'settings.tag',
        );

        if ($result['deleted'] === 0) {
            return $this->respondError(trans('rest-api::app.common.nothing-to-delete'), 404);
        }

        return $this->respondSuccess(trans('rest-api::app.settings.tags.delete-success'));
    }
}
