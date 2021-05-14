<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

/**
 * Csv Service Facade
 * @package App\Facades
 */
class Csv extends Facade {

    /**
     * Get the registered name
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'csvService';
    }

}
