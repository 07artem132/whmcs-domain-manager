<div class="row pull-right" style="margin-right: 4px;">
    <div class="col-md-3">
        <div class="text-right">
            <!-- Split button -->
            <div class="btn-group">
                <form role="form" method="get" style="display: inline;">
                    <input type="hidden" name="module" value="DomainManager">
                    <input type="hidden" name="action" value="domain_add_blacklist">
                    <input type="submit" class="btn btn-success btn-sm" value="Добавить домен">
                </form>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div id="tableBackground" class="tablebg">
        <table id="tableDomainsBlackList" width="100%" class="datatable no-margin">
            <thead>
            <tr>
                <th>
                    Домен
                </th>
                <th>
                    Дата создания
                </th>
                <th style="width: 2%;"></th>
            </tr>
            </thead>
            <tbody>
            {foreach $blacklist as $domain}
                <tr class="product">
                    <td>
                        {$domain.domain}
                    </td>
                    <td>
                        {$domain.created_at}
                    </td>
                    <td>
                        <a href="addonmodules.php?module=DomainManager&action=remove_blacklist&id={$domain.id}"
                           title="Удалить домен"
                           onClick="return window.confirm('Вы точно хотите удалить домен {$domain.domain} из черного списка ?');"
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


