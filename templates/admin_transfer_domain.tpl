<fieldset>
    <br/>
    <h3>Общие настройки</h3>
    <form role="form" id="settings" method="post" class="label-form">
        <div class="row">
            <div class="col-md-3 text-right title">
                <label for="domain">Домен:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="domain" id="domain" value="{$domain}" readonly
                           required>
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
                    <input type="text" class="form-control" name="relid" id="relid" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="relid">Введите id продукта или дополнения</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="type">Тип связи</label>
            </div>
            <div class="col-md-3">
                <div>
                    <select class="form-control" name="type" id="type" required>
                        <option disabled selected value="">Не выбрано</option>
                        <option value="1">Продукт</option>
                        <option value="2">Дополнение</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="type">Выберите с чем ассоциировать домен</label>
            </div>

        </div>
        <div class="row text-center"><br/>
            <input class="btn btn-warning" type="submit" value="Выполнить трансфер"/>
        </div>
    </form>
</fieldset>
