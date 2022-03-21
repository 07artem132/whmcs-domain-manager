<div class="col-md-12">
    <form enctype="multipart/form-data"  method="post">
        <div class="row" style="padding-top: 20px">
            <div class="col-md-3 text-right title">
                <label for="backup">Выберите файл резервной копии:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="file" name="backup" id="backup" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="backup">Тип резервной копии определится автоматически (полная или частичная)</label>
            </div>
        </div>
        <div class="row text-center"><br/>
            <input class="btn btn-danger" type="submit" value="Восстановить из резервной копии"/>
        </div>
    </form>

</div>