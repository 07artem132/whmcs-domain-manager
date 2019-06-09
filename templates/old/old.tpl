<!-- Add Modal -->
<div class="bootstrap">
    <div class="modal fade" id="dialog_addRecord" tabindex="-1" role="dialog" aria-labelledby="dialog_addRecord"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{$LANG.admin_manage_records_addrecord}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div id="sdns_z-name_0" class="col-md-3">
                            <label for="sdns_name_0">{$LANG.global_dns_name}:</label>
                            <input type="text" class="form-padding form-control" name="sdns_name_0" id="sdns_name_0"
                                   placeholder="{$domain->domain}">
                        </div>
                        <div class="col-md-2">
                            <label for="sdns_type_0">{$LANG.global_dns_type}:</label>
                            <select class="form-padding form-control" name="sdns_type_0" id="sdns_type_0">
                                {foreach from=$records item=type}
                                    <option value="{$type}">{$type}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div id="sdns_z-content_0" class="col-md-4">
                            <label for="sdns_content_0">{$LANG.global_dns_content}:</label>
                            <input type="text" class="form-padding form-control" name="sdns_content_0"
                                   id="sdns_content_0">
                        </div>
                        <div id="sdns_z-prio_0" class="col-md-1">
                            <label for="sdns_prio_0">{$LANG.global_dns_prio}:</label>
                            <input type="text" class="form-padding form-control" name="sdns_prio_0" id="sdns_prio_0">
                        </div>
                        <div class="col-md-2">
                            <label for="sdns_ttl_0">{$LANG.global_dns_ttl}:</label>
                            <select class="form-padding form-control" name="sdns_ttl_0" id="sdns_ttl_0">
                                <option value="60">1 {$LANG.global_dns_minute}</option>
                                <option value="300">5 {$LANG.global_dns_minutes}</option>
                                <option SELECTED value="3600">1 {$LANG.global_dns_hour}</option>
                                <option value="86400">1 {$LANG.global_dns_day}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal"
                            onclick="record_add()">{$LANG.global_btn_add}</button>
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">{$LANG.global_btn_cancel}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="bootstrap">
    <div class="modal fade" id="dialog_deleteRecord" tabindex="-1" role="dialog" aria-labelledby="dialog_deleteRecord"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{$LANG.global_head_delete_record}</h4>
                </div>
                <div class="modal-body">
                    <p>{$LANG.global_text_delete_record}</p>
                    <br/>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"
                            onclick="record_delete()">{$LANG.global_btn_delete}</button>
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">{$LANG.global_btn_cancel}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Apply Template -->
<div class="bootstrap">
    <div class="modal fade" id="dialog_applyTemplate" tabindex="-1" role="dialog" aria-labelledby="dialog_applyTemplate"
         aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{$LANG.admin_manage_records_applytemplate}</h4>
                </div>
                <form role="form" id="applytemplate">
                    <input type="hidden" name="sdns_form" value="applytemplate">
                    <input type="hidden" name="sdns_domain" value="{$domain->domain}">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-12">
                                <label for="sdns_apply_template">{$LANG.admin_manage_records_selecttemplate}:</label>
                                <select class="form-padding form-control" name="sdns_apply_template"
                                        id="sdns_apply_template">
                                    <option value="0">{$LANG.global_general_defaulttemplate}</option>
                                    <option value="{$product->id}">{$product->name}</option>
                                </select>

                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal"
                                onclick="applyTemplate();">{$LANG.global_btn_apply}</button>
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal">{$LANG.global_btn_cancel}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Import Zone -->
<div class="bootstrap">
    <div class="modal fade" id="dialog_importZone" tabindex="-1" role="dialog" aria-labelledby="dialog_importZone"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{$LANG.admin_manage_records_importzone}</h4>
                </div>
                <form role="form" id="importzone">
                    <input type="hidden" name="sdns_form" value="importzone">
                    <input type="hidden" name="sdns_domain" value="{$domain->domain}">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-12">
                                <p>{$LANG.admin_manage_text_importzone}</p>
                                <textarea id="textarea_import" name="sdns_importzone"
                                          class="form-padding form-control"></textarea>
                                <div class="checkbox chx_label">
                                    <input id="overwrite" type="checkbox" name="sdns_overwrite"></input>
                                    <label for="overwrite">{$LANG.admin_manage_text_importzoneoverwrite}</label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal"
                                onclick="importZone();">{$LANG.global_btn_import}</button>
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal">{$LANG.global_btn_cancel}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Export Zone -->
<div class="bootstrap">
    <div class="modal fade" id="dialog_exportZone" tabindex="-1" role="dialog" aria-labelledby="dialog_exportZone"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{$LANG.admin_manage_records_exportzone}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <textarea id="textarea_export" name="sdns_exportzone"
                                      class="form-padding form-control">{$LANG.global_table_loading_data}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{$LANG.global_btn_close}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Zone -->
<div class="bootstrap">
    <div class="modal fade" id="dialog_deleteZone" tabindex="-1" role="dialog" aria-labelledby="dialog_deleteZone"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <div id="sdns_zone_name">{$LANG.admin_manage_records_deletezone}: <span>{$domain->domain}</span>
                        </div>
                    </h4>
                </div>
                <div class="modal-body">
                    <p>{$LANG.global_text_delete_zone}</p>
                    <br/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"
                            onclick="zone_delete({$domain->id})">{$LANG.global_btn_delete}</button>
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">{$LANG.global_btn_cancel}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="bootstrap">
    <div class="modal fade" id="dialog_addRecord" tabindex="-1" role="dialog" aria-labelledby="dialog_addRecord"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{$LANG.global_head_add_record}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div id="sdns_z-name_0" class="col-md-3">
                            <label for="sdns_name_0">{$LANG.global_dns_name}:</label>
                            <input type="text" class="form-padding form-control" name="sdns_name_0" id="sdns_name_0"
                                   placeholder="{literal}{domain}{/literal}">
                        </div>
                        <div class="col-md-2">
                            <label for="sdns_type_0">{$LANG.global_dns_type}:</label>
                            <select class="form-padding form-control" name="sdns_type_0" id="sdns_type_0">
                                {foreach from=$records item=type}
                                    <option value="{$type}">{$type}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div id="sdns_z-content_0" class="col-md-4">
                            <label for="sdns_content_0">{$LANG.global_dns_content}:</label>
                            <input type="text" class="form-padding form-control" name="sdns_content_0"
                                   id="sdns_content_0">
                        </div>
                        <div id="sdns_z-prio_0" class="col-md-1">
                            <label for="sdns_prio_0">{$LANG.global_dns_prio}:</label>
                            <input type="text" class="form-padding form-control" name="sdns_prio_0"
                                   id="sdns_prio_0">
                        </div>
                        <div class="col-md-2">
                            <label for="sdns_ttl_0">{$LANG.global_dns_ttl}:</label>
                            <select class="form-padding form-control" name="sdns_ttl_0" id="sdns_ttl_0">
                                <option value="60">1 {$LANG.global_dns_minute}</option>
                                <option value="300">5 {$LANG.global_dns_minutes}</option>
                                <option SELECTED value="3600">1 {$LANG.global_dns_hour}</option>
                                <option value="86400">1 {$LANG.global_dns_day}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal"
                            onclick="record_add('template')">{$LANG.global_btn_add}</button>
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">{$LANG.global_btn_cancel}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="bootstrap">
    <div class="modal fade" id="dialog_deleteRecord" tabindex="-1" role="dialog"
         aria-labelledby="dialog_deleteRecord"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{$LANG.global_head_delete_record}</h4>
                </div>
                <div class="modal-body">
                    <p>{$LANG.global_text_delete_record}</p>
                    <br/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"
                            onclick="record_delete('template')">{$LANG.global_btn_delete}</button>
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">{$LANG.global_btn_cancel}</button>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="sdns_record"></div>
