<div class="col-md-12">
    {if is_int($error) and $error ne 0}
        <div class="alert  alert-danger" style="margin-top: 10px" role="alert">
            Во время восстановления резервной копии {$error} сервер(а) были недоступны,
            резервная копия была восстановлена частично.
        </div>
        <div class="col-md-12 text-center">
            <a href="addonmodules.php?module=DomainManager&action=servers" class="btn btn-warning " role="button"
               aria-disabled="true">Перейти к списку серверов</a>
        </div>
    {elseif !empty($error)}
        <div class="alert  alert-danger" style="margin-top: 10px" role="alert">
            {$error}
        </div>
        <div class="col-md-12 text-center">
            <a href="addonmodules.php?module=DomainManager&action=servers" class="btn btn-warning " role="button"
               aria-disabled="true">Перейти к списку серверов</a>
        </div>
    {else}
        <div class="alert  alert-success" style="margin-top: 10px" role="alert">
            Резервная копия была восстановлена в полном обьеме.
        </div>
        <div class="col-md-12 text-center" style="margin-top: 30px">
            <a href="addonmodules.php?module=DomainManager&action=domain" class="btn btn-primary " role="button"
               aria-disabled="true">Перейти к списку доменов</a>
        </div>
    {/if}
</div>