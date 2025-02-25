<?php

namespace WHMCS\Module\Addon\cron_report\Admin;

use WHMCS\Module\Addon\cron_report\Helper;
use Smarty;
use WHMCS\Database\Capsule;

class Controller
{
    public $params = [];
    public $lang;
    public $tplFileName;
    public $smarty;
    public $tplVar = array();
    public function __construct($params)
    {
        global $CONFIG;
        $this->params = $params;
        $this->tplVar['rootURL'] = $CONFIG["SystemURL"];
        $this->tplVar['urlPath'] = $CONFIG["SystemURL"] . "/modules/addons/{$params['module']}/";
        $this->tplVar['_lang'] = $params["_lang"];
        $this->tplVar['moduleLink'] = $params['modulelink'];
        $this->tplVar['module'] = $params['module'];
        $this->tplVar['tplDIR'] = ROOTDIR . "/modules/addons/{$params['module']}/templates/admin/";
        $this->tplVar['header'] = ROOTDIR . "/modules/addons/{$params['module']}/templates/admin/header.tpl";
        $this->tplVar['footer'] = ROOTDIR . "/modules/addons/{$params['module']}/templates/admin/footer.tpl";
        $this->tplVar['cssPath'] = $CONFIG["SystemURL"] . "/modules/addons/{$params['module']}/assets/css";
    }
    public function cron_report($vars)
    {
        global $whmcs;
        $this->tplVar['modulelink'] = $vars['modulelink'];
        if ($whmcs->get_req_var('submit') && (!empty($whmcs->get_req_var('submit')))) {
            $data = array(
                'preIntimationEmail' => ($whmcs->get_req_var('preIntimationEmail')),
                'PreIntimationReport' => $whmcs->get_req_var('PreIntimationReport'),
                'preSuspensionDays' => $whmcs->get_req_var('preSuspensionDays'),
                'customerUnpaidInvoice' => $whmcs->get_req_var('customerUnpaidInvoice'),
                'preSuspensionReport' => $whmcs->get_req_var('preSuspensionReport'),
                'unpaidinvoice' => $whmcs->get_req_var('unpaidinvoice'),
                'unpaidreport' => $whmcs->get_req_var('unpaidreport')
            );
            $formData = json_encode($data);
            $count = Capsule::table('cron_report_addon')->count();
            if ($count == 0) {
                $insert = Capsule::table('cron_report_addon')->insert(['cron_report' => $formData]);
            } else {
                $latestData = Capsule::table('cron_report_addon')->orderBy('id', 'desc')->first();
                $latestDataId = $latestData->id;

                $update = Capsule::table('cron_report_addon')->where('id', $latestDataId)->update(['cron_report' => $formData]);
            }
        }
        $latestData = Capsule::table('cron_report_addon')->orderBy('id', 'desc')->first();
        if ($latestData) {
            // Decode the JSON data
            $formData = json_decode($latestData->cron_report, true);
            $this->tplVar['cron_report'] = $formData;
        }
        $this->tplFileName = $this->tplVar['tab'] = 'reports_form';
        $this->output();
    }
    public function salesperson($vars)
    {
        global $whmcs;

        if ($whmcs->get_req_var('save') && (!empty($whmcs->get_req_var('save')))) {
            foreach ($whmcs->get_req_var("selectclient") as $clientsId) {
                $fieldid = Capsule::table('tblcustomfields')->where('fieldname', 'LIKE', '%' . "salepersonfield" . '%')->where('type', '=', 'client')->value('id');
                $checkexist = Capsule::table('tblcustomfieldsvalues')->where('fieldid', $fieldid)->where('relid', $clientsId)->count();
                if ($checkexist == 0) {
                    $results = Capsule::table('tblcustomfieldsvalues')->insert([
                        'fieldid' => $fieldid,
                        'relid' => $clientsId,
                        'value' => $whmcs->get_req_var('salesperson')
                    ]);
                    echo $results;
                } else {
                    $checkexist = Capsule::table('tblcustomfieldsvalues')->where('fieldid', $fieldid)->where('relid', $clientsId)->update([
                        'value' => $whmcs->get_req_var('salesperson')
                    ]);
                }
            }
            // die;

        }
        // $count = Capsule::table('tblcustomfields.fieldoptions')->count();    


        // Capsule::table('tblcustomfields')->where('tblcustomfields.id','=','tblcustomfieldsvalues.fieldid')->insert(['tblcustomfieldsvalues.value'=>$salesperson_data]);
        // $count = Capsule::table('cron_report_addon')->count();
        $helper = new Helper();
        $users = $helper->getuser();
        // echo '<pre>';
        // print_r($users);
        // exit();
        $this->tplVar['getuser'] = $users;
        $salesperson = $helper->getsalesperson();
        $this->tplVar['salesperson'] = $salesperson;

        $this->tplFileName = $this->tplVar['tab'] = 'salesperson';
        $this->output();
    }


    public function license($licensekey, $localkey = "") {
        // $whmcsurl = "http://whmcsglobalservices.com/members/"; #enter your own whmcs url here
        // $licensing_secret_key = 'WGSWhatsapp22112021'; #you can enter your own secret key here
        // $check_token = time() . md5(mt_rand(1000000000, 1e+010) . $licensekey);
        // $checkdate = date("Ymd");
        // $usersip = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR'];
        // $localkeydays = 15;
        // $allowcheckfaildays = 5;
        // $localkeyvalid = false;
        // // for local key start
        // $lkey = Capsule::table('tblconfiguration')->where('setting', 'whatsapp_notification')->get();


        // //add for local key
        // if ($lkey) {
        //     $localkey = $lkey[0]->value;
        // }
        // // for local key end
        // if ($localkey) {
        //     $localkey = str_replace("\n", "", $localkey);
        //     $localdata = substr($localkey, 0, strlen($localkey) - 32);
        //     $md5hash = substr($localkey, strlen($localkey) - 32);
        //     if ($md5hash == md5($localdata . $licensing_secret_key)) {
        //         $localdata = strrev($localdata);
        //         $md5hash = substr($localdata, 0, 32);
        //         $localdata = substr($localdata, 32);
        //         $localdata = base64_decode($localdata);
        //         $localkeyresults = unserialize($localdata);
        //         $originalcheckdate = $localkeyresults['checkdate'];
        //         if ($md5hash == md5($originalcheckdate . $licensing_secret_key)) {
        //             $localexpiry = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - $localkeydays, date("Y")));
        //             if ($localexpiry < $originalcheckdate) {
        //                 $localkeyvalid = true;
        //                 $results = $localkeyresults;
        //                 $validdomains = explode(",", $results['validdomain']);
        //                 if (!in_array($_SERVER['SERVER_NAME'], $validdomains)) {
        //                     $localkeyvalid = false;
        //                     $localkeyresults['status'] = "Invalid";
        //                     $results = array();
        //                 }

        //                 $validips = explode(",", $results['validip']);
        //                 if (!in_array($usersip, $validips)) {
        //                     $localkeyvalid = false;
        //                     $localkeyresults['status'] = "Invalid";
        //                     $results = array();
        //                 }

        //                 if ($results['validdirectory'] != dirname(__FILE__)) {
        //                     $localkeyvalid = false;
        //                     $localkeyresults['status'] = "Invalid";
        //                     $results = array();
        //                 }
        //             }
        //         }
        //     }
        // }

        // if (!$localkeyvalid) {
        //     $postfields['licensekey'] = $licensekey;
        //     $postfields['domain'] = $_SERVER['SERVER_NAME'];
        //     $postfields['ip'] = $usersip;
        //     $postfields['dir'] = dirname(__FILE__);
        //     if ($check_token) {
        //         $postfields['check_token'] = $check_token;
        //     }

        //     if (function_exists("curl_exec")) {
        //         $ch = curl_init();
        //         curl_setopt($ch, CURLOPT_URL, $whmcsurl . "modules/servers/licensing/verify.php");
        //         curl_setopt($ch, CURLOPT_POST, 1);
        //         curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        //         curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //         $data = curl_exec($ch);
        //         $info = curl_getinfo($ch);
        //         curl_close($ch);
               
                
        //     } else {
               
        //         $fp = fsockopen($whmcsurl, 80, $errno, $errstr, 5);
        //         if ($fp) {
        //             $querystring = "";
        //             foreach ($postfields as $k => $v) {
        //                 $querystring .= "{$k}=" . urlencode($v) . "&";
        //             }
        //             $header = "POST " . $whmcsurl . "modules/servers/licensing/verify.php HTTP/1.0\r\n";
        //             $header .= "Host: " . $whmcsurl . "\r\n";
        //             $header .= "Content-type: application/x-www-form-urlencoded\r\n";
        //             $header .= "Content-length: " . @strlen(@$querystring) . "\r\n";
        //             $header .= "Connection: close\r\n\r\n";
        //             $header .= $querystring;
        //             $data = "";
        //             @stream_set_timeout(@$fp, 20);
        //             @fputs(@$fp, @$header);
        //             $status = @socket_get_status(@$fp);

        //             while (!feof(@$fp) && $status) {
        //                 $data .= @fgets(@$fp, 1024);
        //                 $status = @socket_get_status(@$fp);
        //             }
        //             @fclose(@$fp);
        //         }
        //     }

        //     if (!$data) {
        //         $localexpiry = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - ( $localkeydays + $allowcheckfaildays ), date("Y")));
        //         if ($localexpiry < $originalcheckdate) {
        //             $results = $localkeyresults;
        //         } else {
        //             $results['status'] = "Invalid";
        //             $results['description'] = "Remote Check Failed";
        //             return $results;
        //         }
        //     }

        //     preg_match_all("/<(.*?)>([^<]+)<\\/\\1>/i", $data, $matches);
        //     $results = array();
        //     foreach ($matches[1] as $k => $v) {
        //         $results[$v] = $matches[2][$k];
        //     }

        //     if ($results['md5hash'] && $results['md5hash'] != md5($licensing_secret_key . $check_token)) {
        //         $results['status'] = "Invalid";
        //         $results['description'] = "MD5 Checksum Verification Failed";
        //         return $results;
        //     }

        //     if ($results['status'] == "Active") {
        //         $results['checkdate'] = $checkdate;
        //         $data_encoded = serialize($results);
        //         $data_encoded = base64_encode($data_encoded);
        //         $data_encoded = md5($checkdate . $licensing_secret_key) . $data_encoded;
        //         $data_encoded = strrev($data_encoded);
        //         $data_encoded = $data_encoded . md5($data_encoded . $licensing_secret_key);
        //         $data_encoded = wordwrap($data_encoded, 80, "\n", true);
        //         $results['localkey'] = $data_encoded;          
        //         // for local key start
        //         if (!Capsule::table('tblconfiguration')->where('setting', 'whatsapp_notification')->get()) {
        //             Capsule::table('tblconfiguration')->insert(
        //               [
        //                   'setting' => 'whatsapp_notification',
        //                   'value' => $results['localkey']
        //               ]
        //             );
        //         } else {
        //             Capsule::table('tblconfiguration')
        //             ->where('setting', 'whatsapp_notification')
        //             ->update(
        //               [
        //                   'value' => $results['localkey']
        //               ]
        //             );          
                
        //         }
        //         // for local key end            
        //     }
        //     $results['remotecheck'] = true;
        // }

        // unset($postfields);
        // unset($data);
        // unset($matches);
        // unset($whmcsurl);
        // unset($licensing_secret_key);
        // unset($checkdate);
        // unset($usersip);
        // unset($localkeydays);
        // unset($allowcheckfaildays);
        // unset($md5hash);
        // $results['status'] = "Active";
       $this->tplFileName = $this->tplVar['tab'] = __FUNCTION__;
        $this->output();
        // return $results;
    }

    public function output()
    {
        $this->smarty = new Smarty();
        if(!isset($_GET['action'])){
            $this->tplVar['actionMenu'] = ''; 
        }else{
            $this->tplVar['actionMenu'] = $_GET['action']; 
        } 
        $this->smarty->assign('tplVar', $this->tplVar);
        if (!empty($this->tplFileName)) {
            $this->smarty->display($this->tplVar['tplDIR'] . $this->tplFileName . '.tpl');
        } else {
            $this->tplVar['errorMsg'] = 'not found';
        }
    }

}








    
