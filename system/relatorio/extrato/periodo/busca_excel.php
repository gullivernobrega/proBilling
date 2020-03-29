<?php

//requisição da config
require('../../../../_app/Config.inc.php');
ini_set('memory_limit', '-1');
// Array com os parametros do Titulo da tabela
$Th = array('Data', 'Origem', 'Destino', 'Tipo', 'Tronco', 'Duração', 'Status');

//filtra os dados
$data = filter_input_array(INPUT_GET, FILTER_DEFAULT);


$excell = new Excellextrato;
if (!empty($data)):

    //Duas datas
    if (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && empty($busca['tipo']) && empty($busca['tronco']) && empty($busca['disposition'])):
        $excell->ExeExcell("cdr", $Th, "ExtratoBuscaPorPeríodo", "WHERE  calldate >= '{$data['di']}' AND calldate <= '{$data['df']}' AND tipo <> '' ORDER BY calldate ASC");

    //Duas datas e o numero    
    elseif (!empty($data['di']) && !empty($data['df']) && !empty($data['num']) && empty($data['tipo']) && empty($busca['tronco']) && empty($data['disposition'])):
        $excell->ExeExcell("cdr", $Th, "ExtratoBuscaPorPeríodo", "WHERE  calldate >= '{$data['di']}' AND calldate <= '{$data['df']}' AND {$data['num']} AND tipo <> '' ORDER BY calldate ASC");

    //Duas datas e o numero e o tipo            
    elseif (!empty($data['di']) && !empty($data['df']) && !empty($data['num']) && !empty($data['tipo']) && empty($busca['tronco']) && empty($data['disposition'])):
        $excell->ExeExcell("cdr", $Th, "ExtratoBuscaPorPeríodo", "WHERE  calldate >= '{$data['di']}' AND calldate <= '{$data['df']}' AND {$data['num']} AND tipo = '{$data['tipo']}' ORDER BY calldate ASC");

    //Duas datas o numero e o disposition      
    elseif (!empty($data['di']) && !empty($data['df']) && !empty($data['num']) && empty($data['tipo']) && empty($busca['tronco']) && !empty($data['disposition'])):
        $excell->ExeExcell("cdr", $Th, "ExtratoBuscaPorPeríodo", "WHERE  calldate >= '{$data['di']}' AND calldate <= '{$data['df']}' AND {$data['num']} AND tipo <> '' AND disposition = '{$data['disposition']}' ORDER BY calldate ASC");

    //Duas datas e todos menos o tronco    
    elseif (!empty($data['di']) && !empty($data['df']) && !empty($data['num']) && !empty($data['tipo']) && empty($busca['tronco']) && !empty($data['disposition'])):
        $excell->ExeExcell("cdr", $Th, "ExtratoBuscaPorPeríodo", "WHERE  calldate >= '{$data['di']}' AND calldate <= '{$data['df']}' AND {$data['num']} AND tipo = '{$data['tipo']}' AND disposition = '{$data['disposition']}' ORDER BY calldate ASC");
    
    //Duas datas e o tronco    
    elseif (!empty($data['di']) && !empty($data['df']) && empty($data['num']) && !empty($data['tipo']) && !empty($data['tronco']) && empty($data['disposition'])):
        $excell->ExeExcell("cdr", $Th, "ExtratoBuscaPorPeríodo", "WHERE  calldate >= '{$data['di']}' AND calldate <= '{$data['df']}' AND tronco = '{$data['tronco']}' ORDER BY calldate ASC");    
     
     //Duas datas o tronco e o disposition    
    elseif (!empty($data['di']) && !empty($data['df']) && empty($data['num']) && empty($data['tipo']) && !empty($data['tronco']) && !empty($data['disposition'])):
        $excell->ExeExcell("cdr", $Th, "ExtratoBuscaPorPeríodo", "WHERE  calldate >= '{$data['di']}' AND calldate <= '{$data['df']}' AND tronco = '{$data['tronco']}' AND disposition = '{$data['disposition']}' ORDER BY calldate ASC");
   
        
    //Duas datas e o tipo    
    elseif (!empty($data['di']) && !empty($data['df']) && empty($data['num']) && !empty($data['tipo']) && empty($data['tronco']) && empty($data['disposition'])):
        $excell->ExeExcell("cdr", $Th, "ExtratoBuscaPorPeríodo", "WHERE  calldate >= '{$data['di']}' AND calldate <= '{$data['df']}' AND tipo = '{$data['tipo']}' ORDER BY calldate ASC");

    //Duas datas o tipo e o disposition    
    elseif (!empty($data['di']) && !empty($data['df']) && empty($data['num']) && !empty($data['tipo']) && empty($data['tronco']) && !empty($data['disposition'])):
        $excell->ExeExcell("cdr", $Th, "ExtratoBuscaPorPeríodo", "WHERE  calldate >= '{$data['di']}' AND calldate <= '{$data['df']}' AND tipo = '{$data['tipo']}' AND disposition = '{$data['disposition']}' ORDER BY calldate ASC");

    //Duas datas e o disposition    
    elseif (!empty($data['di']) && !empty($data['df']) && empty($data['num']) && empty($data['tipo']) && !empty($data['tronco']) && !empty($data['disposition'])):
        $excell->ExeExcell("cdr", $Th, "ExtratoBuscaPorPeríodo", "WHERE  calldate >= '{$data['di']}' AND calldate <= '{$data['df']}' AND disposition = '{$data['disposition']}' ORDER BY calldate ASC");

    else:
    endif;
    
endif;
