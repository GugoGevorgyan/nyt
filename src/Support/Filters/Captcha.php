<?php

declare(strict_types=1);


namespace Src\Support\Filters;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use JsonException;

/**
 * Class Captcha
 * @package Src\Support\Filters
 */
class Captcha extends BaseFilter
{
    /**
     * @return mixed
     * @throws JsonException
     * @throws GuzzleException
     */
    public function filter()
    {
        $this->app->extend(
            'captcha',
            static function ($attribute, $value, $coordinate, $validator) {
                $response = (new Client())->post(
                    config('nyt.captcha_check_url'),
                    [
                        'form_params' => [
                            'secret' => config('nyt.captcha_secret'),
                            'response' => $value,
                            'remoteip' => (new Request())->ip(),
                        ],
                    ]
                );

                $response = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
                return $response['success'];
            }
        );
    }
}
