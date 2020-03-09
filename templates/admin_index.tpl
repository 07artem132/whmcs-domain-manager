{if !empty($server_error)}
    {foreach from=$server_error item=message key=name}
        <div class="alert  alert-danger" style="margin-top: 10px" role="alert">
            Сервер "{$name}" не доступен, ошибка: {$message}
        </div>
    {/foreach}
{/if}
{if !empty($domain_error)}
    {foreach from=$domain_error item=message key=name}
        <div class="alert  alert-danger" style="margin-top: 10px" role="alert">
            При работе с доменом "{$name}" возникла ошибка: {$message}, статистика не точная!
        </div>
    {/foreach}
{/if}
<div class="col-md-12">
    {if $request_server eq false}
        <div style="display: -webkit-box;">
            <div class="col-sm-4" style="margin-top:20px;">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Зоны на серверах (Всего: {$stats.domain_total})</h3>
                    </div>
                    <div class="panel-body" style="padding: 5px;">
                        <canvas id="chartjs-1" class="chartjs" width="962" height="481"
                                style="display: block; height: 385px; width: 770px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-sm-4" style="margin-top:20px;">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Размешенные зоны доменов</h3>
                    </div>
                    <div class="panel-body" style="padding: 5px;">
                        <canvas id="chartjs-2" class="chartjs" width="962" height="481"
                                style="display: block; height: 385px; width: 770px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-sm-4" style="margin-top:20px;">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Заказанные пакеты (Всего: {$stats.client_package_total})</h3>
                    </div>
                    <div class="panel-body" style="padding: 5px;">
                        {if $stats.client_package_total!==0}
                            <canvas id="chartjs-3" class="chartjs" width="962" height="481"
                                    style="display: block; height: 385px; width: 770px;"></canvas>
                        {else}
                            <div style="text-align: center;vertical-align: middle;">
                                Недостаточно данных
                            </div>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4" style="margin-top:20px;">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Статистика по записям (Всего {$stats.records_total})</h3>
                </div>
                <div class="panel-body" style="padding: 5px;">
                    <canvas id="chartjs-4" class="chartjs" width="962" height="481"
                            style="display: block; height: 385px; width: 770px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-sm-4" style="margin-top:20px;">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Использование пакетов </h3>
                </div>
                <div class="panel-body" style="padding: 5px;">
                    {if array_sum($stats.client_package_use.data) !==0}
                        <canvas id="chartjs-5" class="chartjs" width="962" height="481"
                                style="display: block; height: 385px; width: 770px;"></canvas>
                    {else}
                        <div style="text-align: center;vertical-align: middle;">
                            Недостаточно данных
                        </div>
                    {/if}
                </div>
            </div>
        </div>
        <div class="col-sm-4" style="margin-top:20px;">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Резервные копии </h3>
                </div>
                <div class="panel-body" style="padding: 5px;">
                    <div class="widget-content-padded">
                        <div>
        <span>
            <i class="{$icon}" style="color: {$color}"></i>
          <span>{$text}</span>
        </span><br/>
                            <span>
            <i class="{$icon3}" style="color: {$color3}"></i>
          <span style="height: 32px;display: table-cell;vertical-align: middle;">{$text3}</span>
        </span><br/>
                            <span>
          <span style="padding-top: 2%">{$text4}</span>
        </span><br/>
                        </div>

                    </div>
                    <div style="border-top: 1px solid #eee; padding-top: 1%; padding-left: 2%; padding-bottom: 1%;">
                        {if empty($diffLastRunHours)}
                            Резервная копия ещё не выполнялась ни разу!
                        {else}
                            Последний запуск был {$diffLastRunHours} часа(ов) назад
                        {/if}
                    </div>

                </div>
            </div>
        </div>
    {else}
        <div class="alert  alert-warning" style="margin-top: 10px" role="alert">
            Для отображения статистики необходим минимум один активный сервер
        </div>
    {/if}
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.css"/>
<script>
    window.chartColors = {
        red: 'rgb(255, 99, 132)',
        orange: 'rgb(255, 159, 64)',
        yellow: 'rgb(255, 205, 86)',
        green: 'rgb(75, 192, 192)',
        blue: 'rgb(54, 162, 235)',
        purple: 'rgb(153, 102, 255)',
        grey: 'rgb(201, 203, 207)',
        add1: '#AEA3FF',
        add2: '#95A1E8',
        add3: '#B0D1FF',
        add4: '#95CDE8',
        add5: '#A3FAFF',
        add6: '#FFCFC9'

    };

    const chart1 = new Chart(document.getElementById('chartjs-1').getContext('2d'), {
        type: 'pie',
        data: {
            datasets: [
                {
                    data: [
                        {foreach  from=$stats.domain_server.data item=data}
                        '{$data}',
                        {/foreach }
                    ],
                    backgroundColor: [
                        window.chartColors.red,
                        window.chartColors.orange,
                        window.chartColors.yellow,
                        window.chartColors.green,
                        window.chartColors.blue,
                    ],
                }
            ],
            labels: [
                {foreach  from=$stats.domain_server.label item=label}
                '{$label}',
                {/foreach }
            ]
        },
        options: {
            title: {
                display: false,
                text: ''
            }
        }
    });

    const chart2 = new Chart(document.getElementById('chartjs-2').getContext('2d'), {
        type: 'pie',
        data: {
            datasets: [
                {
                    data: [
                        {foreach  from=$stats.zone_top.data item=data}
                        '{$data}',
                        {/foreach }
                    ],
                    backgroundColor: [
                        window.chartColors.red,
                        window.chartColors.orange,
                        window.chartColors.yellow,
                        window.chartColors.green,
                        window.chartColors.blue,
                    ],
                }
            ],
            labels: [
                {foreach  from=$stats.zone_top.label item=label}
                '{$label}',
                {/foreach }
            ]
        },
        options: {
            title: {
                display: false,
                text: ''
            }
        }
    });

    {if $stats.client_package_total!==0}
    const chart3 = new Chart(document.getElementById('chartjs-3').getContext('2d'), {
        type: 'pie',
        data: {
            datasets: [
                {
                    data: [
                        {foreach  from=$stats.package_top.data item=data}
                        '{$data}',
                        {/foreach }
                    ],
                    backgroundColor: [
                        window.chartColors.red,
                        window.chartColors.orange,
                        window.chartColors.yellow,
                        window.chartColors.green,
                        window.chartColors.blue,
                    ],
                }
            ],
            labels: [
                {foreach  from=$stats.package_top.label item=label}
                '{$label}',
                {/foreach }
            ]
        },
        options: {
            title: {
                display: false,
                text: ''
            }
        }
    });
    {/if}

    const chart4 = new Chart(document.getElementById('chartjs-4').getContext('2d'), {
        type: 'pie',
        data: {
            datasets: [
                {
                    data: [
                        {foreach  from=$stats.stats_record_type.data item=data}
                        '{$data}',
                        {/foreach }
                    ],
                    backgroundColor: [
                        window.chartColors.red,
                        window.chartColors.orange,
                        window.chartColors.yellow,
                        window.chartColors.green,
                        window.chartColors.blue,
                        window.chartColors.add1,
                        window.chartColors.add2,
                        window.chartColors.add3,
                        window.chartColors.add4,
                        window.chartColors.add5,
                        window.chartColors.add6
                    ],
                }
            ],
            labels: [
                {foreach  from=$stats.stats_record_type.label item=label}
                '{$label}',
                {/foreach }
            ]
        },
        options: {
            title: {
                display: false,
                text: ''
            }
        }
    });
    {if array_sum($stats.client_package_use.data) !==0}
    const chart5 = new Chart(document.getElementById('chartjs-5').getContext('2d'), {
        type: 'pie',
        data: {
            datasets: [
                {
                    data: [
                        {foreach  from=$stats.client_package_use.data item=data}
                        '{$data}',
                        {/foreach }
                    ],
                    backgroundColor: [
                        window.chartColors.red,
                        window.chartColors.orange,
                        window.chartColors.yellow,
                        window.chartColors.green,
                        window.chartColors.blue,
                        window.chartColors.add1,
                        window.chartColors.add2,
                        window.chartColors.add3,
                        window.chartColors.add4,
                        window.chartColors.add5,
                        window.chartColors.add6
                    ],
                }
            ],
            labels: [
                {foreach  from=$stats.client_package_use.label item=label}
                '{$label}',
                {/foreach }
            ]
        },
        options: {
            title: {
                display: false,
                text: ''
            }
        }
    });
    {/if}
</script>