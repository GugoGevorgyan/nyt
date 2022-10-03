<?php

declare(strict_types=1);

namespace Src\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;
use JsonException;
use Src\Repositories\Address\AddressContract;
use Src\Repositories\AddressDetail\AddressDetailContract;
use Src\Repositories\AddressRoute\AddressRouteContract;
use Src\Repositories\BeforeAuthClient\BeforeAuthClientContract;

/**
 * Class AddressMonitorWriter
 * @package Src\Console\Commands
 */
class AddressMonitorWriter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:address-writer';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Write all addresses table in to file';

    /**
     * Create a new command instance.
     *
     * @param  AddressContract  $addressContract
     * @param  AddressDetailContract  $addressDetailContract
     * @param  AddressRouteContract  $addressRouteContract
     * @param  BeforeAuthClientContract  $beforeClientContract
     */
    public function __construct(
        protected AddressContract $addressContract,
        protected AddressDetailContract $addressDetailContract,
        protected AddressRouteContract $addressRouteContract,
        protected BeforeAuthClientContract $beforeClientContract
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws FileNotFoundException
     * @throws JsonException
     */
    public function handle(): void
    {
        $path_address = DS.'monitor'.DS.'addresses.json';
        $path_detail = DS.'monitor'.DS.'details.json';
        $path_routes = DS.'monitor'.DS.'routes.json';

        $addresses = $this->addressContract->findAll()->toJson();
        $details = $this->addressDetailContract->findAll()->toJson();
        $routes = $this->addressRouteContract->findAll()->toJson();

        if (!$addresses) :
            return;
        endif;

        $result_address = [];
        $result_detail = [];
        $result_route = [];

        if (!empty(Storage::disk('local')->get($path_address))) :
            array_merge($result_address, json_decode(Storage::disk('local')->get($path_address), true, 512, JSON_THROW_ON_ERROR));
        endif;

        if (!empty(Storage::disk('local')->get($path_detail))) :
            array_merge($result_detail, json_decode(Storage::disk('local')->get($path_detail), true, 512, JSON_THROW_ON_ERROR));
        endif;

        if (!empty(Storage::disk('local')->get($path_routes))) :
            array_merge($result_route, json_decode(Storage::disk('local')->get($path_routes), true, 512, JSON_THROW_ON_ERROR));
        endif;

        Storage::disk('local')->put($path_address, $addresses);
        Storage::disk('local')->put($path_detail, $details);
        Storage::disk('local')->put($path_routes, $routes);

        $this->beforeClientContract->where('created_at', '>', now()->subDays())->deletes();

        $this->info('Addresses && Routes saved in files');
    }
}
