{include file=$tplVar.header}
<div id="wrapper">
    <div class="addon_container">
        <div class="ad_content_area">
            <div class="addon_inner">
                <div class="ad_content_sec">
                    <div class="add_version_sec">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="ad_on_table">
                            <tr>
                                <td>Version</td>
                                <td align="right">V{$tplVar.version}</td>
                            </tr>
                            <tr bgcolor="f3f8fd">
                                <td>License Registered To</td>
                                <td align="right"> {$tplVar.registeredname}</td>
                            </tr>
                            <tr>
                                <td>License Registered Email</td>
                                <td align="right">{$tplVar.email}</td>
                            </tr>
                            <tr bgcolor="f3f8fd">
                                <td>License Valid Domain</td>
                                <td align="right">{$tplVar.explodedomain}</td>
                            </tr>
                            <tr>
                                <td>License</td>
                                <td align="right">{$tplVar.license_key}</td>
                            </tr>
                            <tr bgcolor="f3f8fd">
                                <td>License Status</td>
                                <td align="right"><span class="license {$tplVar.status|lower}">{$tplVar.status}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Author</td>
                                <td align="right">Whmcs Global Services</td>
                            </tr>
                            <tr bgcolor="f3f8fd">
                                <td>Product Name</td>
                                <td align="right">{$tplVar.productname}</td>
                            </tr>
                            <tr>
                                <td>Last Updated</td>
                                <td align="right"></td>
                            </tr>
                        </table>
                    </div>
                </div>
 
            </div>
        </div>
    </div>
</div>
{include file=$tplVar.footer}
