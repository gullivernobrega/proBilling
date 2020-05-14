<?php

//ini_set('memory_limit', '-1');
//ini_set('max_execution_time', '-1');

require_once 'classes/Conn.class.php';


$conn = new Conn();

while (true) {

    $Query = "SELECT agent_user, agent_pause, agent_pause_date FROM agents WHERE agent_pause = 'Evento' AND agent_pause_date <> '0000-00-00 00:00:00'";
    $conn->Consultar($Query);
    $dados = $conn->consultaRetorno;

    foreach ($dados as $dado) {
        //agent_pause_date
        //função para pegar diferença de data da pause e data atual
        $date1 = new DateTime($dado['agent_pause_date']);
        $date2 = new DateTime("now");
        $diff = date_diff($date2, $date1);
        $result = $diff->format("%H:%I:%S");

        if ($result > '00:00:20') {
            
            shell_exec("sudo asterisk -rx 'queue unpause member Local/{$dado['agent_user']}@agents'");
            $Query = "UPDATE agents SET agent_pause = '' WHERE agent_user = '{$dado['agent_user']}'";
            $conn->Inserir($Query);
            
        }
    }
}