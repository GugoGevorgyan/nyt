<?php

declare(strict_types=1);


namespace Src\Core\Complex;

use Carbon\Carbon;
use Illuminate\Support\Str;
use JsonException;
use Src\Core\Additional\Guzzle;
use Src\Core\Enums\ConstApiKey;
use Src\Core\Traits\Complex;
use Src\Repositories\ApiKey\ApiKeyContract;


/**
 * Class Acquiring
 * @package Src\Core\Complex
 * @property ApiKeyContract $apiKeyContract
 */
final class Acquiring
{
    use Complex;

    /**
     * @var string
     */
    private static string $apiUrl = '';
    /**
     * @var string
     */
    private static string $apiMerchantId = '';
    /**
     * @var string
     */
    private static string $apiKey = '';


    /**
     * @param  string  $requestType
     * @param  string  $methodName
     * @param  array  $params
     */
    public function __construct(private string $requestType, private string $methodName, private array $params = [])
    {
    }

    /**
     * @param ApiKeyContract $apiKeyContract
     *
     * @return array
     * @throws JsonException
     */
    public function handle(ApiKeyContract $apiKeyContract): array
    {
        if (!self::$apiKey) {
            $api_data = $this->apiKeyContract->where('type', '=', ConstApiKey::ACQUIRING_API_TEST()->getValue())->findFirst();

            self::$apiKey = $api_data->params['key'];
            self::$apiMerchantId = $api_data->params['merchant_id'];
            self::$apiUrl = str_replace('$[version]', $api_data->params['version'], $api_data->url);
        }

        return $this->request();
    }


    /**
     * @return array
     */
    private function request(): array
    {
        $order_id = !empty($this->params['order_id']) ? $this->params['order_id'] : Str::uuid()->toString();

        $first_params = [
            'merchant' => self::$apiMerchantId,
            'order_id' => $order_id
        ];

        $last_params = [
            'timestamp' => Carbon::now('UTC')->toISOString()
        ];


        $form_params = $first_params + $this->params + $last_params;

        $signed_form_params = self::signParams($form_params);

        $client = new Guzzle();

        $response = $client->request($this->requestType, self::$apiUrl . $this->methodName, [
            'form_params' => $signed_form_params
        ]);

        $response_body = $response->getBody()->getContents();

        return json_decode($response_body, true, 512, JSON_THROW_ON_ERROR);
    }


    /**
     * @param array $params
     *
     * @return array
     */
    private static function signParams(array $params): array
    {
        $keys_and_values = '';

        foreach ($params as $key => $param) {
            $keys_and_values .= $key . $param;
        }

        $sign_param = [
            'sign' => hash_hmac('sha1', $keys_and_values, self::$apiKey)
        ];

        return $params + $sign_param;
    }
}
