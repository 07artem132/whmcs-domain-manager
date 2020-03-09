<div class="row pull-right" style="margin-right: 4px;">
    <div class="col-md-3">
        <div class="text-right">
            <!-- Split button -->
            <div class="btn-group">
                <form role="form" method="get" style="display: inline;">
                    <input type="hidden" name="module" value="DomainManager">
                    <input type="hidden" name="action" value="add_domain">
                    <input type="submit" class="btn btn-success btn-sm" value="Добавить домен">
                </form>
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
                    Клиент
                </th>
                <th>
                    Услуга
                </th>
                <th>
                    DNS сервер
                </th>
                <th>
                    Статус зоны
                </th>
                <th style="width: 2%;"></th>
                <th style="width: 2%;"></th>
                <th style="width: 2%;"></th>
                <th style="width: 2%;"></th>
                <th style="width: 2%;"></th>
                <th style="width: 2%;"></th>
            </tr>
            </thead>
            <tbody>
            {foreach $domainList as $domain}
                <tr class="product">
                    <td>
                        {$domain.domain}
                    </td>
                    <td>
                        <a href="clientssummary.php?userid={$domain.client_id}">{$domain.client_name}</a>
                    </td>
                    <td>
                        {if !empty($domain.product_url)}
                            <a href="{$domain.product_url}">{$domain.product_name}</a>
                        {else}
                            {$domain.product_name}
                        {/if}
                    </td>
                    <td>
                        {$domain.server}
                    </td>
                    <td>
                        {$domain.status}
                    </td>
                    <td>
                        <a href="addonmodules.php?module=DomainManager&action=transfer_domain&domain={$domain.domain}"
                           title="Перекинуть на другую услугу">
                            <i class="fas fa-share-square"></i>
                        </a>
                    </td>
                    <td>
                        <a href="addonmodules.php?module=DomainManager&action=record_list&server_id={$domain.server_id}&domain={$domain.domain}"
                           title="Редактирование dns записей">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                    <td>
                        <a href="addonmodules.php?module=DomainManager&action=restore_last_backup&server_id={$domain.server_id}&domain={$domain.domain}"
                           title="Востановить последнюю резервную копию"
                           onClick="return window.confirm('Вы точно хотите востановить последнюю резервную копию для домена {$domain.domain} ?');"
                        >
                            <i class="fas fa-cloud-upload-alt"></i>
                        </a>
                    </td>
                    <td>
                        <a href="addonmodules.php?module=DomainManager&action=create_backup&server_id={$domain.server_id}&domain={$domain.domain}"
                           title="Скачать резервную копию">
                            <i class="fas fa-download"></i>
                        </a>
                    </td>
                    <td>
                        <a href="addonmodules.php?module=DomainManager&action=restore_backup&server_id={$domain.server_id}"
                           title="Загрузить резервную копию">
                            <i class="fas fa-upload"></i>
                        </a>
                    </td>
                    <td>
                        <a href="addonmodules.php?module=DomainManager&action=delete_domain&server_id={$domain.server_id}&domain={$domain.domain}"
                           title="Удалить домен"
                           onClick="return window.confirm('Вы точно хотите удалить домен {$domain.domain} ?');"
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


