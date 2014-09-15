<?php

namespace Martinmarianetti\XmlRpc\Facades;

/**
 * Description of Xmlrpc
 *
 * @author mmarianetti
 */
use Illuminate\Support\Facades\Facade;

class Xmlrpc extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'xmlrpc';
    }

}
