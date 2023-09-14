<?php

namespace Selena\Tasks\Apartments;

use Selena\Tasks\TaskContract;

class ApartmentsFilter implements TaskContract
{

    /**
     * @var array
     */
    protected array $apartments;

    /**
     * @var array
     */
    protected array $ages;

    /**
     * @param array $apartments
     * @param array $ages
     */
    public function __construct(array $apartments, array $ages = [])
    {
        $this->apartments = $this->prepare($apartments);

        $this->ages = $ages;

    }

    /**
     * @return array
     */
    public function get(): array
    {
        $ages = $this->ages;

        return array_filter($this->apartments, function ($apartment) use ($ages){

            $placements = $apartment["placements"];

            if(count($placements) >= count($ages)){

                $allows = [];

                foreach ($ages as $age){

                    foreach ($placements as $key => $placement){

                        if(($age >= $placement["from"]) && ($age <= $placement["to"])){

                            $allows[] = $placement;

                            unset($placements[$key]);

                            break;
                        }

                    }

                }

                return count($allows) == count($ages);

            }

            return false;
        });
    }

    /**
     * @param array $apartments
     * @return array
     */
    private function prepare(array $apartments): array
    {

        foreach ($apartments as $key => $apartment) {

            $places = ["main_ages" => $apartment["places"], "child_ages" => $apartment["childplaces"], "add_ages" => $apartment["addplaces"]];

            foreach ($places as $placeKey => $placeCount) {

                for ($i = 0; $i < $placeCount; $i++) {

                    $ages = $this->detectAges($apartment[$placeKey], $apartment["own_ages"]);

                    $apartments[$key]["placements"][] = ["from" => $ages[0], "to" => $ages[1], "code" => $placeKey];

                }

            }

        }

        return $apartments;
    }

    /**
     * @param string $bitMap
     * @param array $ages
     * @return array
     */
    private function detectAges(string $bitMap, array $ages): array
    {
        $ageAllows = array_keys(
            array_filter(
                str_split($bitMap), fn($item) => $item != 0
            )
        );
        $indexFromAge = reset($ageAllows);

        $indexToAge = end($ageAllows);

        return [$ages[$indexFromAge]["from"] ?? 0, $ages[$indexToAge]["to"] ?? 100];
    }
}
