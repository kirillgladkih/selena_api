<?php

namespace Selena;

class SelenaService extends Container
{

    /**
     * @var SelenaService
     */
    protected static self $instance;

    /**
     * Bootstrap
     *
     * @return void
     */
    protected function boot()
    {
    }

    /**
     * @return self
     */
    public static function instance(): self
    {
        if(!isset(self::$instance)){

            self::$instance = new self();

            self::$instance->boot();
        }

        return self::$instance;
    }

    private function __construct()
    {
    }
}