<?php

namespace Selena\Reports;

interface ReportContract
{
    /**
     * @return string
     */
    public function __toString(): string;
}