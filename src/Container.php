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
     * @param array $args
     * @return void
     */
    public function set(string $key, $value, array $args = []): void
    {
        $key = $this->prepareKey($key);

        $result = $value;

        if(is_callable($value)){

            $result = $value(...$args);

        }

        $this->container[$key] = $result;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        $key = $this->prepareKey($key);

        return $this->container[$key];
    }

    /**
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool
    {
        $key = $this->prepareKey($key);

        return isset($this->container[$key]);
    }

    /**
     * @param string $key
     * @return string
     */
    private function prepareKey(string $key): string
    {
        return $key;
    }
}