<?php

namespace Selena\Params;

abstract class AbstractParams
{
    /**
     * Params
     *
     * @var array
     */
    protected $params = [];
    /**
     * Hitting params
     *
     * @var array
     */
    protected $attributes = [];
    /**
     * Init
     *
     * @param array $data
     */
    public function __construct($data)
    {
        $this->prepareParams($data);
    }
    /**
     * Magic get method
     *
     * @param string $name
     * @return void
     */
    public function __get($name)
    {
        return $this->attributes[$name] ?? null;
    }
    /**
     * Magic set method
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if (isset($this->params[$name])) $this->attributes[$name] = $value;
    }
    /**
     * Extend attributes
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function extend($name, $value)
    {
        if (!isset($this->attributes[$name])) $this->attributes[$name] = $value;
    }
    /**
     * Array serialization
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }
    /**
     * Prepare params
     *
     * @param array $data
     * @return void
     */
    protected function prepareParams($data)
    {
        foreach ($data as $key => $value)
            if (in_array($key, $this->params))
                $this->attributes[$key] = $value;
    }
}
