<?php

namespace Selena\Tasks;

class Promise
{

    /**
     * @var mixed
     */
    protected $result;

    /**
     * @param \Closure $process
     */
    public function __construct(\Closure $process)
    {
        try {

            $this->result = $process();

        }catch (\Throwable $throwable){

            $this->result = $throwable;

        }
    }

    /**
     * @param \Closure|null $closure
     * @return mixed
     */
    public function then(?\Closure $closure)
    {
        return $closure($this->result);
    }

    /**
     * @param \Closure|null $closure
     * @return mixed
     */
    public function catch(?\Closure $closure)
    {
        return $closure($this->result);
    }
}