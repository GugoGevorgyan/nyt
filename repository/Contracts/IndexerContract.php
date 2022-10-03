<?php

declare(strict_types=1);

namespace Repository\Contracts;

use Illuminate\Database\Eloquent\Collection;

/**
 *
 */
interface IndexerContract
{
    /**
     * Update documents.
     *
     * @param  Collection  $models
     * @return array
     */
    public function update(Collection $models);

    /**
     * Delete documents.
     *
     * @param  Collection  $models
     * @return array
     */
    public function delete(Collection $models);
}
