<?php

namespace Selena;

class Container
{

    /**
     * @var array
     */
    protected array $container = [];

    /**
     * @param string $key
     * @param $value
     * @param ...$args
     * @return void
     */
    public function set(string $key, $value, ...$args): void
    {
        $result = $value;

        if(is_callable($value)){

            $result = $value($args);

        }

        $this->container[$key] = $result;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->container[$key];
    }

    /**
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool
    {
        return isset($this->container[$key]);
    }
}