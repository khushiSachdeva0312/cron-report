<?php

namespace WHMCS\Module\Addon\cron_report\Admin;

/**
 * Sample Admin Area Dispatch Handler
 */
class AdminDispatcher {

    public function dispatch($action, $parameters)
    {   
        $controller = new Controller($parameters);
        if (is_callable(array($controller, $action))) {
            return $controller->$action($parameters);
        }
        return '<p>Invalid action requested. Please go back and try again.</p>';
    }
}

