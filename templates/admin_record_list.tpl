<div class="col-md-12">
    <div class="col-md-7">
        <p>
            <strong>Записи домена:</strong>
            {$smarty.get.domain}
        </p>
    </div>
    <div class="col-md-5">
        <div class="text-right">
            <!-- Split button -->
            <div class="btn-group">
				<span data-toggle="modal" data-target="#dialog_addRecord">
                    <button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom"
                            title="Добавить запись">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        Добавить запись
                    </button>
                    <button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom"
                            title="Загрузить записи">
                        <span class="glyphicon glyphicon-import" aria-hidden="true"></span>
                        Загрузить записи
                    </button>
                    <button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="bottom"
                            title="Выгрузить записи">
                        <span class="glyphicon glyphicon-export" aria-hidden="true"></span>
                        Выгрузить записи
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom"
                            title="Удалить домен">
                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                        Удалить домен
                    </button>
				</span>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div id="tableBackground" class="tablebg">
        <table id="tableDomainRecordsList" width="100%" class="datatable no-margin">
            <thead>
            <tr>
                <th style="width: 2%;"></th>
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
                    <td><input type="checkbox"
                               name="{$record['name']}_{$record['type']}_{$record['ttl']}_{','|implode:$record['records']}">
                    </td>
                    <td>{$record['name']}</td>
                    <td>{$record['type']}</td>
                    <td>
                        {foreach from=$record['records'] item=record name=contentLoop}
                            {$record->content}
                            <br/>
                        {/foreach}
                    </td>
                    <td>{$record['ttl']}</td>
                    <td>
                        <a href="{$modulelink}&action=record_list&domain={$domain->name|substr:0:-1}">
                            <img src="images/edit.gif" border="0">
                        </a>
                    </td>
                    <td>
                        <a href="#">
                            <img src="images/delete.gif" width="16" height="16" border="0">
                        </a>
                    </td>                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
</div>
