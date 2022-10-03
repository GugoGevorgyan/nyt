<?php

declare(strict_types=1);

namespace Src\Core\Traits;

use Exception;
use Ramsey\Uuid\Uuid as RamseyUuid;
use RuntimeException;

/**
 * Trait UUID
 * @package Src\Core\Traits
 */
trait Uuid
{
    /**
     * The "booting" method of the model.
     * @throws Exception
     */
    public static function bootUuid(): void
    {
        static::creating(static function (self $model): void {
            if (empty($model->{$model->getKeyName()}) && $model->keyIsUuid()) {
                $model->{$model->getKeyName()} = $model->generateUuid();
            }
        });
    }

    /**
     * Indicates if the IDs are UUIDs.
     *
     * @return bool
     */
    protected function keyIsUuid(): bool
    {
        return true;
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function generateUuid(): string
    {
        switch ($this->uuidVersion()) {
            case 1:
                return RamseyUuid::uuid1()->toString();
            case 4:
                return RamseyUuid::uuid4()->toString();
            default:
        }

        throw new RuntimeException("UUID version [{$this->uuidVersion()}] not supported.");
    }

    /**
     * The UUID version to use.
     *
     * @return int
     */
    protected function uuidVersion(): int
    {
        return 4;
    }
}
