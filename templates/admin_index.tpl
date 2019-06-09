<div class="row pull-right" style="margin-top: 10px;margin-right: 4px;">
    <div class="col-md-3">
        <div class="text-right">
            <!-- Split button -->
            <div class="btn-group">
                <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                        data-target="#dialog_addRecord"
                        onclick="">
                    Добавить домен
                </button>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div id="tableBackground" class="tablebg">
        <table id="tableDomainsList" width="100%" class="datatable no-margin">
            <thead>
            <tr>
                <th>
                    Домен
                </th>
                <th>
                    dnssec
                </th>
                <th>
                    Тип
                </th>
                <th>
                    serial
                </th>
                <th>
                    Master
                </th>
                <th style="width: 2%;"></th>
                <th style="width: 2%;"></th>
            </tr>
            </thead>
            <tbody>
            {foreach $domainList as $domain}
                <tr class="product">
                    <td>
                        {$domain->name|substr:0:-1}
                    </td>
                    <td>
                        {if $domain->dnssec}
                            Включено
                        {else}
                            Отключено
                        {/if}
                    </td>
                    <td>
                        {$domain->kind}
                    </td>
                    <td>
                        {$domain->serial}
                    </td>
                    <td>
                        {if empty($domain->masters)}
                            N/A
                        {else}
                            {', '|implode:$domain->masters}
                        {/if}
                    </td>
                    <td>
                        <a href="{$modulelink}&action=record_list&domain={$domain->name|substr:0:-1}">
                            <img src="images/edit.gif" border="0">
                        </a>
                    </td>
                    <td>
                        <a href="#">
                            <img src="images/delete.gif" width="16" height="16" border="0">
                        </a>
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
</div>


