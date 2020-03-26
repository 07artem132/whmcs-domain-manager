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
        <h2 style="display: initial;">{$LANG.DomainManager_manager_dns}</h2>
    </div>
    <div class="module-header" style="display: flow-root;    margin-bottom: 20px;   ">
        <div class="header-title" style=" float: left;">
            <h1 style="font-size: 20px;color: #45464c;line-height: 34px;padding: 0;margin: 0;">
                {$LANG.DomainManager_edit_zone} - {$domain}
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
                            {$LANG.DomainManager_add_record}
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
                <th>{$LANG.DomainManager_record_name}</th>
                <th>{$LANG.DomainManager_record_type}</th>
                <th>TTL</th>
                <th>{$LANG.DomainManager_record_context}</th>
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
                        <td class="cell-sm-12 form-group" style="vertical-align: inherit;">
                            <input type="text" class="form-control"
                                   name="record[{$smarty.foreach.recordData.index}][name]"
                                   value="{if $recordData.name eq {"`$smarty.get.domain`."}}@{else}{$recordData.name|regex_replace:{"/\.`$smarty.get.domain`.\$/"}:""}{/if}"
                                   title=""
                                   placeholder=""
                                   required=""
                            >
                        </td>
                        <td class="cell-sm-12" style="vertical-align: inherit;">
                            <input type="hidden" name="record[{$smarty.foreach.recordData.index}][type]"
                                   value="{$recordData.type}">
                            <input class="form-control" type="text" value="{$recordData.type}" disabled="" title=""
                            >
                        </td>
                        <td class="cell-sm-12 form-group" style="vertical-align: inherit;">
                            <input class="form-control" type="number"
                                   name="record[{$smarty.foreach.recordData.index}][ttl]"
                                   value="{$recordData.ttl}" title=""
                                   placeholder="TTL"
                                   required="" min="1"
                            >
                        </td>
                        <td class="cell-sm-12">
                            {if $recordData.type eq 'SRV'}
                                {assign var="content" value=" "|explode:$record->content}
                                <input class="form-control table-input" type="text"
                                       name="record[{$smarty.foreach.recordData.index}][records][{$smarty.foreach.record.index}][content][priority]"
                                       value="{$content.0}" title="" placeholder=""
                                >
                                <input class="form-control table-input" type="text"
                                       name="record[{$smarty.foreach.recordData.index}][records][{$smarty.foreach.record.index}][content][weight]"
                                       value="{$content.1}" title="" placeholder=""
                                >
                                <input class="form-control table-input" type="text"
                                       name="record[{$smarty.foreach.recordData.index}][records][{$smarty.foreach.record.index}][content][port]"
                                       value="{$content.2}" title="" placeholder=""
                                >
                                <input class="form-control table-input" type="text"
                                       name="record[{$smarty.foreach.recordData.index}][records][{$smarty.foreach.record.index}][content][target]"
                                       value="{$content.3}" title="" placeholder=""
                                >
                            {elseif $recordData.type eq 'MX'}
                                {assign var="content" value=" "|explode:$record->content}
                                <input class="form-control table-input" type="text"
                                       name="record[{$smarty.foreach.recordData.index}][records][{$smarty.foreach.record.index}][content][preference]"
                                       value="{$content.0}" title="" placeholder=""
                                >
                                <input class="form-control table-input" type="text"
                                       name="record[{$smarty.foreach.recordData.index}][records][{$smarty.foreach.record.index}][content][exchange]"
                                       value="{$content.1}" title="" placeholder=""
                                >
                            {else}
                                <input class="form-control table-input" type="text"
                                       name="record[{$smarty.foreach.recordData.index}][records][{$smarty.foreach.record.index}][content]"
                                       value="{$record->content}" title="" placeholder=""
                                >
                            {/if}
                            <input class="form-control table-input" type="hidden"
                                   name="record[{$smarty.foreach.recordData.index}][records][{$smarty.foreach.record.index}][disabled]"
                                   value="0" title="" placeholder=""
                            >
                        </td>
                        <td data-label="Actions" class="cell-sm-12 cell-actions" style="vertical-align: middle;">
                            <a href="/?m=DomainManager&api=record_delete&countRecords={count($recordData.records)}&domain={$domain|rawurlencode}&ttl={$recordData.ttl}&name={$recordData.name|rawurlencode}&type={$recordData.type|rawurlencode}&content={$record->content|rawurlencode}"
                               style="float: right;padding-left: 10px;"
                               title="{$LANG.DomainManager_record_delete}">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                {/foreach}
            {/foreach}
            </tbody>
        </table>
        <button class="btn btn-success" type="submit">{$LANG.DomainManager_save_records}</button>
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
                <h5 class="modal-title" id="AddZoneModalLabel">{$LANG.DomainManager_add_record_title_modal}</h5>
            </div>
            <form id="form_add_zone" method="POST" action="/?m=DomainManager&api=add&domain={$smarty.get.domain}">
                <div class="modal-body" style="display: flow-root;">
                    <div class="form-group" style="height: 30px;">
                        <label class="control-label col-sm-3"
                               for="record_name">{$LANG.DomainManager_record_name}</label>
                        <div class="col-sm-7">
                            <input class="form-control" type="text" id="record_name" name="record_name" value="">
                        </div>
                    </div>
                    <div id="selected_ip" class="form-group" style="height: 30px;">
                        <label class="control-label col-sm-3"
                               for="record_type">{$LANG.DomainManager_record_type}</label>
                        <div class="col-sm-7">
                            <select class="form-control" id="record_type" name="record_type">
                                {if $limit_A ne 0 and $type_stats.A lt $limit_A or $limit_A eq -1}
                                    <option value="A">A</option>
                                {/if}
                                {if $limit_SRV ne 0 and $type_stats.SRV lt $limit_SRV or $limit_SRV eq -1}
                                    <option value="SRV">SRV</option>
                                {/if}
                                {if $limit_TXT ne 0 and $type_stats.TXT lt $limit_TXT or $limit_TXT eq -1}
                                    <option value="TXT">TXT</option>
                                {/if}
                                {if $limit_CNAME ne 0 and $type_stats.CNAME lt $limit_CNAME or $limit_CNAME eq -1}
                                    <option value="CNAME">CNAME</option>
                                {/if}
                                {if $limit_MX ne 0 and $type_stats.MX lt $limit_MX or $limit_MX eq -1}
                                    <option value="MX">MX</option>
                                {/if}
                                {if $limit_NS ne 0 and $type_stats.NS lt $limit_NS or $limit_NS eq -1}
                                    <option value="NS">NS</option>
                                {/if}
                                {if $limit_DNAME ne 0 and $type_stats.DNAME lt $limit_DNAME or $limit_DNAME eq -1}
                                    <option value="DNAME">DNAME</option>
                                {/if}
                                {if $limit_AAAA ne 0 and $type_stats.AAAA lt $limit_AAAA or $limit_AAAA eq -1}
                                    <option value="AAAA">AAAA</option>
                                {/if}
                                {if $limit_CAA ne 0 and $type_stats.CAA lt $limit_CAA or $limit_CAA eq -1}
                                    <option value="CAA">CAA</option>
                                {/if}
                                {if $limit_PTR ne 0 and $type_stats.PTR lt $limit_PTR or $limit_PTR eq -1}
                                    <option value="PTR">PTR</option>
                                {/if}
                                {if $limit_DS ne 0 and $type_stats.DS lt $limit_DS or $limit_DS eq -1}
                                    <option value="DS">DS</option>
                                {/if}
                            </select>
                        </div>
                    </div>
                    <div id="custom_ip" class="form-group" style="height: 30px; ">
                        <label class="control-label col-sm-3" for="record_ttl">{$LANG.DomainManager_record_ttl}</label>
                        <div class="col-sm-7">
                            <input class="form-control" type="text" id="record_ttl" name="record_ttl" value="60">
                        </div>
                    </div>
                    <div id="custom_ip" class="form-group" style="height: 30px; ">
                        <label class="control-label col-sm-3"
                               for="record_context">{$LANG.DomainManager_record_context}</label>
                        <div class="col-sm-7" id="record_context_block">
                            <input class="form-control" type="text" id="record_context" name="record_context" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{$LANG.DomainManager_close_modal}</button>
                    <input type="submit" class="btn btn-success" value="{$LANG.DomainManager_add_record}">
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $("#record_type").change(function () {
        switch ($(this).val()) {
            case 'SRV':
                $('#record_context_block').html('' +
                    '<input class="form-control" type="text" style="margin-bottom: 15px;" id="record_context[Priority]" name="record_context[Priority]" placeholder="{$LANG.DomainManager_priority}" value="">' +
                    '<input class="form-control" type="text" style="margin-bottom: 15px;" id="record_context[weight]" name="record_context[weight]" placeholder="{$LANG.DomainManager_weight}" value="">' +
                    '<input class="form-control" type="text" style="margin-bottom: 15px;" id="record_context[port]" name="record_context[port]" placeholder="{$LANG.DomainManager_port}" value="">' +
                    '<input class="form-control" type="text" style="margin-bottom: 15px;" id="record_context[text]" name="record_context[text]" placeholder="{$LANG.DomainManager_text}" value="">');
                break;
            case 'MX':
                $('#record_context_block').html('' +
                    '<input class="form-control" type="text" style="margin-bottom: 15px;" id="record_context[preference]" name="record_context[preference]" placeholder="{$LANG.DomainManager_preference}" value="">' +
                    '<input class="form-control" type="text" style="margin-bottom: 15px;" id="record_context[exchange]" name="record_context[exchange]" placeholder="{$LANG.DomainManager_exchange}" value="">');
                break;
            default:
                $('#record_context_block').html('<input class="form-control" type="text" id="record_context" name="record_context" value="">');
                break;
        }
    });
</script>