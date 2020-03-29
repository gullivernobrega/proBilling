<?php

//requisição da config
require('../../../../_app/Config.inc.php');

// Array com os parametros do Titulo da tabela
$Th = array('Data', 'Número', 'Status');

//filtra os dados
$data = filter_input_array(INPUT_GET, FILTER_DEFAULT);

$excell = new Excellextratorperdida;
if (!empty($data)):

    //Duas datas
    //select para busca
    //SELECT `calldate`, `src`, `disposition` FROM `cdr` WHERE `calldate` >= '2018-08-21 00:00:01' AND `calldate` <= '2018-08-21 23:59:59' AND `disposition` <> 'ANSWER' AND `dcontext` = 'entrada'
    if (!empty($data['di']) && !empty($data['df'])):
        $excell->ExeExcell("cdr", $Th, "ExtratoBuscaRecebidasPerdidas", "WHERE  calldate >= '{$data['di']}' AND calldate <= '{$data['df']}'  AND `disposition` <> 'ANSWERED' AND `dcontext` = 'entrada' ORDER BY calldate ASC");
    else:
    endif;
    
endif;