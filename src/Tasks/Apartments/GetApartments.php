<?php

namespace Selena\Tasks\Apartments;

use Psr\Http\Client\ClientInterface;
use Selena\Resources\Front\FrontApi;
use Selena\Tasks\TaskContract;

/*
 * Получить список апартаментов
 */

class GetApartments implements TaskContract
{
    /*
     * ID объекта размещения
     *
     * @var integer
     */
    protected int $objectid;
    /*
     * ID палубы
     *
     * @var integer[]
     */
    protected array $unitids;
    /*
     * Init
     *
     * @param integer $objectid
     * @param integer $tourid
     * @param integer[] $unitids
     */
    public function __construct(int $objectid, array $unitids = [])
    {
        $this->objectid = $objectid;

        $this->unitids = $unitids;
    }
    /*
     * Get tag name for cache
     *
     * @return string
     */
    public function tag()
    {
        $unitids = implode("_", $this->unitids);

        $class = preg_replace("/\//", "_", self::class);

        return $class . "_{$this->objectid}_{$unitids}";
    }
    /*
     * Get callable
     *
     * @return callable
     */
    public function get()
    {
        return function (ClientInterface $client) {

            try {

                $frontApi = new FrontApi($client);

                $result = [];

                $apartments = [];

                $apartmentQuery = ["objectid" => $this->objectid];
                /**
                 * Получение апартаметов по палубам
                 */
                if (!empty($this->unitids)) {

                    foreach ($this->unitids as $id) {

                        $apartmentQuery["unitid"] = $id;

                        $aps = $frontApi->apartmentList($apartmentQuery)["apartments"] ?? [];

                        $apartments = array_merge($apartments, $aps);
                    }
                } else {

                    $apartments = $frontApi->apartmentList($apartmentQuery)["apartments"] ?? [];
                }

                $result = $apartments;
            } catch (\Exception $exception) {

                $result = null;
            }

            return $result;
        };
    }
}
