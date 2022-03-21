<fieldset>
    <br/>
    <h3>Общие настройки</h3>
    <form role="form" id="settings" method="post" class="label-form">
        <input type="hidden" name="server_id" value="{$smarty.get.server_id}">
        <input type="hidden" name="domain" value="{$smarty.get.domain}">
        <div class="row">
            <div class="col-md-3 text-right title">
                <label for="record_name">Имя:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="record_name" id="record_name" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="record_name">Имя записи</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="record_type">Тип:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <select class="form-control" name="type" id="record_type" required >
                        <option disabled selected value="">Не выбрано</option>
                        <option value="txt">TXT</option>
                        <option value="ns">NS</option>
                        <option value="dname">DNAME</option>
                        <option value="srv">SRV</option>
                        <option value="mx">MX</option>
                        <option value="cname">CNAME</option>
                        <option value="ptr">PTR</option>
                        <option value="ds">DS</option>
                        <option value="aaaa">AAAA</option>
                        <option value="caa">CAA</option>
                        <option value="a">A</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="record_type">Тип записи</label>
            </div> <div class="col-md-3 text-right title" style="height: 100px">
                <label for="record">Запись</label>
            </div>
            <div class="col-md-3" style="height: 100px">
                <div>
                    <textarea class="form-control" style="height: 95px;resize: none;" name="record" id="record" required></textarea>
                </div>
            </div>
            <div class="col-md-6" style="height: 100px">
                <label class="info_text" for="record">По одной записи на строку</label>
            </div>

            <div class="col-md-3 text-right title">
                <label for="record_ttl">TTL</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="record_ttl" id="record_ttl" value="60" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="record_ttl">Время актуальности данных при кешировании</label>
            </div>
        </div>
        <div class="row text-center"><br/>
            <input class="btn btn-success" type="submit" value="Добавить запись"/>
        </div>
    </form>
</fieldset>
