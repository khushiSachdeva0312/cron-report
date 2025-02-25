{include file=$tplVar.header}
<!-- <pre>
{* {$tplVar['cron_report']|@print_r} *}
</pre> -->
<div class="main-section">
<div class="report-form">
    <div class="container">
        <form id="myForm" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group report-fields">
                        <label for="preSuspensionReport">Pre Suspension Report for Salesperson</label>
                        <input type="text" class="form-control" id="preSuspensionReport" name="preSuspensionReport"
                            value="{$tplVar['cron_report']['preSuspensionReport']}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group report-fields">
                        <label for="PreIntimationReport">Pre Intimation Report Days</label>
                        <input type="text" class="form-control" id="preSuspensionDays" name="PreIntimationReport"
                            value="{$tplVar['cron_report']['PreIntimationReport']}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group report-fields">
                        <label for="unpaidreport">Customer Unpaid Invoice Report Days</label>
                        <select class="form-select form-control" aria-label="Default select example" name="unpaidreport"
                            value="{$tplVar['cron_report']['unpaidreport']}">
                            <option>Select Day</option>
                            <option value="sunday" {if $tplVar['cron_report']['unpaidreport']=="sunday"}selected{/if}>
                                Sunday
                            </option>
                            <option value="monday" {if $tplVar['cron_report']['unpaidreport']=="monday"}selected{/if}>
                                Monday
                            </option>
                            <option value="tuesday" {if $tplVar['cron_report']['unpaidreport']=="tuesday"}selected{/if}>
                                Tuesday</option>
                            <option value="wednesday"
                                {if $tplVar['cron_report']['unpaidreport']=="wednesday"}selected{/if}>
                                Wednesday</option>
                            <option value="thursday"
                                {if $tplVar['cron_report']['unpaidreport']=="thursday"}selected{/if}>
                                Thursday</option>
                            <option value="friday" {if $tplVar['cron_report']['unpaidreport']=="friday"}selected{/if}>
                                Friday
                            </option>
                            <option value="saturday"
                                {if $tplVar['cron_report']['unpaidreport']=="saturday"}selected{/if}>
                                Saturday</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group report-fields">
                        <label for="unpaidinvoice">Customer Unpaid Invoice Weekly Report</label>
                        <select class="form-select form-control" aria-label="Default select example"
                            name="unpaidinvoice" value="{$tplVar['cron_report']['unpaidinvoice']}">
                            <option>Select Report Cycle</option>
                            <option value="once" {if $tplVar['cron_report']['unpaidinvoice']=="once"}selected{/if}>Once
                                a
                                Week</option>
                            <option value="twice" {if $tplVar['cron_report']['unpaidinvoice']=="twice"}selected{/if}>
                                Twice a
                                Week</option>
                            <option value="monthly"
                                {if $tplVar['cron_report']['unpaidinvoice']=="monthly"}selected{/if}>
                                Monthly a Week</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group report-fields">
                        <label for="preSuspensionDays">Pre Suspension Days</label>&nbsp;&nbsp;
                        <input type="checkbox" class="form-check-input" name="preSuspensionDays" value="on" {if
                        $tplVar['cron_report']['preSuspensionDays']=="on" }checked{/if}>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check report-fields">
                        <label for="customerUnpaidInvoice">Customer Unpaid Invoice Report</label>
                        <input type="checkbox" class="form-check-input" id="customerUnpaidInvoice"
                            name="customerUnpaidInvoice" value="on" {if
                            $tplVar['cron_report']['customerUnpaidInvoice']=="on" }checked{/if}>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check report-fields">
                        <label for="preIntimationEmail">Pre Intimation Email Report</label> &nbsp;&nbsp;
                        <input type="checkbox" class="form-check-input" name="preIntimationEmail" value="on" {if
            $tplVar['cron_report']['preIntimationEmail']=="on" }checked{/if}>
                    </div>
                </div>
            </div>
            <input type="submit" class="btn btn-success report-btn" name="submit" value="submit">
    </div>
    </form>
</div>
</div>
{include file=$tplVar.footer}
{* <!-- </div> --> *}