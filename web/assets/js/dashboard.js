$(function() {
    setInterval(
        function() {
            $('.server-status').each(function() {
                var that = $(this);
                $.ajax({
                    url: "/servers/ping/" + $(this).attr('id'),
                    success: function(data) {
                        if (data.status == 'ok') {
                            $(that).removeClass('text-danger');
                            $(that).addClass('text-success');
                            $(that).html('<i class="fa fa-circle"></i> Online');
                        } else {
                            $(that).removeClass('text-success');
                            $(that).addClass('text-danger');
                            $(that).html('<i class="fa fa-circle"></i> Offline');
                        }
                    }
                });
            })
        },
        30000
    );

    $("#busqueda").keyup(function() {
        var value = this.value;

        $("table tbody").find("tr").each(function() {
            $(this).find("td").each(function() {
                var contenido = $(this).text();
                if (contenido.indexOf(value) >= 0) {
                    $(this).parent().removeAttr('hidden');
                    return false;
                } else {
                    $(this).parent().attr('hidden', 'hidden');
                }
            });
        });
    });

    $.validator.addMethod('IP4Checker', function(value) {
        var ip = /^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/gm;
        return value.match(ip);
    }, 'Dirección IP inválida.');

    $('#modalForm').validate({
        rules: {
            ip: {
                required: true,
                IP4Checker: true
            },
            port: {
                digits: true
            }
        }
    });

    jQuery.extend(jQuery.validator.messages, {
        required: "Este campo es obligatorio.",
        digits: "Introduce un número entero válido.",
        number: "Introduce un número entero válido.",
        min: "Introduce un número mayor o igual a 1."
    });

    $('#deleteServer').on('show.bs.modal', function(e) {
        var serverId = $(e.relatedTarget).data('server-id');
        $(e.currentTarget).find('#serverId').val(serverId);
    });
});