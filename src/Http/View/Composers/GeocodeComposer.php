<?php

declare(strict_types=1);


namespace Src\Http\View\Composers;


use Illuminate\View\View;
use ReflectionException;
use Src\Core\Complex\GetRightYKey;
use Src\Core\Enums\ConstApiKey;

/**
 * Class GeocodeComposer
 * @package Src\Http\View\Composers
 */
class GeocodeComposer
{
    public function __construct()
    {
    }

    /**
     * @param  View  $view
     * @throws ReflectionException
     */
    public function compose(View $view): void
    {
        $geocode = GetRightYKey::complex(ConstApiKey::Y_MAP()->getValue());

        $view->with('geocode', $geocode);
    }
}
