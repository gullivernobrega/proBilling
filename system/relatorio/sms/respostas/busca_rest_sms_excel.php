<?php

//requisição da config
require('../../../../_app/Config.inc.php');
ini_set('memory_limit', '-1');
// Array com os parametros do Titulo da tabela
$Th = array('SMS Cus/Acc', 'Data', 'Origem', 'Resposta');

//filtra os dados
$data = filter_input_array(INPUT_GET, FILTER_DEFAULT);

$excell = new ExcellSmsRest();
if (!empty($data)):

    //Duas datas
    if (!empty($data['di']) && !empty($data['df']) && empty($data['numero'])):
        $excell->ExeExcell("rest_sms", $Th, "SmsBuscaRespostas", "WHERE  data_recebimento >= '{$data['di']}' AND data_recebimento <= '{$data['df']}' AND ORDER BY data_recebimento ASC");
    //Duas datas e o numero    
    elseif (!empty($data['di']) && !empty($data['df']) && !empty($data['numero']) && empty($data['status'])):
        $excell->ExeExcell("rest_sms", $Th, "SmsBuscaRespostas", "WHERE  data_recebimento >= '{$data['di']}' AND data_recebimento <= '{$data['df']}' AND origem = {$data['numero']} ORDER BY data_recebimento ASC");
    else:
    endif;
    
endif;
