<?php

//requisição da config
require('../../../../_app/Config.inc.php');
ini_set('memory_limit', '-1');

// Array com os parametros do Titulo da tabela
$Th = array("Lote", "Campanha", "Data", "Inserido", "Não Entregavel", "Enviado", "Entregue", "Não Entregue", "Total");

//filtra os dados
$busca = filter_input_array(INPUT_GET, FILTER_DEFAULT);

$excell = new ExcellDashboardSms;
if (!empty($busca)):

    //Inicializa as variaveis
    //$data = "";
    $dataRead = "";

    //loop para preparar itens da pesquisa
    foreach ($busca as $k => $v):

        //motagem do data link e search
        //$data .= "{$k}={$v}&";

        //verifico se a key é do fone
        if ($k == "di"):
            $dataRead .= "sms_date >= '{$v}' AND ";
        elseif ($k == "df"):
            $dataRead .= "sms_date <= '{$v}' AND ";
        else:
            $dataRead .= "$k = '$v' AND ";
        endif;

    endforeach;

    //retira o ultimo "&" da linha de pesquisa
    //$data = substr($data, 0, -1);
    $dataRead = substr($dataRead, 0, -4);
    
    $excell->ExeExcell("cdr_sms", $Th, "RelatorioBuscaSms", "WHERE  {$dataRead}");   
    
endif;
