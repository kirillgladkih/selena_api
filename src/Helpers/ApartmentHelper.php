<?php

namespace Selena\Helpers;

class ApartmentHelper
{
    /**
     * Получить разрешенные категории для мест из битовой карты
     *
     * @param string $map
     * @param array $ageCategories
     * @return array
     */
    public static function getAllowAges(string $map, array $ageCategories): array
    {
        $result = [];

        $allows = array_map(fn($value) => (int) $value, str_split($map, 1));

        foreach($allows as $key => $allow) if($allow) $result[] = $ageCategories[$key];

        return $result;
    }
}
