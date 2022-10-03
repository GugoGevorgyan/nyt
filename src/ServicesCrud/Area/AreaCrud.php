<?php

declare(strict_types=1);


namespace Src\ServicesCrud\Area;

use JsonException;
use Src\Repositories\Area\AreaContract;
use Src\ServicesCrud\BaseCrud;

/**
 * Class AreaCrud
 * @package Src\ServicesCrud\Area
 */
class AreaCrud extends BaseCrud implements AreaCrudContract
{
    /**
     * AreaCrud constructor.
     * @param  AreaContract  $areaContract
     */
    public function __construct(protected AreaContract $areaContract)
    {
    }

    /**
     * @param $requestData
     * @return object|null
     * @throws JsonException
     */
    public function create(array $requestData): ?object
    {
        $data = [
            'title' => $requestData['title'],
            'description' => $requestData['description'],
            'area' => $requestData['area'],
            'name' => $this->generateName($requestData['title'])
        ];

        return $this->areaContract->create(decode($data));
    }

    /**
     * @param $title
     * @param  int  $num
     * @return string|string[]
     */
    protected function generateName($title, $num = 0): array|string
    {
        $name = str_replace(' ', '_', mb_strtoupper($title));

        if ($this->areaContract->where('name', '=', $name)->findFirst()) {
            return $this->generateName($title.' '.$num, $num + 1);
        }

        return $name;
    }

    /**
     * @inheritdoc
     * @throws JsonException
     */
    public function update(array $requestData, $area_id): bool|object
    {
        $area = $this->areaContract->findOrFail($area_id);

        if (!$area) {
            return false;
        }

        $data = [
            'title' => $requestData['title'],
            'description' => $requestData['description'],
            'area' => $requestData['area'],
            'name' => $this->generateName($requestData['title'])
        ];

        return $this->areaContract->update($area_id, decode($data));
    }

    /**
     * @inheritdoc
     */
    public function delete($area_id): bool|object
    {
        return $this->areaContract->delete($area_id);
    }
}
