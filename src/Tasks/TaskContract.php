<?php

namespace Selena\Tasks;

interface TaskContract
{
    /**
     * Handle task
     *
     * @return mixed
     */
    public function get();
}
