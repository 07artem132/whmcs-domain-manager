<script src="/modules/addons/DomainManager/templates/js/client/client.js"></script>
{literal}
    <style>
        .panel-title > a:before {
            font-family: "Font Awesome 5 Brands", "Font Awesome 5 Pro";
            content: "\f077";
            padding-right: 5px;
        }

        .panel-title > a.collapsed:before {
            content: "\f078";
        }

        .panel-title > a:hover,
        .panel-title > a:active,
        .panel-title > a:focus {
            text-decoration: none;
        }

        .header-actions {
            display: table-cell;
            width: 100%;
        }

        .panel-title a {
            color: #333;
            font-weight: 700;
        }

        .panel-title {
            vertical-align: top;
            white-space: nowrap;
            line-height: 36px;
        }

        .panel-heading {
            display: table;
            line-height: 14px;
            width: 100%;
        }

        .panel-heading > .header-actions > .badge {
            float: left;
            margin: 11px 0 0 10px;
            background: #B3B0B0;
        }

        .badge-success {
            color: #fff;
            background-color: #28a745 !important;
        }

        #mg-wrapper .list-info {
            margin: 0;
            padding: 0;
        }

        #mg-wrapper .panel-list {
            display: block;
            padding: 0;
            margin: 0;
            list-style: none;
        }

        #mg-wrapper .panel-list > li {
            padding: 20px;
            border-bottom: 1px solid #e2e7eb;
        }

        #mg-wrapper .list-info > li {
            list-style: none;
            padding-top: 8px;
            padding-bottom: 8px;
        }

        #mg-wrapper .panel-list .list-actions {
            float: right;
        }

        .mg-ca-buttons {
            text-align: right;
        }

        #mg-wrapper .table > thead > tr > th, #mg-wrapper .table > tbody > tr > td {
            vertical-align: middle !important;
        }

        @media only screen and (max-width: 600px) {

            #addBackupFile {

                margin-left: 8px !important;
            }

        }

        @media only screen and (max-width: 768px) {


            #backupsList_length label {
                display: flex;
                width: 170px;
            }

            #backupsList tbody tr {
                border-collapse: collapse !important;
            }

            #backupsList.dataTable {
                border-collapse: collapse !important;
            }

        }

        @media (max-width: 768px) {
            #mg-wrapper .mg-ca-backup-table thead {
                display: table-header-group !important;
                text-align: center;
            }
        }

        .backups-action-button {
            height: 36px !important;
            width: 36px !important;
        }


        #pageheader h1 {
            color: #45464c;
            font-size: 20px;
            line-height: 34px;
        }

        .progress-bar {
            color: black !important;
        }

        .nozones {
            text-align: center;
            font-size: 18px;
        }

        .mg-custom-header {
            margin-top: 10px !important;
        }

        #pageheader .myheader-title {
            padding-top: 5px;
        }


        .mg-backup-task-header {
            margin-top: 5px !important;
        }

        th.dt-center, td.dt-center {
            text-align: center;
        }

        .myheader-title h1 {
            font-size: 24px !important;
        }

        .mg-backuplist-header {
            margin-top: 8px !important;
        }

        #setstable tr :last-child a {
            float: right;
        }

        #setstable tr :last-child button {
            float: right;
        }

        #setstable_info {
            margin: 0px !important;
        }

        #setstable_paginate .pagination {
            margin: 0px !important;
        }

        .backupsHeader {
            margin: 10px 0 !important;
        }

        .module-main-header {
            margin: 0px !important;
        }


        .caHeaderButtonBackup {
            color: white !important;
            margin-right: 0px !important;
        }

        #MGLoader {
            background-color: rgba(0, 0, 0, 0.5);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 60000;
        }

        #MGLoader img {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 300px;
            margin-left: -150px;
        }

        #mg-wrapper #mg-container .module-main-header {
            border-bottom: 1px solid #DDDDDD;
        }

        #mg-wrapper #mg-container .module-main-header h2 {
            letter-spacing: 0;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 22px;
            font-weight: lighter;
            line-height: 36px;
        }

        #mg-wrapper #mg-container .module-main-header .btn-back {
            border: 0;
            background: none;
            font-size: 15px;
            line-height: 36px;
            color: #8a8e99;
            padding: 0px;
            display: inline-block;
            vertical-align: top;
            box-shadow: none;
            margin-right: 5px;
        }

        #mg-wrapper #mg-container .module-main-header .btn-back:focus {
            outline: none;
        }

        #mg-wrapper #mg-container .module-main-header .btn-back::-moz-focus-inner {
            border: 0;
        }

        #mg-wrapper {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 14px;
            line-height: 1.42857143;
            color: #333;
        }

        #mg-wrapper *:focus {
            outline: none;
        }

        #mg-wrapper *::-moz-focus-inner {
            border: 0;
        }

        #mg-wrapper .btn:hover,
        #mg-wrapper .btn:active,
        #mg-wrapper .btn:focus,
        #mg-wrapper a:focus,
        #mg-wrapper a:active,
        #mg-wrapper a:hover,
        #mg-wrapper a {
            text-decoration: none !important;
            outline: none !important;
        }

        #mg-wrapper .form-group .fa-question-circle {
            font-size: 16px;
            color: #337ab7;
            line-height: 36px;
        }

        #mg-wrapper .module-content {
            background-color: #fff;
        }

        #mg-wrapper .module-header h1 {
            font-size: 20px;
            color: #45464c;
            line-height: 34px;
        }

        #mg-wrapper .module-body h4 {
            font-size: 16px;
        }

        /* forms */
        #mg-wrapper .form-group .btn span {
            line-height: 1.5;
        }

        #mg-wrapper a.select2-choice .select2-chosen {
            line-height: 34px;
            color: #555;
        }

        #mg-wrapper .form-group span:not(.input-group-addon),
        #mg-wrapper .form-fluid span:not(.input-group-addon) {
            line-height: 36px;
        }

        #mg-wrapper label:not(.control-label) {
            font-weight: 400;
            font-size: 12px;
            line-height: 34px;
        }

        #mg-wrapper label.control-label {
            font-size: 12px;
            font-weight: 800;
        }

        #mg-wrapper .input-group .form-control.first {
            border-radius: 4px 0 0 4px !important;

        }

        #mg-wrapper .input-group .form-control.last {
            border-radius: 0 4px 4px 0 !important;
        }

        #mg-wrapper .input-group-addon:not(.first):not(.last),
        #mg-wrapper .input-group-btn:not(.first):not(.last),
        #mg-wrapper .input-group .form-control:not(.first):not(.last) {
            border-radius: 0;
            border-width: 1px 0;
        }

        #mg-wrapper .well .form-actions {
            border-top: 1px solid #e6e6e6;
        }

        #mg-wrapper p {
            line-height: 22px;
        }

        /* tables */
        #mg-wrapper .table {
            border-bottom: 1px solid #e4e8f0;
        }

        #mg-wrapper .table > tbody > tr > td.cell-actions {
            text-align: right;
        }

        #mg-wrapper .table > tbody > tr > td.cell-actions > * {
            display: inline-block;
            margin: 0;
        }

        #mg-wrapper .list-info .btn-icon .fa,
        #mg-wrapper .table > tbody > tr > td .btn-icon .fa {
            font-size: 16px;
            line-height: 22px;
            color: #8a8e99;
        }

        #mg-wrapper .table > tbody > tr > td.cell-actions .btn-icon .fa,
        #mg-wrapper .table > tbody > tr > td.cell-actions .btn-icon .caret {
            color: #8a8e99;
        }

        #mg-wrapper .table tr th,
        #mg-wrapper .table tr td {
            border-color: #e4e8f0;
        }

        #mg-wrapper .table thead tr th {
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 800;
            color: #a1a5b3;
        }

        #mg-wrapper .table tr td {
            font-size: 12px;
            line-height: 22px;
        }

        #mg-wrapper .table tr td a {
            cursor: pointer;
        }

        #mg-wrapper .table-marginless {
            margin: 0;
        }

        /* buttons */
        #mg-wrapper .btn {
            font-size: 12px;
            line-height: 22px;
        }

        #mg-wrapper .btn.btn-icon, #mg-wrapper .btn.btn-icon:hover, #mg-wrapper .btn.btn-icon:active, #mg-wrapper .btn-group.open .btn.btn-icon {
            background: transparent;
            border: none;
            box-shadow: none;
            border-radius: 0;
        }

        #mg-wrapper .fa-question-circle {
            cursor: pointer;
        }

        #mg-wrapper a.panel-heading h6 {
            color: #5c5e66;
        }

        #mg-wrapper a.panel-heading:hover h6 {
            color: #000;
        }

        #mg-wrapper table input.dnssec {
            height: auto !important;
            width: 100%;
        }


        select[name="setstable_length"] {
            display: inline !important;
            width: auto !important;
        }

        @media (max-width: 600px) {
            .dataTables_wrapper .dataTables_filter {
                float: right;
                margin-top: 0px !important;
                width: auto !important;
            }
        }

        .searchTableMG-CA {
            /*margin-right: 20px !important;*/
        }

        @media only screen and (max-width: 720px) {
            .searchTableMG-CA {
                /*margin-right: 20px !important;*/
                /*margin-top: 20px !important;*/
            }
        }

        #setstable_length {
            text-align: left !important;
        }


        .panel-body {
            padding: unset;
        }
    </style>
{/literal}
{if $error != ''}
    <div class="alert  alert-danger" style="margin-top: 10px" role="alert">
        {$error}
    </div>
{/if}
<div id="mg-wrapper" class="row">
    <div class="panel-group" id="accordion">
        {foreach key=id item=package from=$service_packages}
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" href="#collapse{$package.product_id}">
                            {$LANG.DomainManager_service} #{$package.product_id} {if $package.domain != ""}
                                - {$package.domain}
                            {/if}
                        </a>
                    </h4>
                    <div class="header-actions">
                        <div class="badge badge-success">{$LANG.DomainManager_zone} <span>
                             {$package.domain_count} /{if $package.domain_zone_limit eq -1}
                                    <span>∞</span>
                                {else}
                                {$package.domain_zone_limit}
                                {/if}
                            </span></div>
                        <button type="button"
                                style="margin-left: 10px;float: right; margin-top: -2px;margin-bottom: -2px;"
                                class="btn btn-primary btn-sm" data-toggle="modal" data-target="#AddZoneModal"
                                data-limit="{$package.domain_zone_filter}"
                                data-rel-id="{$package.product_id}"
                                data-rel-type="1">
                            {$LANG.DomainManager_add_zone}
                        </button>
                    </div>
                </div>
                <div id="collapse{$package.product_id}" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <ul class="panel-list list-info">
                            {foreach  item=domain from=$package.package_domains}
                                <li>
                                    <a class="mg-ca-zone" href="https://{$domain}">{$domain}</a>
                                    <a href="/?m=DomainManager&api=delete&domain={$domain}"
                                       onClick="return window.confirm('{$LANG.DomainManager_delete_zone_confirm|replace:'%s':$domain|escape}');"
                                       style="float: right;padding-left: 10px;"
                                       title="{$LANG.DomainManager_delete_zone}">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                    <a href="/?m=DomainManager&api=edit&domain={$domain}"
                                       style="float: right;padding-left: 10px;"
                                       title="{$LANG.DomainManager_edit_zone}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </li>
                                {foreachelse}
                                <li>
                                    {$LANG.DomainManager_empty_zones}
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                </div>
            </div>
        {/foreach}
        {foreach key=id item=package from=$addon_packages}
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" href="#collapse{$package.addon_id}">
                            {$LANG.DomainManager_service} #{$package.product_id} {$LANG.DomainManager_addon} #{$package.addon_id} {if $package.domain != ""}
                                - {$package.domain}
                            {/if}
                        </a>
                    </h4>
                    <div class="header-actions">
                        <div class="badge badge-success">{$LANG.DomainManager_zone}
                            <span>
                                {$package.domain_count} /
                                {if $package.domain_zone_limit eq -1}
                                    <span>∞</span>
                                {else}
                                    {$package.domain_zone_limit}
                                {/if}
                            </span>
                        </div>
                        <button type="button"
                                style="margin-left: 10px;float: right; margin-top: -2px;margin-bottom: -2px;"
                                class="btn btn-primary btn-sm" data-toggle="modal" data-target="#AddZoneModal"
                                data-limit="{$package.domain_zone_filter}"
                                data-rel-id="{$package.addon_id}"
                                data-rel-type="2"
                        >
                            {$LANG.DomainManager_add_zone}
                        </button>
                    </div>
                </div>
                <div id="collapse{$package.addon_id}" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <ul class="panel-list list-info">
                            {foreach  item=domain from=$package.package_domains}
                                <li>
                                    <a class="mg-ca-zone" href="https://{$domain}">{$domain}</a>
                                    <a href="/?m=DomainManager&api=delete&domain={$domain}"
                                       onClick="return window.confirm('{$LANG.DomainManager_delete_zone_confirm|replace:'%s':$domain|escape}');"
                                       style="float: right;padding-left: 10px;"
                                       title="{$LANG.DomainManager_delete_zone}">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                    <a href="/?m=DomainManager&api=edit&domain={$domain}"
                                       style="float: right;padding-left: 10px;"
                                       title="{$LANG.DomainManager_edit_zone}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </li>
                                {foreachelse}
                                <li>
                                    {$LANG.DomainManager_empty_zones}
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                </div>
            </div>
        {/foreach}
        {foreach key=id item=package from=$domain_packages}
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" href="#collapse{$package.domain_id}">
                            {$LANG.DomainManager_domain} #{$package.domain_id} {if $package.domain != ""}
                                - {$package.domain}
                            {/if}
                        </a>
                    </h4>
                    <div class="header-actions">
                        <div class="badge badge-success">{$LANG.DomainManager_zone}
                            <span>
                                {$package.domain_count} /
                                {if $package.domain_zone_limit eq -1}
                                    <span>∞</span>
                                {else}
                                    {$package.domain_zone_limit}
                                {/if}
                            </span>
                        </div>
                        <button type="button"
                                style="margin-left: 10px;float: right; margin-top: -2px;margin-bottom: -2px;"
                                class="btn btn-primary btn-sm" data-toggle="modal" data-target="#AddZoneModal"
                                data-limit="{$package.domain_zone_filter}"
                                data-rel-id="{$package.domain_id}"
                                data-rel-type="3"
                        >
                            {$LANG.DomainManager_add_zone}
                        </button>
                    </div>
                </div>
                <div id="collapse{$package.domain_id}" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <ul class="panel-list list-info">
                            {foreach  item=domain from=$package.package_domains}
                                <li>
                                    <a class="mg-ca-zone" href="https://{$domain}">{$domain}</a>
                                    <a href="/?m=DomainManager&api=delete&domain={$domain}"
                                       style="float: right;padding-left: 10px;"
                                       onClick="return window.confirm('{$LANG.DomainManager_delete_zone_confirm|replace:'%s':$domain|escape}');"
                                       title="{$LANG.DomainManager_delete_zone}">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                    <a href="/?m=DomainManager&api=edit&domain={$domain}"
                                       style="float: right;padding-left: 10px;"
                                       title="{$LANG.DomainManager_edit_zone}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </li>
                                {foreachelse}
                                <li>
                                    {$LANG.DomainManager_empty_zones}
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                </div>
            </div>
        {/foreach}
    </div>
</div>

<div class="modal fade" id="AddZoneModal" tabindex="-1" role="dialog" aria-labelledby="AddZoneModal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="AddZoneModalLabel">{$LANG.DomainManager_added_zone}</h5>
            </div>
            <form id="form_add_zone" method="POST" action="/?m=DomainManager">
                <input name="rel_id" type="hidden" value="">
                <input name="rel_type" type="hidden" value="">
                <div class="modal-body" style="display: flow-root;">
                    <div class="form-group" style="height: 30px;">
                        <label class="control-label col-sm-3" for="zone_name">{$LANG.DomainManager_domain}</label>
                        <div class="col-sm-7">
                            <input class="form-control" type="text" id="zone_name" name="zone_name" value="" required=""
                                   pattern="(.*\.)+.+">
                        </div>
                    </div>
                    <div class="form-group" style="height: 30px;" id="zone_limit_desk">
                        <label class="control-label col-sm-3" for="zone_limit_desk"></label>
                        <div class="col-sm-7" id="zone_limit" data-text="{$LANG.DomainManager_allow_domain}">
                        </div>
                    </div>
                    <div class="form-group" style="height: 30px;">
                        <label class="control-label col-sm-3" for="create_root_record">{$LANG.DomainManager_create_main}</label>
                        <div class="col-sm-7">
                            <input type="checkbox" id="create_root_record" name="create_root_record" value="1">
                        </div>
                    </div>
                    <div class="form-group" style="height: 30px;">
                        <label class="control-label col-sm-3" for="create_www_record">{$LANG.DomainManager_create_www}</label>
                        <div class="col-sm-7">
                            <input type="checkbox" id="create_www_record" name="create_www_record" value="1">
                        </div>
                    </div>
                    <div id="selected_ip" class="form-group" style="height: 30px;display: none">
                        <label class="control-label col-sm-3" for="ip">{$LANG.DomainManager_ip}</label>
                        <div class="col-sm-7">
                            <select class="form-control" id="ip" name="ip">
                                {foreach from=$ip_list item=$ip}
                                    <option value="{$ip}">{$ip}</option>
                                {/foreach}
                                <option value="other">{$LANG.DomainManager_other_ip_selected}</option>
                            </select>
                        </div>
                    </div>
                    <div id="custom_ip" class="form-group" style="height: 30px; display: none">
                        <label class="control-label col-sm-3" for="custom_ip">{$LANG.DomainManager_other_ip}</label>
                        <div class="col-sm-7">
                            {literal}
                                <input class="form-control" type="text" id="custom_ip" name="custom_ip" value=""
                                       pattern="^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$">
                            {/literal}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{$LANG.DomainManager_close_modal}</button>
                    <input type="submit" class="btn btn-success" value="{$LANG.DomainManager_add_zone_modal}">
                </div>
            </form>
        </div>
    </div>
</div>
