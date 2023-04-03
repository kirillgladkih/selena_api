<?php

namespace Selena\Dto;

abstract class AbstractDto implements IDto
{   
    /**
     * Fillable fields
     *
     * @var array
     */
    protected array $fillable = [];
    /**
     * Dto attributes
     *
     * @var array
     */
    protected array $attributes = [];
    /**
     * Init 
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->fill($data);
    }
    /**
     * Magic set method
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, mixed $value)
    {
        if(in_array($name, $this->fillable)) $this->attributes[$name] = $value;
    }
    /**
     * Magic get method
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->attributes[$name] ?? null;
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
     * Fill this dto
     *
     * @param array $data
     * @return self
     */
    public function fill(array $data): self
    {
        foreach($data as $key => $value)
            if(isset($key, $this->fillable))
                $this->attributes[$key] = $value;

        return $this;
    } 
}
