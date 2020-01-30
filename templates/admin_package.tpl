<div class="row pull-right" style="margin-right: 4px;">
    <div class="col-md-3">
        <div class="text-right">
            <!-- Split button -->
            <div class="btn-group">
                <form role="form" method="get" style="display: inline;">
                    <input type="hidden" name="module" value="DomainManager">
                    <input type="hidden" name="action" value="add_package">
                    <input type="submit" class="btn btn-success btn-sm" value="Добавить пакет">
                </form>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div id="tableBackground" class="tablebg">
        <table id="tablePackage" width="100%" class="datatable no-margin">
            <thead>
            <tr>
                <th>
                    Название
                </th>
                <th>
                    Связанный продукт/дополнение
                </th>
                <th>
                    Ограничение зоны
                </th>
                <th>
                    Сервер
                </th>
                <th>
                    Лимит зон
                </th>
                <th>
                    Записи
                </th>
                <th>
                    Лимит записей
                </th>
                <th style="width: 2%;"></th>
                <th style="width: 2%;"></th>
            </tr>
            </thead>
            <tbody>
            {foreach $packages as $package}
                <tr class="product">
                    <td>
                        {$package.title}
                    </td>
                    <td>
                        {$package.rel_product}
                    </td>
                    <td>
                        {$package.domain_zone_filter}
                    </td>
                    <td>
                        {$package.server_name}
                    </td>
                    <td>
                        {$package.domain_zone_limit}
                    </td>
                    <td>
                        A -> {$package.A}<br/>
                        NS -> {$package.NS}<br/>
                        MX -> {$package.MX}<br/>
                        DS -> {$package.DS}<br/>
                        SRV -> {$package.SRV}<br/>
                        TXT -> {$package.TXT}<br/>
                        PTR -> {$package.PTR}<br/>
                        CAA -> {$package.CAA}<br/>
                        AAAA -> {$package.AAAA}<br/>
                        DNAME -> {$package.DNAME}<br/>
                        CNAME -> {$package.CNAME}<br/>
                    </td>
                    <td>
                        {$package.record_total_limit}
                    </td>
                    <td>
                        <a href="addonmodules.php?module=DomainManager&action=edit_package&id={$package.id}"
                           title="Редактировать">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                    <td>
                        <a href="addonmodules.php?module=DomainManager&action=delete_package&id={$package.id}"
                           title="Удалить пакет">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
</div>


