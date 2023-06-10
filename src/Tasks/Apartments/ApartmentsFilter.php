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
    protected array $children;

    /**
     * @var int
     */
    protected int $main_places;

    /**
     * @param array $apartments
     * @param int $main_places
     * @param array $children
     */
    public function __construct(array $apartments, int $main_places = 0, array $children = [])
    {
        $this->apartments = $apartments;

        $this->main_places = $main_places;

        $this->children = $children;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        $childrenCount = count(($this->children ?? []));

        foreach ($this->apartments as $apartment) {

            if (($this->main_places <= (int)$apartment["places"]) && ($childrenCount <= (int)$apartment["childplaces"])) {

                $childAgesCount = count($apartment["age_allows"]["child_ages"]);

                if ($childrenCount >= 1) {

                    if ($childAgesCount >= 1) {

                        $from = (int)($apartment["age_allows"]["child_ages"][0]["from"] ?? 0);

                        $to = (int)($apartment["age_allows"]["child_ages"][$childAgesCount - 1]["to"] ?? 16);

                        $childrenAgeCondition = true;

                        foreach ($this->children as $age) {

                            $childrenAgeCondition = $age <= $to && $age >= $from;

                            if ($childrenAgeCondition) {

                                $result[] = $apartment;

                                break;
                            }
                        }
                    }

                } else {

                    $result[] = $apartment;
                }
            }
        }

        return $result ?? [];
    }
}
