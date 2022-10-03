<?php

declare(strict_types=1);

namespace Src\Http\Controllers\App;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Src\Http\Controllers\Controller;

/**
 * Class TariffController
 * @package Src\Http\Controllers\App
 */
class TariffController extends Controller
{

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('soon.index');
    }
}
