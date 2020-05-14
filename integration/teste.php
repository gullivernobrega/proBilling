<?php
// Recebe o parâtro vi POST
$versao = (isset($_POST['Versao'])) ? $_POST['Versao'] : '';
$Data = (isset($_POST['Data'])) ? $_POST['Data'] : '';
$mac = (isset($_POST['mac'])) ? $_POST['mac'] : '';

$dados = array(
    'Versao' => $versao,
    'Data' => $Data,
    'mac' => $mac
    
);


// Verifica se o parâtro nãestáazio e se o valor égual a 'data'
//if(!empty($parametro) && $parametro == 'data'):
//      $retorno = array('data' => date('d-m-Y'));
        echo json_encode($dados);
//        echo $_POST;
//endif;
