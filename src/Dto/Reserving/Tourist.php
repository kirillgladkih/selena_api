<?php

namespace Selena\Dto\Reserving;

use Selena\Dto\AbstractDto;

/**
 * @property int $apartmentid required
 * @property int $place_type required
 * @property int $main_place_type
 * @property boolean $place_is_locked: boolean
 * @property string $firstname required
 * @property string $middlename required
 * @property string $lastname required
 * @property DateTime $birthdate required
 * @property int $identity
 * @property string $identity_sn
 * @property DateTime $identity_issued
 * @property string $identity_issuer
 * @property DateTime $identity_expired: date,
 * @property string $address
 * @property string $phone
 * @property int $sex
 * @property int $roomid
 * @property int $services: [int,int, ...],
 * @property array $socials format id => <int>, doc => <string>
 * @property int $tourpackid
 * @property string $city
 * @property int $cityid
 * @property string $citizenship
 * @property string $forward_tripid
 * @property string $forward_trip_seat
 * @property int $forward_load_point
 * @property int $forward_unload_point
 * @property string $backward_tripid
 * @property string $backward_trip_seat
 * @property int $backward_load_point
 * @property int $backward_unload_point
 */

class Tourist extends AbstractDto
{
    protected array $fillable = [
        "apartmentid",
        "place_type",
        "main_place_type",
        "place_is_locked",
        "firstname",
        "middlename",
        "lastname",
        "birthdate",
        "identity",
        "identity_sn",
        "identity_issued",
        "identity_issuer",
        "identity_expired",
        "address",
        "phone",
        "sex",
        "roomid",
        "services",
        "socials",
        "tourpackid",
        "city",
        "cityid",
        "citizenship",
        "forward_tripid",
        "forward_trip_seat",
        "forward_load_point",
        "forward_unload_point",
        "backward_tripid",
        "backward_trip_seat",
        "backward_load_point",
        "backward_unload_point"
    ];
}
