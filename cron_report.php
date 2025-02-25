<?php
use WHMCS\Database\Capsule;

use WHMCS\Module\Addon\cron_report\Admin\AdminDispatcher;
// use WHMCS\Module\Addon\epp_demonstrator_addon\Client\ClientDispatcher;
use WHMCS\Module\Addon\cron_report\Helper;


if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}
function cron_report_config()
{
    return [

        'name' => 'Cron Report',

        'description' => '',

        'author' => 'whmcs global service',

        'language' => 'english',

        'version' => '1.0',
        'fields' => [
            'demo' => [
                'FriendlyName' => 'demo field',
                'Type' => 'text',
                'Size' => '25',
                'Default' => '',
            ],
        ]
    ];
}


function cron_report_activate()
{
    
    try {
        $helper = new Helper();
        $helper->get_admin();

        $helper->create_table();
        return [
            'status' => 'success',
            'description' => 'Activated Successfully.',
        ];
    } catch (\Exception $e) {
        return [
            'status' => "error",
            'description' => 'Unable to create : ' . $e->getMessage(),
        ];
    }
}


function cron_report_deactivate()
{
    return [
        'status' => 'success',
        'description' => 'Deactivated Successfully.',
    ];

}
function cron_report_output($vars)
{
    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] :'license';
    $dispatcher = new AdminDispatcher();
    $response = $dispatcher->dispatch($action, $vars);
    echo $response;
}
