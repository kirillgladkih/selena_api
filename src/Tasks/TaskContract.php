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
    /**
     * Get tag name for cache
     *
     * @return string
     */
    public function tag();
}
