<?php

declare(strict_types=1);

namespace Src\Http\Controllers\AdminSuper;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\AdminSuper\CreateAreaRequest;
use Src\Http\Requests\AdminSuper\UpdateAreaRequest;
use Src\ServicesCrud\Area\AreaCrudContract;

/**
 *
 */
class AreaController extends Controller
{
    /**
     * AreaController constructor.
     * @param  AreaCrudContract  $areaCrud
     */
    public function __construct(protected AreaCrudContract $areaCrud)
    {
    }

    /**
     * @return Application|Factory|View
     */
    public function index(): Factory|View|Application
    {
        return view('admin-super.area.index');
    }

    /**
     * @param  CreateAreaRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function create(CreateAreaRequest $request): Response|Application|ResponseFactory
    {
        $area = $this->areaCrud->create($request->all());

        return $area ?
            response(['message' => 'Tariff successfully created', 'area' => $area]) :
            response(['message' => 'Oops! Something went wrong.'], 500);
    }

    /**
     * @param  UpdateAreaRequest  $request
     * @param $area_id
     * @return Application|ResponseFactory|Response
     */
    public function update(UpdateAreaRequest $request, $area_id): Response|Application|ResponseFactory
    {
        if (!$this->areaCrud->update($request->all(), $area_id)) {
            return response(['message' => 'Oops! Something went wrong.'], 500);
        }
        return response(['message' => 'Tariff successfully updated']);
    }

    /**
     * @param $area_id
     * @return Application|ResponseFactory|Response
     */
    public function destroy($area_id): Response|Application|ResponseFactory
    {
        if (!$this->areaCrud->delete($area_id)) {
            return response(['message' => 'Oops! Something went wrong.'], 500);
        }

        return response(['message' => 'Tariff successfully deleted']);
    }
}
