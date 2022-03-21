<fieldset>
    <h3>Работа с резервными копиями</h3>
    <div class="row">
        <div class="col-md-12 text-center">
            <form role="form" method="get" style=" display: inline;">
                <input type="hidden" name="module" value="DomainManager">
                <input type="hidden" name="action" value="restore_backup">
                <input type="submit" class="btn btn-danger" value="Востановить из резервной копии">
            </form>
            <form role="form" method="get" style="display: inline;">
                <input type="hidden" name="module" value="DomainManager">
                <input type="hidden" name="action" value="create_backup">
                <input type="submit" class="btn btn-success" value="Создать резервную копию">
            </form>
        </div>
    </div>
    <hr/>
    <h3>Общие настройки</h3>
    <form role="form" id="settings" method="post" class="label-form">
        <div class="row">
            <div class="col-md-3 text-right title">
                <label for="limit_remote_backup">Лимит удаленных копий:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="number" class="form-control" name="limit_remote_backup" id="limit_remote_backup"
                           value="{$limit_remote_backup}">
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="limit_remote_backup">-1 =
                    неограниченно, более старые будут удалены. (Не работает если лимит локальных установлен в 0)</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="backup_local_limit">Лимит локальных копий:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="number" class="form-control" name="backup_local_limit" id="backup_local_limit"
                           value="{$backup_local_limit}">
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="backup_local_limit">-1 =
                    неограниченно, более старые будут удалены. (Минимум 1 если включена выгрузка)</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="upload_backup">Выгрузка резервных копий:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <select class="form-control" name="upload_backup" id="upload_backup"
                    >
                        <option value="0" {if $upload_backup eq null || $upload_backup eq 0} selected{/if}>Нет</option>
                        <option value="1" {if $upload_backup eq 1} selected{/if}>Да</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="upload_backup">Выгружать ли копии на удаленный сервер</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="compress_backup">Сжатие резервных копий:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <select class="form-control" name="compress_backup" id="compress_backup"
                    >
                        <option value="0" {if $compress_backup eq null || $compress_backup eq 0} selected{/if}>
                            Без
                        </option>
                        <option value="1" {if  $compress_backup eq 1} selected{/if}>
                            gzip
                        </option>
                        <option value="2" {if  $compress_backup eq 2} selected{/if}>
                            bzip2
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="compress_backup">Без сжатия выполняется резервная копия быстрее однако она
                    занимает больше места</label>
            </div>
        </div>
        <hr/>
        <h3>Настройки для выгрузки резервных копий</h3>
        <div class="row">
            <div class="col-md-3 text-right title">
                <label for="server_ip">IP:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="server_ip" id="server_ip" value="{$server_ip}">
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="ftp_ip">IP адрес сервера</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="server_port">Порт:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="number" class="form-control" name="server_port" id="server_port"
                           value="{$server_port}">
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="server_port">Порт сервера</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="server_login">Логин:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="server_login" id="server_login"
                           value="{$server_login}">
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="server_login">Логин для подключения к серверу</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="server_password">Пароль:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="text" class="form-control" name="server_password"
                           id="server_password" value="{$server_password}">
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="server_password">Пароль для подключения к серверу</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="server_type">Тип сервера:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <select class="form-control" name="server_type" id="server_type"
                    >
                        <option value="ftp"
                                {if $server_type eq 'ftp'}
                                    selected
                                {/if}
                        >FTP
                        </option>
                        <option value="sftp"
                                {if $server_type eq 'sftp'}
                                    selected
                                {/if}
                        >SFTP</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="server_type">Выберите тип сервера</label>
            </div>
            <div class="col-md-3 text-right title">
                <label for="server_path">Путь:</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input name="server_path" type="text" class="form-control" id="server_path" value="{$server_path}">
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="server_path">Путь для сохранения резервных копий</label>
            </div>
        </div>
        <div class="row text-center"><br/>
            <input class="btn btn-primary" type="submit" value="Сохранить изменения"/>
        </div>
    </form>
</fieldset>
