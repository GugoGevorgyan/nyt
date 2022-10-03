<?php

declare(strict_types=1);

namespace Src\Console\Commands;

use Illuminate\Console\Command;

class DriverCordCleaner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cord:cleaner';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and delete driver coordinates oldest';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->info('Old cords deleted');
    }
}
