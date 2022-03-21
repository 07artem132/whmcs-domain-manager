<div class="col-md-12">
    <form class="label-form" method="post">
        <div class="row" style="padding-top: 20px">

            <div class="col-md-3 text-right title">
                <label for="domain">Введите запрещенный домен</label>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <input type="text" class="form-control" name="domain" id="domain" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="backup">Укажите *.domain.zone дабы включить все суб домены домена,
                    domain.zone дабы внести домен или *.zone для добавления зоны в черный список</label>
            </div>
        </div>
        <div class="row text-center"><br/>
            <input class="btn btn-danger" type="submit" value="Добавить в черный список"/>
        </div>
    </form>

</div>