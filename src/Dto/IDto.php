<?php

namespace Selena\Dto;

interface IDto
{
    /**
     * Fill dto
     *
     * @param array $data
     * @return self
     */
    public function fill(array $data);
    /**
     * Serialize from array
     *
     * @return array
     */
    public function toArray();
}
