<?php

namespace Selena\Tasks;

interface TaskContract
{
    /**
     * Get callable
     *
     * @return callable
     */
    public function get();
}
