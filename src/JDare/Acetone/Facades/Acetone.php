<?php
namespace JDare\Acetone\Facades;

use Illuminate\Support\Facades\Facade;

class Acetone extends Facade {
    /**
    * Get the registered name of the component.
    *
    * @return string
    */
    protected static function getFacadeAccessor() { return 'acetone'; }

}
