<div class="col-md-12">
    {if $error ne 0}
        <div class="alert  alert-danger" style="margin-top: 10px" role="alert">
            Во время выполнения резервной копии {$error} сервер(а) были недоступны,
            резервная копия была выполнена частично.
        </div>
        <div class="col-md-12 text-center">
            <a href="{$url}" class="btn btn-warning " role="button" aria-disabled="true" download>Скачать частичную
                копию (она рабочая)</a>
        </div>
    {else}
        <div class="col-md-12 text-center" style="margin-top: 30px">
            <a href="{$url}" class="btn btn-success " role="button" aria-disabled="true" download>Скачать резервную</a>
        </div>
    {/if}
</div>