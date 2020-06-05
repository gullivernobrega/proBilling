#!/usr/bin/php -q
<?php
/*
 * Arquivo criado por @GulliverNóbrega
 * Recebimento de chamadas pelo sistema ProBilling / Receiving Calls by ProBilling System
 * e-mail: gulliver@brazistelecom.com.br
 */

if (function_exists('pcntl_signal')) {
    pcntl_signal(SIGHUP, SIG_IGN);
}

spl_autoload_register(function ($class_name) {
    require 'classes/' . $class_name . '.class.php';
});

//Instanciando Classes
$conn = new Conn();
$cdr = new Cdr();
$did = new did_call();

//Instanciando a Classe do asterisk
$agi = new AGI();
$unique = $agi->request['agi_uniqueid'];
//Varíavel para pegar o numero de entrada para distribuir no contexto [entrada]
$numeroEntrada = $agi->request['agi_extension'];
//Varíavel que pega o numero que ligou. CALLERID.
$numeroArquivo = $agi->request['agi_callerid'];
//Varíavel para pegar o tronco recebido
$tronco = $agi->request['agi_channel'];
$tronco = explode('/', $tronco);
$tronco = $tronco[1];
$tronco = explode('-', $tronco);
$tronco = $tronco[0];
//Pegando ha hora atual é data atual
$diaSemana = date('N');
$horaAtual = date('H:i:s');
$data = date('Y-m-d_H:i:s');


//Query para armazenar rotas cadastradas no sistema:
$Query = "SELECT * FROM `did`";
$dados = $conn->Consultar($Query);

foreach ($dados as $dado) {

    if ($numeroEntrada == $dado['did_origem'] && $diaSemana >= 1 && $diaSemana <= 5 && $horaAtual >= $dado['did_hora_ss_ini'] && $horaAtual <= $dado['did_hora_ss_fim']) {
        $did->exeDID($agi, $conn, $cdr, $unique, $numeroArquivo, $tronco, $data, $dado);
    } elseif ($numeroEntrada == $dado['did_origem'] && $diaSemana == 6 && $horaAtual >= $dado['did_hora_s_ini'] && $horaAtual <= $dado['did_hora_s_fim']) {
        $did->exeDID($agi, $conn, $cdr, $unique, $numeroArquivo, $tronco, $data, $dado);
    } elseif ($numeroEntrada == $dado['did_origem'] && $diaSemana == 7 && $horaAtual >= $dado['did_hora_d_ini'] && $horaAtual <= $dado['did_hora_d_fim']) {
        $did->exeDID($agi, $conn, $cdr, $unique, $numeroArquivo, $tronco, $data, $dado);
    } elseif ($numeroEntrada == $dado['did_origem']) {
        $agi->exec("Playback", "/var/www/html/proBilling/arquivos/horariodeatendimento");
    
}
}



