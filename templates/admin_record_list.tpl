<div class="col-md-12">
    <div class="text-right">
        <!-- Split button -->
        <div class="btn-group" style="margin-right: 4px;">
				<span data-toggle="modal" data-target="#dialog_addRecord">
                   <form role="form" method="get" style="display: inline;">
                    <input type="hidden" name="module" value="DomainManager">
                    <input type="hidden" name="domain" value="{$smarty.get.domain}">
                    <input type="hidden" name="server_id" value="{$smarty.get.server_id}">
                    <input type="hidden" name="action" value="domain_add_record">
                    <input type="submit" class="btn btn-success btn-sm" value="Добавить запись">
                </form>
				</span>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div id="tableBackground" class="tablebg">
        <table id="tableDomainRecordsList" width="100%" class="datatable no-margin">
            <thead>
            <tr>
                <th>Имя</th>
                <th>Тип</th>
                <th>Запись</th>
                <th>TTL</th>
                <th style="width: 2%;"></th>
                <th style="width: 2%;"></th>
            </tr>
            </thead>
            <tbody>
            {foreach  from=$recordList item=record name=recordLoop}
                <tr>
                    <td>{$record['name']}</td>
                    <td>{$record['type']}</td>
                    <td style="word-break: break-all;">
                        {foreach from=$record['records'] item=record name=contentLoop}
                            {$record->content}
                            <br/>
                        {/foreach}
                    </td>
                    <td>{$record['ttl']}</td>
                    <td>
                        <a href="addonmodules.php?module=DomainManager&domain={$smarty.get.domain}&server_id={$smarty.get.server_id}&type={$record['type']}&name={$record['name']}&action=domain_edit_record">
                            <img src="images/edit.gif" border="0">
                        </a>
                    </td>
                    <td>
                        <a href="addonmodules.php?module=DomainManager&domain={$smarty.get.domain}&server_id={$smarty.get.server_id}&type={$record['type']}&name={$record['name']}&action=domain_delete_record"
                           onClick="return window.confirm('Вы точно хотите удалить запись {$record['name']} типа {$record['type']} ?');"
                        >
                            <img src="images/delete.gif" width="16" height="16" border="0">
                        </a>
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
</div>
