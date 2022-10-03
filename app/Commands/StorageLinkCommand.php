<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Foundation\Console\StorageLinkCommand as StorageLink;

class StorageLinkCommand extends StorageLink
{
    /**
     * Get the symbolic links that are configured for the application.
     *
     * @return array
     */
    protected function links(): array
    {
        /** @noinspection OffsetOperationsInspection */
        return $this->laravel['config']['filesystems.links'] ?? [public_path('storage') => storage_path('public')];
    }
}
