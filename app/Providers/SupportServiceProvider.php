<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use League\Geotools\Geohash\Geohash;
use Src\Core\Additional\QTSMS;
use Src\Core\Additional\YandexBusiness;
use Src\Core\Geo\Geo;
use Src\Core\Utils\ImageCommon;
use Src\Exceptions\ExceptHelpers;
use Src\Support\Filters\Captcha;
use Src\Support\Filters\CompanyHasTariffRegion;
use Src\Support\Filters\ExistsAddress;
use Src\Support\Filters\ExistsJson;
use Src\Support\Filters\ExistsPassword;
use Src\Support\Filters\ExistsRedis;
use Src\Support\Filters\HasExistsDriver;
use Src\Support\Filters\HasExistsPersonalAdmin;
use Src\Support\Filters\HasExistsSystemWorker;
use Src\Support\Filters\NonExists;
use Src\Support\Filters\UniquePhone;
use Src\Support\Filters\ValidAddressFilter;

use function is_array;
use function is_object;

/**
 * Class SupportServiceProvider
 * @package App\Providers
 */
class SupportServiceProvider extends ServiceProvider
{
    ///////////////////////////////////////////////////////////////////////////////////////////////
    //                                          BOOTED                                          //
    /////////////////////////////////////////////////////////////////////////////////////////////
    public function boot(): void
    {
        $this->filtersRegister();
    }

    /** @noinspection OffsetOperationsInspection */
    public function filtersRegister(): void
    {
        new ExistsRedis($this->app['validator']);
        new HasExistsSystemWorker($this->app['validator']);
        new HasExistsPersonalAdmin($this->app['validator']);
        new HasExistsDriver($this->app['validator']);
        new ExistsJson($this->app['validator']);
        new ExistsPassword($this->app['validator']);
        new ExistsAddress($this->app['validator']);
        new UniquePhone($this->app['validator']);
        new ValidAddressFilter($this->app['validator']);
        new CompanyHasTariffRegion($this->app['validator']);
        new NonExists($this->app['validator']);
        new Captcha($this->app['validator']);
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////
    //                                          REGISTERED                                      //
    /////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->collectsRegister();
        $this->facadesRegister();
    }

    /**
     *
     */
    protected function collectsRegister(): void
    {
        Collection::make(glob(app_path('Support/Collections/').'*.php'))
            ->mapWithKeys(fn($path) => [$path => pathinfo($path, PATHINFO_FILENAME)])
            ->reject(fn($macro) => Collection::hasMacro($macro))
            ->each(
                function ($macro) {
                    $class = 'Src\\Support\\Collections\\'.$macro;
                    Collection::macro(Str::camel($macro), app($class)());

                    Collection::macro('recToRec', function () {
                        return $this->map(function ($value) {
                            if (is_array($value) || is_object($value)) {
                                return collect($value)->recToRec();
                            }
                            return $value;
                        });
                    });
                }
            );
    }

    /**
     *
     */
    protected function facadesRegister(): void
    {
        $this->app->bind('HandlerFacade', fn() => new ExceptHelpers());
        $this->app->bind('Geo', fn() => new Geo());
        $this->app->bind('Geohash', fn() => new Geohash());
        $this->app->bind('Sms', fn() => new QTSMS());
        $this->app->bind('ExtYandex', fn() => new YandexBusiness());
        $this->app->bind('Image', fn() => new ImageCommon());
    }
}
