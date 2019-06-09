<form role="form" id="nameserver" class="label-form">
    <fieldset>
        <input type="hidden" name="sdns_form" value="nameserver">
        <br/>
        <h3>
            <input class="btn btn-sm btn-default" type="button" onClick="syscheck();"
                   value="Проверить доступность мастер сервера"/>
        </h3>
        <div class="row">
            <div class="col-md-3 text-right title">
                <label for="sdns_db_host">
                    URL:
                </label>
            </div>
            <div class="col-md-3">
                <div id="sdns_db_host_field">
                    <input type="text" class="form-padding form-control" name="sdns_db_host" id="sdns_db_host"
                           placeholder="http://127.0.0.1:8081/api/v1/servers/localhost/">
                </div>
            </div>
            <div class="col-md-6 title">
                <label class="info_text" for="sdns_db_host"></label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 text-right title">
                <label for="sdns_db_port">
                    KEY:
                </label>
            </div>
            <div class="col-md-3">
                <div id="sdns_db_port_field">
                    <input type="text" class="form-padding form-control" name="sdns_db_port" id="sdns_db_port"
                           placeholder="">
                </div>
            </div>
            <div class="col-md-6 title">
                <label class="info_text" for="sdns_db_port">
                    Значение api-key из конфигурационного файла
                </label>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-3 text-right title">
                <label for="sdns_ns0">
                    Nameserver 1:
                </label>
            </div>
            <div class="col-md-3">
                <div id="sdns_ns0_field">
                    <input type="text" class="form-padding form-control" name="sdns_ns0" id="sdns_ns0" value="">
                </div>
            </div>
            <div class="col-md-6 title">
                <label class="info_text" for="sdns_ns0">
                    Это поле обязательно к заполнению
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 text-right title">
                <label for="sdns_ns1">
                    Nameserver 2:
                </label>
            </div>
            <div class="col-md-3">
                <div id="sdns_ns1_field">
                    <input type="text" class="form-padding form-control" name="sdns_ns1" id="sdns_ns1" value="">
                </div>
            </div>
            <div class="col-md-6"></div>
        </div>
        <div class="row">
            <div class="col-md-3 text-right title">
                <label for="sdns_ns2">
                    Nameserver 3:
                </label>
            </div>
            <div class="col-md-3">
                <div id="sdns_ns2_field">
                    <input type="text" class="form-padding form-control" name="sdns_ns2" id="sdns_ns2" value="">
                </div>
            </div>
            <div class="col-md-6"></div>
        </div>
        <div class="row">
            <div class="col-md-3 text-right title">
                <label for="sdns_ns3">
                    Nameserver 4:
                </label>
            </div>
            <div class="col-md-3">
                <div id="sdns_ns3_field">
                    <input type="text" class="form-padding form-control" name="sdns_ns3" id="sdns_ns3" value="">
                </div>
            </div>
            <div class="col-md-6"></div>
        </div>
        <div class="row">
            <div class="col-md-3 text-right title">
                <label for="sdns_ns4">
                    Nameserver 5:
                </label>
            </div>
            <div class="col-md-3">
                <div id="sdns_ns4_field">
                    <input type="text" class="form-padding form-control" name="sdns_ns4" id="sdns_ns4" value="">
                </div>
            </div>
            <div class="col-md-6"></div>
        </div>
        <div class="row">
            <div class="col-md-3 text-right title">
                <label for="sdns_ns5">
                    Nameserver 6:
                </label>
            </div>
            <div class="col-md-3">
                <div id="sdns_ns5_field">
                    <input type="text" class="form-padding form-control" name="sdns_ns5" id="sdns_ns5" value="">
                </div>
            </div>
            <div class="col-md-6 title">
                <label class="info_text" for="sdns_ns5">
                    Если используется меньше 6 NS серверов просто оставьте некоторые пустыми
                </label>
            </div>
        </div>
        <div class="row text-center">
            <br/>
            <input class="btn btn-primary" type="button" onClick="window.updateSettings('nameserver');"
                   value="Сохранить изменения"/>
        </div>
    </fieldset>
</form>