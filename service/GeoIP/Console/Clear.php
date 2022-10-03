<?php

declare(strict_types=1);

namespace ServiceEntity\GeoIP\Console;

use Illuminate\Console\Command;

class Clear extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'GeoIP:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear GeoIP cached locations.';

    /**
     * Execute the console command for Laravel 5.5 and newer.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->fire();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        if (false === $this->isSupported()) {
            return $this->output->error('Default cache system does not support tags');
        }

        $this->performFlush();
    }

    /**
     * Is cache flushing supported.
     *
     * @return bool
     */
    protected function isSupported(): bool
    {
        return false === empty(app('geoip')->config('cache_tags'))
            && false === \in_array(config('cache.default'), ['file', 'database']);
    }

    /**
     * Flush the cache.
     *
     * @return void
     */
    protected function performFlush()
    {
        $this->output->write('Clearing cache...');

        app('geoip')->getCache()->flush();

        $this->output->writeln('<info>complete</info>');
    }
}
