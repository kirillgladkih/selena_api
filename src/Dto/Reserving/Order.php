<?php

namespace Selena\Dto\Reserving;

use Selena\Dto\AbstractDto;

/**
 * @property int $baseid required
 * @property DateTime $begindate required
 * @property DateTime $enddate required
 * @property int $duration required
 * @property int $tourid required
 * @property int $agentid
 * @property int $customerid
 * @property string $note
 * @property string $promo
 * @property bool $silence
 */

class Order extends AbstractDto
{
    protected array $fillable = [
        "baseid" ,
        "begindate",
        "enddate",
        "duration",
        "tourid",
        "agentid",
        "customerid",
        "note",
        "promo",
        "silence",
    ];
}
