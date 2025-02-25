<link rel="stylesheet" type="text/css" href="{$tplVar['rootURL']}/modules/addons/cron_report/assets/css/style.css">

<div class="add_hdr">

    <a href="#" class="aspire_logo" ><img src="/modules/addons/cron_report/assets/img/wgs-logo.svg"
            width="250px" ;></a>
    <div class="add_nav">

        <ul>
            <li><a href="{$tplVar.moduleLink}&action=salesperson" class="{if $tplVar.actionMenu == 'salesperson'}active {/if} "><i class="fa fa-exchange" aria-hidden="true"></i> Assign Sales Person</a>
            </li>
        </ul>

        <ul>
            <li><a href="{$tplVar.moduleLink}&action=cron_report" class="{if $tplVar.actionMenu == 'cron_report'}active {/if}"><i class="fa fa-cog" aria-hidden="true"></i> Cron Setting</a></li>
        </ul>
        <ul>
            <li><a href="{$tplVar.moduleLink}&action=license" class="{if $tplVar.actionMenu == ''} active{/if}"><i class="fas fa-home"></i> Dashboard</a></li>
        </ul>
    </div>
</div>