<div class="row pull-right" style="margin-right: 4px;">
    <div class="col-md-3">
        <div class="text-right">
            <!-- Split button -->
            <div class="btn-group">
                <form role="form" method="get" style="display: inline;">
                    <input type="hidden" name="module" value="DomainManager">
                    <input type="hidden" name="action" value="add_server">
                    <input type="submit" class="btn btn-success btn-sm" value="Добавить сервер">
                </form>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div id="tableBackground" class="tablebg">
        <table id="tableServersList" width="100%" class="datatable no-margin">
            <thead>
            <tr>
                <th>
                    Имя
                </th>
                <th>
                    ns'ки
                </th>
                <th>
                    IP
                </th>
                <th>
                    статус
                </th>
                <th style="width: 2%;"></th>
                <th style="width: 2%;"></th>
                <th style="width: 2%;"></th>
            </tr>
            </thead>
            <tbody>
            {foreach $serverList as $server}
                <tr class="product">
                    <td>
                        {$server.name}
                    </td>
                    <td>
                        {foreach from=$server.ns_list item=ns name=contentLoop}
                            {$ns}
                            <br/>
                        {/foreach}
                    </td>
                    <td>
                        {$server.ip}
                    </td>
                    <td>
                        {if $server.status eq 1}
                            Активен
                        {else}
                            Выключен
                        {/if}
                    </td>
                    <td>
                        <a href="addonmodules.php?module=DomainManager&action=edit_server&id={$server.id}"
                           title="Редактирование сервера">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                    <td>
                        <a href="addonmodules.php?module=DomainManager&action=change_status_server&id={$server.id}"
                           title="Изменение статуса сервера">
                            {if $server.status eq 1}
                                <i class="fas fa-power-off" style="color: red;"></i>
                            {else}
                                <i class="fas fa-power-off" style="color: green;"></i>
                            {/if}
                        </a>
                    </td>
                    <td>
                        <a href="addonmodules.php?module=DomainManager&action=delete_server&id={$server.id}"
                           title="Удалить сервер"
                           onClick="return window.confirm('Вы точно хотите удалить сервер  {$server.name} ?');"
                        >
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
</div>


