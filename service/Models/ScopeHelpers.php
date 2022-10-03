<?php

declare(strict_types=1);


namespace ServiceEntity\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

use function defined;

/**
 * Class ScopeHelpers
 * @package ServiceEntity\Models
 */
trait ScopeHelpers
{
    /////////////////////////////////////////////////////STATUS, TYPE, CLASS///////////////////////////////////////////

    /**
     * @param $status
     * @return Builder|Model|object|ServiceModel|null
     */
    public static function getStatusId($status)
    {
        return (new static())->newModelQuery()->where('status', '=', $status)->first([(new static())->getKeyName(), 'status'])->{(new static())->getKeyName()};
    }

    /**
     * @param $type
     * @param  string  $attribute
     * @return int|string
     */
    public static function getTypeId($type, string $attribute = 'type'): int|string
    {
        return (new static())->newModelQuery()->where($attribute, '=', $type)
            ->first([(new static())->getKeyName(), $attribute])
            ->{(new static())->getKeyName()};
    }

    /**
     * @param $class
     * @return mixed
     */
    public static function getClassId($class)
    {
        return (new static())->newModelQuery()->where('type', '=', $class)->first([(new static())->getKeyName(), 'class'])->{(new static())->getKeyName()};
    }


    ///////////////////////////////////////////////////////////HELPERS/////////////////////////////////////////////////

    /**
     * @param $query
     * @param  array  $values
     * @return array|void
     */
    public function scopeExcept($query, array $values = [])
    {
        $attributes = static::first();

        if (!$attributes) {
            return;
        }

        $attributes = $attributes->getAttributes();
        $diff_data = array_diff(array_keys($attributes), array_values($values));

        return $query->select($diff_data);
    }

    /**
     * @param  Model  $model
     * @return Collection
     */
    public function mergeAttributes(Model $model): Collection
    {
        return collect(array_merge($this->getAttributes(), $model->getAttributes()));
    }

    /**
     * @param  Builder  $query
     * @param $latitude
     * @param $longitude
     * @param  int  $distance
     * @return Builder
     */
    public function scopeDistanceCord(Builder $query, $latitude, $longitude, int $distance = 1): Builder
    {
        $angle_radius = $distance / 111;

        $min_lat = $latitude - $angle_radius;
        $max_lat = $latitude + $angle_radius;
        $min_lon = $longitude - $angle_radius;
        $max_lon = $longitude + $angle_radius;

        $lat_column = $this->getLatitudeColumn();
        $lut_column = $this->getLongitudeColumn();

        return $query->whereRaw("$lat_column BETWEEN ".$min_lat.' AND '.$max_lat." AND $lut_column BETWEEN ".$min_lon.' AND '.$max_lon.'    ');
    }

    /**
     * @return string
     */
    public function getLatitudeColumn(): string
    {
        return defined('static::LATITUDE') ? static::LATITUDE : 'latitude';
    }

    /**
     * @return string
     */
    public function getLongitudeColumn(): string
    {
        return defined('static::LONGITUDE') ? static::LONGITUDE : 'longitude';
    }

    /**
     * @param $query
     * @param $latitude
     * @param $longitude
     * @param $inner_radius
     * @param $outer_radius
     * @return Builder
     */
    public function scopeGeofence(Builder $query, $latitude, $longitude, $inner_radius, $outer_radius): Builder
    {
        $query = $this->scopeCordDistance($query, $latitude, $longitude);
        return $query->havingRaw('distance BETWEEN ? AND ?', [$inner_radius, $outer_radius]);
    }

    /**
     * @param  Builder  $query
     * @param $latitude
     * @param $longitude
     * @return Builder
     */
    public function scopeCordDistance(Builder $query, $latitude, $longitude): Builder
    {
        $latName = $this->getLatitudeColumn();
        $lonName = $this->getLongitudeColumn();

        if (null === $query->getQuery()->columns) {
            $query->select($this->getTable().'.*');
        } else {
            $query->select($query->getQuery()->columns);
        }

        $kilometers = property_exists(static::class, 'kilometers') && static::$kilometers;

        if ($kilometers) {
            $sql =
                'ROUND(((ACOS(SIN(? * PI() / 180) * SIN('.$latName.' * PI() / 180) + COS(? * PI() / 180) *
                COS('.$latName.' * PI() / 180) * COS((? - '.$lonName.') * PI() / 180)) * 180 / PI()) * 60 * ?), 1) as distance';

            $query->selectRaw($sql, [$latitude, $latitude, $longitude, 1.1515 * 1.609344]);
        } else {
            $sql =
                'ROUND(((ACOS(SIN(? * PI() / 180) * SIN('.$latName.' * PI() / 180) + COS(? * PI() / 180) *
                COS('.$latName.' * PI() / 180) * COS((? - '.$lonName.') * PI() / 180)) * 180 / PI()) * 60 * ?), 2) * 1000 as distance';

            $query->selectRaw($sql, [$latitude, $latitude, $longitude, 1.1515]);
        }

        return $query;
    }

    /**
     * @param  Builder  $query
     * @param $latitude
     * @param $longitude
     * @param $distance
     * @return Builder
     */
    public function scopeDisatnceCordsss(Builder $query, $latitude, $longitude, $distance): Builder
    {
        $sql = "(6371 * ACOS(COS(radians($latitude))
                      * COS(radians(lat))
                      * COS(radians(lut) - radians($longitude))
                      + SIN(radians($latitude))
                      * SIN(radians(lat)))
                ) AS distance HAVING distance < $distance";

        $query->selectRaw($sql, [$latitude, $longitude, $latitude, $distance]);

        return $query;
    }
}
