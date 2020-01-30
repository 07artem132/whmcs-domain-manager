{if !empty($smarty.get.error)}
    <div class="alert  alert-danger" style="margin-top: 10px" role="alert">{$smarty.get.error}</div>
{/if}
<fieldset>
    <h3>Общие настройки</h3>
    <form role="form" id="settings" method="post" class="label-form">
        <div class="row">
            <div class="col-md-3 text-right title">
                <label for="package_name">Название:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="package_name" id="package_name"
                           value="{$package.title}" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="backup_limit">Название пакета (видно только в админ панели)</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="rel_id">Связанный продукт/дополнение</label>
            </div>
            <div class="col-md-3">
                <div>
                    <select class="form-control" name="rel_id" id="rel_id" required>
                        {foreach item=$associate from=$associateList}
                            <option value="{$associate.id}" {if $package.rel_id === $associate.id} selected{/if}>
                                {$associate.text}
                            </option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="rel_id">Выберите продукт/дополнение для которого будет применен
                    пакет</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="zone_filter">Ограничение зоны:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="zone_filter" id="zone_filter"
                           value="{$package.domain_zone_filter}" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="zone_filter">"*" - не ограничено, "*.com" - только com зона,
                    "*.com,*.ru" - только com и ru зона</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="server">Сервер:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <select class="form-control" name="server" id="server" required>
                        {foreach item=$server from=$servers}
                            <option value="{$server.id}" {if $package.server_id == $server.id} selected{/if}>{$server.name}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="server">Сервер на котором будут создаваться зоны</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="zone_limit">Лимит зон</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="number" class="form-control" name="zone_limit" id="zone_limit"
                           value="{$package.domain_zone_limit}" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="zone_limit">"-1" - не ограничено</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="record_limit">Лимит записей</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="number" class="form-control" name="record_limit" id="record_limit"
                           value="{$package.record_total_limit}" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="record_limit">"-1" - не ограничено</label>
            </div>
        </div>
        <hr/>
        <h3>Настройки ограничений для записей</h3>
        <div class="row">
            <div class="col-md-3 text-right title">
                <label for="TXT">TXT:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="TXT" id="TXT" value="{$package.TXT}" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="TXT">"-1" - не ограничено</label>
            </div>

            <div class="col-md-3 text-right title">
                <label for="NS">NS:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="NS" id="NS" value="{$package.NS}" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="NS">"-1" - не ограничено</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="DNAME">DNAME:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="DNAME" id="DNAME" value="{$package.DNAME}" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="DNAME">"-1" - не ограничено</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="SRV">SRV:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="SRV" id="SRV" value="{$package.SRV}" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="SRV">"-1" - не ограничено</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="MX">MX:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="MX" id="MX" value="{$package.MX}" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="MX">"-1" - не ограничено</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="CNAME">CNAME:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="CNAME" id="CNAME" value="{$package.CNAME}" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="CNAME">"-1" - не ограничено</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="PTR">PTR:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="PTR" id="PTR" value="{$package.PTR}" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="PTR">"-1" - не ограничено</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="DS">DS:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="DS" id="DS" value="{$package.DS}" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="DS">"-1" - не ограничено</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="AAAA">AAAA:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="AAAA" id="AAAA" value="{$package.AAAA}" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="AAAA">"-1" - не ограничено</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="CAA">CAA:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="CAA" id="CAA" value="{$package.CAA}" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="CAA">"-1" - не ограничено</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="A">A:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="A" id="A" value="{$package.A}" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="A">"-1" - не ограничено</label>
            </div>

        </div>
        <div class="row text-center"><br/>
            <input class="btn btn-primary" type="submit" value="Сохранить изменения"/>
        </div>
    </form>
</fieldset>
