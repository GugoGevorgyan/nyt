<?php

declare(strict_types=1);


namespace Src\Core\Complex;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Src\Core\Enums\ConstApiKey;
use Src\Core\Traits\Complex;
use Src\Repositories\ApiKey\ApiKeyContract;

use function count;

/**
 * Class GetRightYKey
 * @property ApiKeyContract $apiKeyContract
 * @property  Client $client
 * @package Src\Core\Complex
 * @method static complex(int $type = 1, string $old_key = null)
 */
final class GetRightYKey
{
    use Complex;

    /**
     * GetRightYKey constructor.
     * @param  int  $type
     * @param  string|null  $oldKey
     */
    public function __construct(protected int $type = 1, protected ?string $oldKey = null)
    {
    }

    /**
     * Class Complex
     * @param  ApiKeyContract  $apiKeyContract
     * @param  Client  $client
     * @return array|string|null
     * @throws GuzzleException
     * @package Src\Core
     * @method handle()
     */
    public function handle(ApiKeyContract $apiKeyContract, Client $client): array|string|null
    {
        switch ($this->type) {
            case ConstApiKey::Y_GEOCODE()->getValue():
                $keys = $this->apiKeyContract->where('type', '=', ConstApiKey::Y_GEOCODE()->getValue())->findFirst();
                $geocode = $this->iterateKey($keys);
                break;
            case ConstApiKey::Y_ROUTE()->getValue():
                $keys = $this->apiKeyContract->where('type', '=', ConstApiKey::Y_ROUTE()->getValue())->findFirst();
                $geocode = $this->iterateKey($keys);
                break;
            case ConstApiKey::Y_MATRIX()->getValue():
                $keys = $this->apiKeyContract->where('type', '=', ConstApiKey::Y_MATRIX()->getValue())->findFirst();
                $geocode = $this->iterateKey($keys);
                break;
            case ConstApiKey::Y_MAP()->getValue():
                $keys = $this->apiKeyContract->where('type', '=', ConstApiKey::Y_MAP()->getValue())->findFirst();
                $geocode = $this->iterateKey($keys);
                break;
            case ConstApiKey::GBD()->getValue():
                $keys = $this->apiKeyContract->where('type', '=', ConstApiKey::GBD()->getValue())->findFirst();
                $geocode = $this->iterateKey($keys);
                break;
            default:
        }

        return $geocode ?? null;
    }

    /**
     * @param $keys
     * @return array|string|string[]|void|null
     * @throws GuzzleException
     */
    protected function iterateKey($keys)
    {
        $geocode = null;
        $iterate = 0;

        if (!$keys) {
            return;
        }

        foreach ($keys->params as $key) {
            if (true === $key['used']) {
                $geocode = str_replace(['$[version]', '$[key]'], [$key['version'], $key['key']], $keys->url);

                if ($this->oldKey && !$this->checkKey($geocode.'geocode=Москва,+Тверская+улица,+дом+7')) {
                    $this->iterateKey($keys);
                }

                break;
            }
        }

        foreach ($keys->params as $new_key_value => $new_key) {
            $iterate++;
            $geocode = str_replace(['$[version]', '$[key]'], [$new_key['version'], $new_key['key']], $keys->url);

            if (!$this->checkKey($geocode.'geocode=Москва,+Тверская+улица,+дом+7')) {
                if (count($keys->params) === $iterate) {
                    break;
                }

                continue;
            }

            $this->updateUsedKey($keys, $new_key_value);

            break;
        }

        return $geocode;
    }

    /**
     * @param $geocode
     * @return bool|null
     * @throws GuzzleException
     */
    protected function checkKey($geocode): bool
    {
        try {
            return !(200 !== $this->client->get($geocode)->getStatusCode());
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * @param $keys
     * @param $key_val
     */
    protected function updateUsedKey($keys, $key_val): void
    {
        $params = $keys->params;

        foreach ($params as $param_key => $param) {
            $params[$param_key]['used'] = false;
        }

        $params[$key_val]['used'] = true;
        $this->apiKeyContract->where('api_key_id', '=', $keys->api_key_id)->updateSet(['params' => $params]);
    }
}
