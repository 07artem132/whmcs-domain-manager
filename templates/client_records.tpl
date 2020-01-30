<style>
    #mg-wrapper .badge {
        display: inline-block;
        min-width: 10px;
        padding: 3px 7px;
        font-size: 12px;
        font-weight: bold;
        color: #ffffff;
        line-height: 1;
        vertical-align: baseline;
        white-space: nowrap;
        text-align: center;
        background-color: #777777;
        border-radius: 10px;
    }

    #mg-wrapper .badge:empty {
        display: none;
    }

    #mg-wrapper .btn .badge {
        position: relative;
        top: -1px;
    }

    #mg-wrapper .btn-xs .badge,
    #mg-wrapper .btn-group-xs > .btn .badge {
        top: 0;
        padding: 1px 5px;
    }

    #mg-wrapper a.badge:hover,
    #mg-wrapper a.badge:focus {
        color: #ffffff;
        text-decoration: none;
        cursor: pointer;
    }

    #mg-wrapper .badge-success {
        color: #fff;
        background-color: #28a745 !important;
    }

    #mg-wrapper .module-main-header {
        border-bottom: 1px solid #DDDDDD;
    }

    #mg-wrapper .module-main-header h2 {
        letter-spacing: 0;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 22px;
        font-weight: lighter;
        line-height: 36px;
    }

    #mg-wrapper .header-search input {
        width: 240px;
    }

    #mg-wrapper .header-title > h4 {
        float: left;
        margin: 0 20px 0 0;
        line-height: 36px;
    }

    #mg-wrapper .input-icon > .form-control {
        padding-left: 40px;
    }

    #mg-wrapper .searchTable {
        background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABsAAAAWCAIAAAC+KHDcAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAgFJREFUeNqclI2SgjAMhG3BAxn/3v8pUUcEhXIfLNcrCr2Z64wIbbLdJJsY1/ebzYaHMZvPtbi/ZqyV6g8Lb1dVj9fr1batc85aux1XnufeJgI3QMHRY4FzvV5HFv2cVJ+m6W63y/MspLlI1rgf56Z5Agcp/MUrSRJoPp9PKLNpjDkcDln2FQ98QmzbDjiFidt2m4ZGcL/dbjo9Ho9pmkRoWlG83+84wAIHwYVxs8M+p13XVVXlU++fM0TdQx34IFNEGjr8VjBNKA6nJMG5PlIZyw8jlSLLsjUN6T44jkl4/YHYj4ugksS+oYR8rTXkkRfyE0PEEziZzkq2VkpjQm3NZTZ82lGxRjS7zn3G6310CkGE5fffLuZz4Ej6dG1d12+X+w7hk1OZSQwq6We6rRzIOs/Hg/5rF+2kmzHXSVlesPRK9PaTnjpHTYaILpeLJLnf79UYftE1KLyfZspQQ5bUHhZz1jM8YFGWpXYhQrLw4Q60ItGYkYMXhnb89Z7yb18LVN0mn7CmTAqcUa56RvJQX1KGoijQ1jSZXP/eAHXdhNMMLHygrFKQQUBDmrwTE9NA/b6A+OdY1eAgIMEJmrtPpxNptauDcwWOm2B9Pp9pcw8nMTRNM3XhYhdHbhpkbJmVJHbvY2eRmVjUkRUmhMHKJIQglItiNxz9AzGe5W8BBgCXqI5zhWUaagAAAABJRU5ErkJggg==);
        background-repeat: no-repeat;
        background-position: 8px 5px;
    }
</style>
{if $error != ''}
    <div class="alert  alert-danger" style="margin-top: 10px" role="alert">
        {$error}
    </div>
{/if}
<div id="mg-wrapper">
    <div class="module-main-header">
        <a href="/?m=DomainManager" class="btn btn-back btn-icon" style="height: inherit;">
            <i class="fa fa-arrow-left"></i>
        </a>
        <h2 style="display: initial;">Управление DNS</h2>
    </div>
    <div class="module-header" style="display: flow-root;    margin-bottom: 20px;   ">
        <div class="header-title" style=" float: left;">
            <h1 style="font-size: 20px;color: #45464c;line-height: 34px;padding: 0;margin: 0;">
                Редактор зоны - {$domain}
            </h1>
        </div>
        <div class="header-actions" style="float: right;">
            <div class="header-search">
                <div class="row-fluid-xs">
                    <div class="fluid-100" style="float: left;">
                        <div class="input-icon">
                            <input class="form-control searchTable" type="text" placeholder="Поиск" data-search=""
                                   style="    margin-right: 12px;">
                        </div>
                    </div>
                    <div class="fluid-0" style="float: left;">
                        <button class="btn btn-primary" data-act="addRecord"
                                data-toggle="modal" data-target="#AddZoneModal">
                            Добавить запись
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
    {if 'TXT'|array_key_exists:$type_stats}
        <div class="col-md-2 col-sm-4 col-xs-6" style="color: #45464C;">
            <b>TXT</b>
            <span class="badge badge-success">{$type_stats.TXT} / {if $limit_TXT eq -1}
                    <span>∞</span>
                {else}
                    {$limit_TXT}
                {/if}
            </span>
        </div>
    {/if}
    {if 'NS'|array_key_exists:$type_stats}
        <div class="col-md-2 col-sm-4 col-xs-6" style="color: #45464C;">
            <b>NS</b>
            <span class="badge badge-success">{$type_stats.NS} / {if $limit_NS eq -1}
                    <span>∞</span>
                {else}
                    {$limit_NS}
                {/if}
            </span>
        </div>
    {/if}
    {if 'DNAME'|array_key_exists:$type_stats}
        <div class="col-md-2 col-sm-4 col-xs-6" style="color: #45464C;">
            <b>NS</b>
            <span class="badge badge-success">{$type_stats.DNAME} / {if $limit_DNAME eq -1}
                    <span>∞</span>
                {else}
                    {$limit_DNAME}
                {/if}
            </span>
        </div>
    {/if}
    {if 'SRV'|array_key_exists:$type_stats}
        <div class="col-md-2 col-sm-4 col-xs-6" style="color: #45464C;">
            <b>SRV</b>
            <span class="badge badge-success">{$type_stats.SRV} / {if $limit_SRV eq -1}
                    <span>∞</span>
                {else}
                    {$limit_SRV}
                {/if}
            </span>
        </div>
    {/if}
    {if 'MX'|array_key_exists:$type_stats}
        <div class="col-md-2 col-sm-4 col-xs-6" style="color: #45464C;">
            <b>MX</b>
            <span class="badge badge-success">{$type_stats.MX} / {if $limit_MX eq -1}
                    <span>∞</span>
                {else}
                    {$limit_MX}
                {/if}
            </span>
        </div>
    {/if}
    {if 'CNAME'|array_key_exists:$type_stats}
        <div class="col-md-2 col-sm-4 col-xs-6" style="color: #45464C;">
            <b>CNAME</b>
            <span class="badge badge-success">{$type_stats.CNAME} / {if $limit_CNAME eq -1}
                    <span>∞</span>
                {else}
                    {$limit_CNAME}
                {/if}
            </span>
        </div>
    {/if}
    {if 'PTR'|array_key_exists:$type_stats}
        <div class="col-md-2 col-sm-4 col-xs-6" style="color: #45464C;">
            <b>PTR</b>
            <span class="badge badge-success">{$type_stats.PTR} / {if $limit_PTR eq -1}
                    <span>∞</span>
                {else}
                    {$limit_PTR}
                {/if}
            </span>
        </div>
    {/if}
    {if 'DS'|array_key_exists:$type_stats}
        <div class="col-md-2 col-sm-4 col-xs-6" style="color: #45464C;">
            <b>DS</b>
            <span class="badge badge-success">{$type_stats.DS} / {if $limit_DS eq -1}
                    <span>∞</span>
                {else}
                    {$limit_DS}
                {/if}
            </span>
        </div>
    {/if}
    {if 'AAAA'|array_key_exists:$type_stats}
        <div class="col-md-2 col-sm-4 col-xs-6" style="color: #45464C;">
            <b>AAAA</b>
            <span class="badge badge-success">{$type_stats.AAAA} / {if $limit_AAAA eq -1}
                    <span>∞</span>
                {else}
                    {$limit_AAAA}
                {/if}
            </span>
        </div>
    {/if}
    {if 'CAA'|array_key_exists:$type_stats}
        <div class="col-md-2 col-sm-4 col-xs-6" style="color: #45464C;">
            <b>CAA</b>
            <span class="badge badge-success">{$type_stats.CAA} / {if $limit_CAA eq -1}
                    <span>∞</span>
                {else}
                    {$limit_CAA}
                {/if}
            </span>
        </div>
    {/if}
    {if 'A'|array_key_exists:$type_stats}
        <div class="col-md-2 col-sm-4 col-xs-6" style="color: #45464C;">
            <b>A</b>
            <span class="badge badge-success">{$type_stats.A} / {if $limit_A eq -1}
                    <span>∞</span>
                {else}
                    {$limit_A}
                {/if}
            </span>
        </div>
    {/if}
    <div class="col-md-2 col-sm-4 col-xs-6" style="color: #45464C;">
        <b>TOTAL</b>
        <span class="badge badge-success">{$total_count} / {if $limit_record_total_limit eq -1}
                <span>∞</span>
            {else}
                {$limit_record_total_limit}
            {/if}
            </span>
    </div>
    <form method="post">
        <table class="table">
            <thead>
            <tr>
                <th>Имя</th>
                <th>Тип</th>
                <th>TTL</th>
                <th>Значение</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody id="edit-form">
            {foreach from=$records item=$recordData name="recordData"}
                {foreach from=$recordData.records item=$record name="record"}
                    {if $recordData.type eq SOA}
                        {continue}
                    {/if}
                    <tr id="record0" class="record">
                        <td data-label="Имя" class="cell-sm-12 form-group">
                            <input type="text" class="form-control"
                                   name="record[{$smarty.foreach.recordData.index}][name]"
                                   value="{$recordData.name}" title=""
                                   placeholder="Имя"
                                   required=""
                                   data-original-title="Name of the owner, i.e. name of the node this resource record is related to.">
                        </td>
                        <td data-label="Тип" class="cell-sm-12">
                            <input type="hidden" name="record[{$smarty.foreach.recordData.index}][type]"
                                   value="{$recordData.type}">
                            <input class="form-control" type="text" value="{$recordData.type}" disabled="" title=""
                            >
                        </td>
                        <td data-label="TTL" class="cell-sm-12 form-group">
                            <input class="form-control" type="number"
                                   name="record[{$smarty.foreach.recordData.index}][ttl]"
                                   value="{$recordData.ttl}" title=""
                                   placeholder="TTL"
                                   required="" min="1"
                            >
                        </td>
                        <td data-label="Значение" class="cell-sm-12">
                            <input class="form-control table-input" type="text"
                                   name="record[{$smarty.foreach.recordData.index}][records][{$smarty.foreach.record.index}][content]"
                                   value="{$record->content}" title="" placeholder=""
                            >
                            <input class="form-control table-input" type="hidden"
                                   name="record[{$smarty.foreach.recordData.index}][records][{$smarty.foreach.record.index}][disabled]"
                                   value="0" title="" placeholder=""
                            >
                        </td>
                        <td data-label="Actions" class="cell-sm-12 cell-actions" style="vertical-align: middle;">
                            <a href="/?m=DomainManager&api=record_delete&domain={$domain|rawurlencode}&ttl={$recordData.ttl}&name={$recordData.name|rawurlencode}&type={$recordData.type|rawurlencode}&content={$record->content|rawurlencode}"
                               style="float: right;padding-left: 10px;"
                               title="Удалить запись">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                {/foreach}
            {/foreach}
            </tbody>
        </table>
        <button class="btn btn-success" type="submit">Сохранить изменения</button>
    </form>
</div>

<script>
    {literal}
    (function ($) {
        $(document).ready(function () {
            $(document).delegate("[data-search]", 'keyup', function () {
                var search = $(this).val().toLowerCase();

                if (!search.length) {
                    $('.no-matches').hide();
                    $('#edit-form > tr.record').show().css('display', "");
                    $('.no-matches').css('display', 'none');
                    if ($('#edit-form > tr.record').length == 0) {
                        $('.empty-record').removeClass('hidden');
                    }
                    return;
                    return;
                }

                $('#edit-form > tr.record').each(function () {
                    if ($('input', this).filter(function () {
                        return this.value.toLowerCase().indexOf(search) > -1;
                    }).length) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });

                $('#edit-form > tr.no-matches').toggle(!$('#edit-form > tr.record:visible').length);

                if (!$('#edit-form > tr.record:visible').length == true) {
                    $('.empty-record').addClass('hidden');
                }
                if (!search && $('#edit-form > tr.record').length == 0) {
                    $('.empty-record').removeClass('hidden');
                }
            });
        });
    })(jQuery);
    {/literal}
</script>


<div class="modal fade" id="AddZoneModal" tabindex="-1" role="dialog" aria-labelledby="AddZoneModal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="AddZoneModalLabel">Добавление зоны</h5>
            </div>
            <form id="form_add_zone" method="POST" action="/?m=DomainManager&api=add&domain={$smarty.get.domain}">
                <div class="modal-body" style="display: flow-root;">
                    <div class="form-group" style="height: 30px;">
                        <label class="control-label col-sm-3" for="record_name">Имя</label>
                        <div class="col-sm-7">
                            <input class="form-control" type="text" id="record_name" name="record_name" value=""
                                   required=""
                            >
                        </div>
                    </div>
                    <div id="selected_ip" class="form-group" style="height: 30px;">
                        <label class="control-label col-sm-3" for="record_type">Тип</label>
                        <div class="col-sm-7">
                            <select class="form-control" id="record_type" name="record_type">
                                <option value="A">A</option>
                                <option value="SRV">SRV</option>
                                <option value="TXT">TXT</option>
                                <option value="CNAME">CNAME</option>
                                <option value="MX">MX</option>
                                <option value="NS">NS</option>
                                <option value="DNAME">DNAME</option>
                                <option value="AAAA">AAAA</option>
                                <option value="CAA">CAA</option>
                                <option value="PTR">PTR</option>
                                <option value="DS">DS</option>
                            </select>
                        </div>
                    </div>
                    <div id="custom_ip" class="form-group" style="height: 30px; ">
                        <label class="control-label col-sm-3" for="record_ttl">TTL (секунды)</label>
                        <div class="col-sm-7">
                            <input class="form-control" type="text" id="record_ttl" name="record_ttl" value="60">
                        </div>
                    </div>
                    <div id="custom_ip" class="form-group" style="height: 30px; ">
                        <label class="control-label col-sm-3" for="record_context">Значение</label>
                        <div class="col-sm-7">
                            <input class="form-control" type="text" id="record_context" name="record_context" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <input type="submit" class="btn btn-success" value="Добавить запись">
                </div>
            </form>
        </div>
    </div>
</div>