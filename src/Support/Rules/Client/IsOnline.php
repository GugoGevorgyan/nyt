<?php

declare(strict_types=1);

namespace Src\Support\Rules\Client;

use Src\Repositories\Client\ClientContract;
use Illuminate\Contracts\Validation\Rule;

/**
 * Class IsOnline
 * @package Src\Support\Rules\Client
 */
class IsOnline implements Rule
{
    protected ClientContract $clientContract;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->clientContract = app(ClientContract::class);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param $phone
     * @return bool
     */
    public function passes($attribute, $phone)
    {
        $client = $this->clientContract->where('phone', '=', $phone)->where('online', '=', 0)->findFirst();

        if (!$client) {
            return true;
        }

        if ($client->online) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You are not phone allowed';
    }
}
