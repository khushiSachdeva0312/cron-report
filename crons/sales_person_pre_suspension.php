<?php

use WHMCS\Database\Capsule;

$whmcspath = "";
if (file_exists(dirname(__FILE__) . "/config.php"))
    require_once dirname(__FILE__) . "/config.php";
if (!empty($whmcspath))
    require $whmcspath . "/init.php";
else
    require_once dirname(dirname(dirname(dirname(__DIR__)))) . "/init.php";

    $report_form_data = Capsule::table('cron_report_addon')->get()->toArray();
    $new_form_data = json_decode($report_form_data[0]->cron_report);

try {
    if($new_form_data->preSuspensionDays == 'on'){
        $month = date('m');

        logActivity('Sale Pre Suspension report - Start');
        global $CONFIG;
        create_EmailTemplate();
        $results = Capsule::table("tblhosting")
            ->join("tblclients", "tblclients.id", "tblhosting.userid")
            ->join("tblproducts", "tblproducts.id", "tblhosting.packageid")
            ->whereMonth('tblhosting.nextduedate', '>=', $month)
            ->select("tblclients.firstname", "tblclients.lastname", "tblclients.companyname", "tblproducts.name as productname", "tblhosting.*")->get();
        foreach ($results as $result) {
            $salespersonId = getSalesPersonName($result->userid);
            if (!empty($salespersonId)) {
                $salesperson[$salespersonId][] = $result;
            }
        }
    
    
        sendEmailToCustomers($salesperson);   #Send Email to Customer
    }
  

} catch (Execption $e) {
    logActivity("Suspension Cron Error: {$e->getMessage()}");
}


function sendEmailToCustomers($tabledata)
{
    $report_form_data = Capsule::table('cron_report_addon')->get()->toArray();
    $new_form_data = json_decode($report_form_data[0]->cron_report);
    foreach ($tabledata as $salesperson => $data) {
        $table =  "<table style='border-collapse: collapse; width: 100%;' border='1'>
    <thead>
            <tr>
                    <th width = '30%'>Client Name</th>
                    <th width = '20%'>Company Name</th>
                    <th width = '20%'>Products Name</th>
                    <th width = '10%'>Invoice Date</th>
                    <th width = '10%'>Invoice Due Date</th>
                    <th width = '10%'>Total Amount</th>
                    
            </tr>
    </thead>
<tbody style='text-align: center;'>";
        global $CONFIG;
        global $whmcs;
        global $customadminpath;
        $systemURL = $CONFIG['SystemURL'];
        $path = $systemURL . '/' . $customadminpath;
        $reportdate = date('d-m-Y');
        $count = 0;
        foreach ($data as $result) {
            $invoicedetails = Capsule::table("tblinvoiceitems")
                ->join('tblinvoices', 'tblinvoices.id', '=', 'tblinvoiceitems.invoiceid')
                ->where('tblinvoiceitems.userid', $result->userid)
                ->where('tblinvoiceitems.relid', $result->id)
                ->where('tblinvoiceitems.type', '=', 'Hosting')
                ->where("tblinvoices.status", "Paid")
                ->orderBy('tblinvoiceitems.invoiceid', 'desc')
                ->select("tblinvoices.date as invoicedate", "tblinvoices.duedate as invoiceduedate", "tblinvoices.total as totalamount", "tblinvoices.credit", "tblinvoices.status as invoicestatus")
                ->first();
            $currentDate = date("Y-m-d");
            $nextduedateafterdays = date("Y-m-d", strtotime($result->nextduedate . " + $CONFIG[AutoSuspensionDays] days"));
            $new_date =  date("Y-m-d", strtotime($nextduedateafterdays . $new_form_data->preSuspensionReport ."day"));
            $currentDates = date("Y-m-d", strtotime($currentDate));
            if ($currentDates == $new_date) {
                $totalamount = ($invoicedetails->credit == "0.00" ? $invoicedetails->totalamount : $invoicedetails->credit);
                $count  = $count + 1;
                $currencyData = getCurrency($result->userid);
                $company = empty($result->companyname) ? "-" : $result->companyname;
                $table .= '<tr>
                <td width = "30%" ><a href="' . $path . '/clientssummary.php?userid=' . $result->userid . '" target="_blank">' . $result->firstname . ' ' . $result->lastname  . '</a></td>
                <td width = "20%">' . $company . '</td>
                <td width = "20%">' . $result->productname . '</td>
                <td width = "10%">' . date('d-m-Y', strtotime($invoicedetails->invoicedate)) . '</td>
                <td width = "10%">' . date('d-m-Y', strtotime($invoicedetails->invoiceduedate)) . '</td>
                <td width = "10%">' . formatCurrency($totalamount, $currencyData['id']) . '</td>';
            }
            '</tr>';
        }
        $table .= "</tbody>
</table>";
        $expoldesalesperson = (!empty($salesperson) ? explode("|", $salesperson) : "-");
        if ($count >= 1) {
            sendEmail($table, $reportdate, $expoldesalesperson[1], $expoldesalesperson[0]); #send_email_admin_withinSalesman
        }
    }
}

function sendEmail($table, $reportdate, $salespersonname, $salespersonId)
{

    $mergefields = [
        "messagename" => 'Suspension report',
        "table" => $table,
        "reportdate" => $reportdate,
        "admin_name" => $salespersonname
    ];
    $res = sendAdminMessage("Suspension report", $mergefields, "", 0, [$salespersonId]);
    if ($res) {
        logActivity('Sale Pre Suspension report - Email Send to Sales person. Sale Persion ID: ' . $salespersonId);
    }
    echo ('Sale Pre Suspension report - Email Send to Sales person ' . $salespersonId) . "<br>";
}

function create_EmailTemplate()
{
    $data = [
        "name" => "Suspension report Sales Person",
        "type" => "admin",
        "subject" => 'Suspension report of  Sales Person{$reportdate}',
        "message" => '<p>Hi {$admin_name},</p>
        <p>This is your Suspension report</p>
        <p>Report date - <strong>{$reportdate}</strong></p>
        <p></p>
        <p>{$table}</p>
        <p>{$whmcs_admin_link}</p>',
        "custom" => "1"
    ];
    if (!Capsule::table('tblemailtemplates')->WHERE('name', $data["name"])->WHERE('type', $data["type"])->count()) {
        return Capsule::table('tblemailtemplates')->insertGetId($data);
    }
}
function getSalesPersonName($userinvoiceid)
{
    $salespersons = Capsule::table('tblcustomfields')
        ->join('tblcustomfieldsvalues', 'tblcustomfields.id', '=', 'tblcustomfieldsvalues.fieldid')
        ->where('tblcustomfields.type', 'client')
        ->where('tblcustomfields.fieldtype', 'dropdown')
        ->where('tblcustomfields.fieldname', 'like', '%sale_person%')
        ->where('tblcustomfieldsvalues.relid', $userinvoiceid)
        ->value('tblcustomfieldsvalues.value');
    return $salespersons;
}
die;
