<?php

declare(strict_types=1);

namespace Src\Jobs;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Src\Core\Additional\Guzzle;

/**
 *
 */
class Https implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected mixed $payload, protected string $method = 'GET', protected array $options_data = [])
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws GuzzleException
     */
    public function handle(): void
    {
        (new Guzzle())->request($this->method, $this->payload, $this->options_data);
    }
}
