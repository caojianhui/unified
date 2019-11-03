<?php

namespace Unified\Login\Facades;

use Illuminate\Support\Facades\Facade;

class Unified extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'unified';
    }

}
