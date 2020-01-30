<fieldset>
    <br/>
    <h3>Общие настройки</h3>
    <form role="form" id="settings" method="post" class="label-form">
        <div class="row">
            <div class="col-md-3 text-right title">
                <label for="server_name">Имя:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="server_name" id="server_name" value="{$server.name}"
                           required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="server_name">Имя сервера (видно только в панели администратора)</label>
            </div>
            <div class="col-md-3 text-right title" style="height: 100px">
                <label for="ns">ns'ки</label>
            </div>
            <div class="col-md-3" style="height: 100px">
                <div>
                    <textarea class="form-control" style="height: 95px;resize: none;" name="ns" id="ns"
                              required>{"\r\n"|implode:$server.ns_list}</textarea>
                </div>
            </div>
            <div class="col-md-6" style="height: 100px">
                <label class="info_text" for="ns">По одному ns на строку</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="ip">ip:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="ip" id="ip" value="{$server.ip}" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="ip">IP для api запросов</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="token">Токен</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="token" id="token" value="{$server.token}" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="token">api-key значение в конфиге</label>
            </div>
        </div>
        <div class="row text-center"><br/>
            <input class="btn btn-success" type="submit" value="Сохранить изменения"/>
        </div>
    </form>
</fieldset>
