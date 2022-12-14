<?php

declare(strict_types=1);

namespace ServiceEntity\GeoIP;

use Exception;
use Illuminate\Cache\CacheManager;
use Illuminate\Support\Arr;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use ServiceEntity\GeoIP\Contracts\ServiceInterface;

class GeoIP
{
    /**
     * Illuminate config repository instance.
     *
     * @var array
     */
    protected $config;
    /**
     * Remote Machine IP address.
     *
     * @var float
     */
    protected $remoteIp = null;
    /**
     * Current location instance.
     *
     * @var Location
     */
    protected $location = null;
    /**
     * Currency data.
     *
     * @var array
     */
    protected $currencies = null;
    /**
     * GeoIP service instance.
     *
     * @var Contracts\ServiceInterface
     */
    protected $service;
    /**
     * Cache manager instance.
     *
     * @var CacheManager
     */
    protected $cache;
    /**
     * Default Location data.
     *
     * @var array
     */
    protected $defaultLocation = [
        'ip' => '127.0.0.0',
        'iso_code' => 'US',
        'country' => 'United States',
        'city' => 'New Haven',
        'state' => 'CT',
        'state_name' => 'Connecticut',
        'postal_code' => '06510',
        'lat' => 41.31,
        'lon' => -72.92,
        'timezone' => 'America/New_York',
        'continent' => 'NA',
        'currency' => 'USD',
        'default' => true,
        'cached' => false,
    ];

    /**
     * Create a new GeoIP instance.
     *
     * @param  array  $config
     * @param  CacheManager  $cache
     */
    public function __construct(array $config, CacheManager $cache)
    {
        $this->config = $config;

        // Create caching instance
        $this->cache = new Cache(
            $cache,
            $this->config('cache_tags'),
            $this->config('cache_expires', 30)
        );

        // Set custom default location
        $this->defaultLocation = array_merge(
            $this->defaultLocation,
            $this->config('default_location', [])
        );

        // Set IP
        $this->defaultLocation['ip'] = $this->getClientIP();
        $this->remoteIp = $this->defaultLocation['ip'];
    }

    /**
     * Get configuration value.
     *
     * @param  string  $key
     * @param  mixed  $default
     *
     * @return mixed
     */
    public function config($key, $default = null)
    {
        return Arr::get($this->config, $key, $default);
    }

    /**
     * Get the client IP address.
     *
     * @return string
     */
    public function getClientIP()
    {
        $remotes_keys = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_CLIENT_IP',
            'HTTP_X_REAL_IP',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
            'HTTP_X_CLUSTER_CLIENT_IP',
        ];

        foreach ($remotes_keys as $key) {
            if ($address = getenv($key)) {
                foreach (explode(',', $address) as $ip) {
                    if ($this->isValid($ip)) {
                        return $ip;
                    }
                }
            }
        }

        return '127.0.0.0';
    }

    /**
     * Checks if the ip is valid.
     *
     * @param  string  $ip
     *
     * @return bool
     */
    private function isValid($ip)
    {
        return !(!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
            && !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE));
    }

    /**
     * Get the location from the provided IP.
     *
     * @param  string  $ip
     *
     * @return Location
     * @throws Exception
     */
    public function getLocation($ip = null)
    {
        // Get location data
        $this->location = $this->find($ip);

        // Should cache location
        if ($this->shouldCache($this->location, $ip)) {
            $this->getCache()->set($ip, $this->location);
        }

        return $this->location;
    }

    /**
     * Find location from IP.
     *
     * @param  string  $ip
     *
     * @return Location
     * @throws Exception
     */
    private function find($ip = null)
    {
        // If IP not set, user remote IP
        $ip = $ip ?: $this->remoteIp;

        // Check cache for location
        if ('none' !== $this->config('cache', 'none') && $location = $this->getCache()->get($ip)) {
            $location->cached = true;

            return $location;
        }

        // Check if the ip is not local or empty
        if ($this->isValid($ip)) {
            try {
                // Find location
                $location = $this->getService()->locate($ip);

                // Set currency if not already set by the service
                if (!$location->currency) {
                    $location->currency = $this->getCurrency($location->iso_code);
                }

                // Set default
                $location->default = false;

                return $location;
            } catch (Exception $e) {
                if (true === $this->config('log_failures', true)) {
                    $log = new Logger('geoip');
                    $log->pushHandler(new StreamHandler(storage_path('logs/GeoIP.log'), Logger::ERROR));
                    $log->error($e);
                }
            }
        }

        return $this->getService()->hydrate($this->defaultLocation);
    }

    /**
     * Get cache instance.
     *
     * @return Cache
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * Get service instance.
     *
     * @return ServiceInterface
     * @throws Exception
     */
    public function getService()
    {
        if (null === $this->service) {
            // Get service configuration
            $config = $this->config('services.'.$this->config('service'), []);

            // Get service class
            $class = Arr::pull($config, 'class');

            // Sanity check
            if (null === $class) {
                throw new Exception('The GeoIP service is not valid.');
            }

            // Create service instance
            $this->service = new $class($config);
        }

        return $this->service;
    }

    /**
     * Get the currency code from ISO.
     *
     * @param  string  $iso
     *
     * @return string
     */
    public function getCurrency($iso)
    {
        if (null === $this->currencies && $this->config('include_currency', false)) {
            $this->currencies = include(__DIR__.'/Support/Currencies.php');
        }

        return Arr::get($this->currencies, $iso);
    }

    /**
     * Determine if the location should be cached.
     *
     * @param  Location  $location
     * @param  string|null  $ip
     *
     * @return bool
     */
    private function shouldCache(Location $location, $ip = null)
    {
        if (true === $location->default || true === $location->cached) {
            return false;
        }

        return match ($this->config('cache', 'none')) {
            'all', 'some' && null === $ip => true,
            default => false,
        };
    }
}
