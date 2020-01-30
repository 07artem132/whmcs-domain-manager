<div class="col-md-12">
    {if $error ne 0}
        <div class="alert  alert-danger" style="margin-top: 10px" role="alert">
            Во время восстановления резервной копии {$error} сервер(а) были недоступны,
            резервная копия была восстановлена частично.
        </div>
        <div class="col-md-12 text-center">
            <a href="addonmodules.php?module=DomainManager&action=servers" class="btn btn-warning " role="button"
               aria-disabled="true">Перейти к списку серверов</a>
        </div>
    {else}
        <div class="col-md-12 text-center" style="margin-top: 30px">
            <a href="addonmodules.php?module=DomainManager&action=domain" class="btn btn-primary " role="button"
               aria-disabled="true">Перейти к списку доменов</a>
        </div>
    {/if}
</div>