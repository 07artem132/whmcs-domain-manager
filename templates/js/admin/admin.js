window.onload = function () {
    var table1 = jQuery("#tableDomainsList").DataTable({
        "ordering": false,
        "dom": '<"listtable"fit>pl',
        "responsive": true,
        "oLanguage": {
            "sEmptyTable": "Записей не найдено",
            "sInfo": "Показано с _START_ по _END_ из _TOTAL_",
            "sInfoEmpty": "Показано с 0 по 0 из 0",
            "sInfoFiltered": "(отфильтровано из _MAX_ записей)",
            "sInfoPostFix": "",
            "sInfoThousands": ",",
            "sLengthMenu": "Показать _MENU_ записей",
            "sLoadingRecords": "Загрузка...",
            "sProcessing": "Обработка...",
            "sSearch": "",
            "sZeroRecords": "Записей не найдено",
            "oPaginate": {
                "sFirst": "Первая",
                "sLast": "Последняя",
                "sNext": "Вперед",
                "sPrevious": "Назад"
            }
        },
        "pageLength": 100,
        "lengthMenu": [
            [50, 100, 500, -1],
            [50, 100, 500, "Все"]
        ], "stateSave": true
    });

    $('#tableDomainsList tr td:nth-child(-n+5) ').click(function () {
        var domain = $.trim($($(this).parent().find('td')[0]).text());
        if (String(window.location).indexOf("index") === -1) {
            window.location = window.location + '&action=record_list&domain=' + domain;

        } else {
            window.location = String(window.location).replace("index", "record_list") + '&domain=' + domain;

        }
    });

    var table2 = jQuery("#tableDomainRecordsList").DataTable({
        "ordering": false,
        "dom": '<"listtable"fit>pl',
        "responsive": true,
        "oLanguage": {
            "sEmptyTable": "Записей не найдено",
            "sInfo": "Показано с _START_ по _END_ из _TOTAL_",
            "sInfoEmpty": "Показано с 0 по 0 из 0",
            "sInfoFiltered": "(отфильтровано из _MAX_ записей)",
            "sInfoPostFix": "",
            "sInfoThousands": ",",
            "sLengthMenu": "Показать _MENU_ записей",
            "sLoadingRecords": "Загрузка...",
            "sProcessing": "Обработка...",
            "sSearch": "",
            "sZeroRecords": "Записей не найдено",
            "oPaginate": {
                "sFirst": "Первая",
                "sLast": "Последняя",
                "sNext": "Вперед",
                "sPrevious": "Назад"
            }
        },
        "pageLength": 100,
        "lengthMenu": [
            [50, 100, 500, -1],
            [50, 100, 500, "Все"]
        ], "stateSave": true
    });

    jQuery(".dataTables_filter input").attr("placeholder", "Условие для поиска...");

};


