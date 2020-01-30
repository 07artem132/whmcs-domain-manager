<fieldset>
    <h3>Общие настройки</h3>
    <form role="form" id="settings" method="post" class="label-form">
        <div class="row">
            <div class="col-md-3 text-right title">
                <label for="domain">Домен:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="domain" id="domain" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="domain">Введите имя домена</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="relid">Введите id Продукта/Дополнения:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="relid" id="relid">
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="relid">Введите id продукта или дополнения (опционально)</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="type">Тип связи</label>
            </div>
            <div class="col-md-3">
                <div>
                    <select class="form-control" name="type" id="type">
                        <option disabled selected value="">Не выбрано</option>
                        <option value="1">Продукт</option>
                        <option value="2">Дополнение</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="type">Выберите с чем ассоциировать домен (опционально)</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="server">Сервер:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <select class="form-control" name="server" id="server" required>
                        <option disabled selected value="">Не выбрано</option>
                        {foreach item=$server from=$servers}
                            <option value="{$server.id}">{$server.name}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="server">Сервер на котором будут создаваться зоны</label>
            </div>
        </div>
        <div class="row text-center"><br/>
            <input class="btn btn-success" type="submit" value="Добавить сервер"/>
        </div>
    </form>
</fieldset>
