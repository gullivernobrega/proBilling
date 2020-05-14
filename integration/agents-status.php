<?php
//ini_set('memory_limit', '-1');
//ini_set('max_execution_time', '-1');

require_once 'classes/Conn.class.php';
include "classes/numberAnswer.php";
include "classes/phpagi-asmanager.php";
include "classes/queuesFila.php";


$conn = new Conn();
$asmanager = new AGI_AsteriskManager;
$conectaServidor = $conectaServidor = $asmanager->connect('127.0.0.1:5038', 'proBilling', 'proBilling');
$num = new numberAnswer();
$fila = new queuesFila();
$arrayAgents[0] = array();

while (true) {

    //    Dando TRUNCATE na tabela agents_status
    $Query = 'TRUNCATE agents_status';
    $consulta = $conn->Inserir($Query);

    //    Dando TRUNCATE na tabela agents_status
    $Query = 'TRUNCATE queues_fila';
    $consulta = $conn->Inserir($Query);

    // Alimentando tabela queues_fila
    $queue = $fila->fila($asmanager);

    //   Pegando todos agentes criados no sistema ProBilling
    $Query = "SELECT agent_user FROM agents";
    $consulta = $conn->Consultar($Query);

    //   percorrendo Array de agents e chamando a classe de tratamento para alimentar a 
    //   tabela agents_status
    for ($i = 0; $i < count($consulta); $i++) {
        $agent_user = $consulta[$i]['agent_user'];
        $agents = $num->pegaNumero("$agent_user", $asmanager, $arrayAgents);
        $user = $agents[0];
        
    //Chamando metodo EnviaUrl para analisar Agente que esta em ligação.
    //Enviar URL para CRM de integração.    
        //$enviaUrl = $num->EnviaUrl($user,$agents,$arrayAgents);
        //$arrayAgents = $enviaUrl;
       
    }

    sleep(3);
}
