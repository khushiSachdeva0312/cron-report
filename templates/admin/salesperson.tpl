{include file=$tplVar.header}
<div class="article_price">
    <form id="article_price_form" method="POST">
        <table id="pricingtbl" class="table table-condensed">
            <thead class="price_heading">
            </thead>
            <tbody class="price_body">
                <tr bgcolor="#ffffff" style="text-align:center">
                    <td bgcolor="#efefef">
                        <label for="selectclient">Choose Client</label>
                    </td>
                    <td>
                        <select class="form-select form-control" placeholder="select client"
                            aria-label="Default select example" name="selectclient[]" id="clients"
                            value="$tplVar['getuser']['firstname']['lastname']" multiple>
                            {foreach from=$tplVar['getuser'] item=arr}
                            <option value="{$arr->id}">{$arr->firstname} {$arr->lastname} {if !{$arr->companyname} == ''}({$arr->companyname}){/if}</option>
                            {/foreach}
                        </select>

                    </td>
                </tr>
                <tr bgcolor="#ffffff" style="text-align:center">
                    <td bgcolor="#efefef">
                        <label for="salesperson">Salesperson</label>
                    </td>
                    <td>
                        <select class="form-select form-control" aria-label="Default select example" name="salesperson"
                            value="$tplVar['salesperson']['firstname']['lastname']">
                            <option>Select Salesperson </option>
                            {foreach from=$tplVar['salesperson'] item=arr}
                                <option value="{$arr->id}|{$arr->username}">{$arr->username}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>

            </tbody>
        </table>
        <input type="submit" class="btn btn-primary" id="submitbtn" value="Save" name="save"></input>
    </form>
</div>
{include file=$tplVar.footer}
<script>
    $(document).ready(function() {
        $('#clients').selectize({
            plugins: ['remove_button']
        });
    });
</script>