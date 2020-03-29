jQuery.datetimepicker.setLocale('pt-BR');
jQuery(document).ready(function () {
//    'use strict';

    //pesquisa extrato
    jQuery('#datetimeIni, #datetimeFim').datetimepicker({
//        format: 'Y-m-d H:i:s'
        'timeFormat': 'H:i:s'
    });

    //pesquisa campanha
    jQuery('#campanha_data_inicio, #campanha_data_fim').datetimepicker({
//        format:'Y-m-d H:i:s'
        timeFormat: 'H:i:s'
    });
});

