<?php

declare(strict_types=1);

namespace Src\Http\Controllers\AdminSuper\Stations;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Src\Http\Controllers\Controller;

/**
 * Class AirportController
 * @package Src\Http\Controllers\AdminSuper\Stations
 */
class StationController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('admin-super.stations.station');
    }
}
