window.onload = function () {
    $("#AddZoneModal").on('show.bs.modal', function (e) {
        let limit_zone = $(e.relatedTarget).data('limit').split(",");
        let regex = "(";
        for (let i = 0; i < limit_zone.length; i++) {
            let part1 = limit_zone[i].substring(0, 2);
            let part2 = limit_zone[i].substring(2, limit_zone[i].length);
            regex += (part1 + part2.replace(/\./g, '\\.')).replace('*.', '^.*?\\.');
            if (i + 1 !== limit_zone.length)
                regex += "|";
        }
        regex += ")$";
        console.log("regex->" + regex);

        $("input[name=rel_id]").val($(e.relatedTarget).data('rel-id'));
        $("input[name=rel_type]").val($(e.relatedTarget).data('rel-type'));

        $("#zone_name").attr("pattern", regex);
        if (limit_zone.length > 0 && limit_zone[0] !== '*') {
            console.log("show");
            $("#zone_limit_desk").show();
            $("#zone_limit").text($("#zone_limit").attr("data-text").replace('%s', limit_zone.join(',').replace(/\*/g, '')));
        } else {
            console.log("hidden");
            $("#zone_limit_desk").hide();
        }
    });

    $('#form_add_zone').on('change', ':checkbox', function () {
        if ($("#create_root_record").prop('checked') || $("#create_www_record").prop('checked')) {
            $("#selected_ip").show();
        } else {
            $("#selected_ip").hide();
        }
    });

    $("select[name=ip]").change(function () {
        if ($(this).val() === 'other') {
            $("#custom_ip").show();
        } else {
            $("#custom_ip").hide();
        }
    });
};
