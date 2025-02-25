<?php
namespace WHMCS\Module\Addon\cron_report;

use WHMCS\Database\Capsule;

class Helper
{
    public function get_admin()
    {
        try {
            $admins = Capsule::table('tbladminroles')
                ->join('tbladmins', 'tbladmins.roleid', '=', 'tbladminroles.id')
                ->where('tbladminroles.name', '!=', 'Full Administrator')
                ->get();
                // return $admins;
                $fieldOptions = [];

                foreach ($admins as $id => $salesperson) {
                    
                    $fieldOptions["option" . ($id + 1)] = $salesperson->id . '|' . $salesperson->username;
                }
                $customfieldArry = array(
                    "0" => [
                    'type' => 'client',
                    'fieldtype' => 'dropdown',
                    'relid' => '0',
                    'fieldname' => 'salepersonfield|Sales Persons',
                    'adminonly' => 'on',
                    'showorder' => '',
                    'showinvoice' => '',
                    'fieldoptions' => $fieldOptions
                    
                ],
                "1" => [
                    'type' => 'client',
                    'fieldtype' => 'tickbox',
                    'relid' => '0',
                    'fieldname' => 'unpaid_report|Disable Unpaid Report',
                    'adminonly' => 'on',
                    'showorder' => '',
                    'showinvoice' => '',
                    // 'fieldoptions' => $fieldOptions
                    
                ],
            );
            // echo"<pre>";
            // print_r($customfieldArry);
            // die;
            foreach ($customfieldArry as $customfields) {
                $customfildname = explode('|', $customfields["fieldname"])[0];
                $unpaidreport = explode('|', $customfields["fieldname"])[1];
                if (isset($customfields['fieldoptions']) && is_array($customfields['fieldoptions'])) {

                    $implodedString = implode(', ', $customfields['fieldoptions']);
                } else {
                    $implodedString = '';

                }
                $checkcustomfieldvalueId = Capsule::table('tblcustomfields')
                    ->where('type', 'client')
                    ->where('fieldname', 'LIKE', '%' . $customfildname . '%')
                    ->where('fieldname', 'LIKE', '%' . $unpaidreport . '%')
                    ->count();
                if ($checkcustomfieldvalueId == 0) {
                    $customfields['fieldoptions'] = $implodedString;
                    $custom_field_id = Capsule::table('tblcustomfields')->insert($customfields);
                }

            }
        } catch (Exception $ex) {
            echo 'Message: ' . $ex->getMessage();
        }

    }
    public function create_table()
    {
        try {
            if (!Capsule::schema()->hasTable('cron_report_addon')) {
                Capsule::schema()
                    ->create(
                        'cron_report_addon',
                        function ($table) {
                            $table->increments('id');
                            $table->longtext('cron_report');
                        }
                    );
            }
        } catch (Exception $e) {
            echo 'error: ' . $e->getMessage();
        }
    }

    public function getuser()
    {

        $getuser =  Capsule::table('tblclients')->get();

        return  $getuser;
        
    }
    
    public function getsalesperson()
    {
        $salesperson = Capsule::table('tbladminroles')
        ->join('tbladmins', 'tbladmins.roleid', '=', 'tbladminroles.id')
        ->where('tbladminroles.name', '!=', 'Full Administrator')
        ->get();
        return $salesperson;
    }
}

?>