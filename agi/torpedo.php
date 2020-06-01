#!/usr/bin/php -q
<?php
if (function_exists('pcntl_signal')) {
    pcntl_signal(SIGHUP, SIG_IGN);
}

spl_autoload_register(function ($class_name) {
    require 'classes/' . $class_name . '.class.php';
});

$agi = new AGI();
$unique = $agi->request['agi_uniqueid'];
$numeroDestino = $agi->get_variable("CALLED", true);
$tipoCall = $agi->get_variable("TIPOCALL", true);
$tronco = $agi->get_variable("TRONCO", true);
$audio1 = $agi->get_variable("AUDIO1", true);
$audio2 = $agi->get_variable("AUDIO2", true);
$nome = $agi->get_variable("NOME", true);
$cpf = $agi->get_variable("CPF", true);
$agendaID = $agi->get_variable("AGENDAID", true);
$numeroID = $agi->get_variable("NUMEROID", true);
$destiTrans = $agi->get_variable("DESTITRANS", true);
$campanhaAmd = $agi->get_variable("CAMPANHAAMD", true);

//Tratando Número Destino.
$numeroDestino = ($tipoCall == 'Brasil-Movel') ? $numeroDestino = substr($numeroDestino, -12) : substr($numeroDestino, -11);

$DestAten = explode("/", $destiTrans);

$data = date('Y-m-d_H:i:s');
$dataPasta = date('d-m-Y');

$conn = new Conn();
$cdrInsert = new Cdr();
$Query = "UPDATE `numero` SET `numero_status`='E' WHERE `numero_id` = '$numeroID'";
$conn->Inserir($Query);

        
if ($campanhaAmd == 1) {
      $agi->exec("Wait", "1");
      $agi->exec("Playback", "/var/www/html/proBilling/arquivos/aaalo");
      $agi->exec("AMD", "");
      $amdStatus = $agi->get_variable("AMDSTATUS", true);
    
} elseif ($campanhaAmd == 0) {
    $amdStatus = 'HUMAN';
}
if ($amdStatus == 'MACHINE') {
    $agi->exec("NOOP", "Caixa\ Postal\ Detectada\ Brasil-Movel:\ $numeroDestino ");
    $agi->exec("Hangup", "");
    exit();
} else {

//    if (strlen($numeroDestino) == 9 || strlen($numeroDestino) == 14) {
    if (strlen($numeroDestino) == 12) {
        if (!empty($nome && $audio2)) {

            $tts = new tts();  
            $tts->Exectts($nome);
            $nome = str_replace(" ", "-", $nome);
            $pasta = "/var/www/html/proBilling/sounds/nome-$nome.mp3";
//            file_put_contents($pasta, file_get_contents($url));
            file_put_contents($pasta, $tts->getResult());
            $nome = "/var/www/html/proBilling/sounds/nome-$nome";
            shell_exec("lame --decode $nome.mp3 - | sox -v 2.0 -t wav - -t wav -b 16 -r 8000 -c 1 $nome.wav");
            shell_exec("rm -rf $nome.mp3");
        }

        $agi->verbose(print_r($agi->request, true));
        $agi->exec("NOOP", "Torpedo\ de\ Voz\ Brasil-Movel:\ $numeroDestino ");
        $agi->exec("NOOP", "Data\ da\ ligacao\ $data ");
        $uniqueidTratado = explode('.', $unique);
        $agi->exec("set", "CDR(userfield)={$unique}_{$data}_{$numeroDestino}");
        $agi->exec("set", "CDR(tipo)=Brasil-Movel");
        $arquivoGravacao = "/var/spool/asterisk/monitor/$dataPasta/$unique" . '_' . "$data" . '_' . "$numeroDestino" . '.wav';
        $agi->exec("set", "__TRANSFBRAZIS=$arquivoGravacao");
        $agi->exec("Playback", "$audio1");
        if (!empty($nome)) {
            $agi->exec("Playback", "$nome");
        }
        if (!empty($audio2)) {
            $agi->exec("Playback", "$audio2");
        }
        $agi->exec("MixMonitor", "$arquivoGravacao,b");

        if ($DestAten[0] == "QUEUE") {
//            $agi->exec("Queue", "$DestAten[1],,,,,/var/www/html/proBilling/agi/queue.php");
//            $agi->exec("Queue", "$DestAten[1],,,,300");
            $agi->exec("Answer", "");
            $tempoIni = new DateTime("now");
            $tempoIni = $tempoIni->format("Y-m-d H:i:s");
            $tempoIni = strtotime($tempoIni);
            $agi->exec("Queue", "$DestAten[1],,,,10");
            $tempoFim = new DateTime("now");
            $tempoFim = $tempoFim->format("Y-m-d H:i:s");
            $tempoFim = strtotime($tempoFim);
            $tempoFalado = $tempoFim - $tempoIni;
            $agi->exec("NOOP", "Tempo:$tempoFalado");

            /*
             * Codigo de teste para Gravar Evento
             */

            if ($tempoFalado > '5') {

                $Query = "SELECT queue_tipo FROM queues WHERE queue_name = '{$DestAten[1]}'";
                $dadosQueue = $conn->Consultar($Query);;
                $queueTipo = $dadosQueue[0]['queue_tipo'];
                
                if ($queueTipo == 'A'){
                    $agent = $agi->get_variable("CDR(dstchannel)");
                    $agent = $agent['data'];
                    $agent2 = explode("/", $agent);
                    $agent = $agent2[1];
                    $agent2 = explode("@", $agent);
                    $agent3 = $agent2[0];
                    $Query = "UPDATE agents SET agent_pause = 'Evento' WHERE agent_user = '{$agent3}'";
                    $agi->exec("NOOP", "$agent");
                    $agi->verbose("Query " . $Query);
                    $conn->Inserir($Query);
                    //Colocando agente em pause de evento
                    shell_exec("sudo asterisk -rx 'queue pause member Local/$agent3@agents'");
                } else {
                    //SIP/1010-00000009
                    $agent = $agi->get_variable("CDR(dstchannel)");
                    $agent = $agent['data'];
                    $agent = explode("/", $agent);
                    $agent = $agent[1];
                    $agent = explode("-", $agent);
                    $agent3 = $agent[0];
                }
                               
                
                
                
            }
        } elseif ($DestAten[0] == "SIP") {
            $agi->exec("Dial", "$destiTrans,60,tT");
        } elseif ($DestAten[0] == "CUSTOM") {
            $agi->exec("Dial", "$destiTrans,60,tT");
        }

        $dialstatus = $agi->get_variable("CDR(disposition)");
        $dialstatus = $dialstatus['data'];
        $agi->verbose("DIAL status " . $dialstatus);

        //Preparando o tronco para o CDR
        $troncoTemp = explode('/', $tronco);
        $tronco = $troncoTemp[1];
        
        
       //Coletando informações para armazenamento de CDR. 
        if (!empty($tempoFalado)) {
            $dadosCdr = $cdrInsert->ExeCdr($agi, $tempoFalado, $agent3,$tronco,$numeroDestino);
        } else {
            $dadosCdr = $cdrInsert->ExeCdr($agi,'','',$tronco,$numeroDestino);
        }
        $Query = "$dadosCdr";
        $conn->Inserir($Query);




        if ($dialstatus != 'ANSWERED') {
            unlink($arquivoGravacao);
        }

        $agi->exec("Hangup", "");
        shell_exec("rm -rf $nome.wav");

    } elseif (strlen($numeroDestino) == 11) {
        if (!empty($nome && $audio2)) {
            $tts = new tts();  
            $tts->Exectts($nome);
            $nome = str_replace(" ", "-", $nome);
            $nome = str_replace(" ", "-", $nome);
            $pasta = "/var/www/html/proBilling/sounds/nome-$nome.mp3";
            file_put_contents($pasta, $tts->getResult());
            $nome = "/var/www/html/proBilling/sounds/nome-$nome";
            shell_exec("lame --decode $nome.mp3 - | sox -v 2.0 -t wav - -t wav -b 16 -r 8000 -c 1 $nome.wav");
            shell_exec("rm -rf $nome.mp3");
        }

        $agi->verbose(print_r($agi->request, true));
        $agi->exec("NOOP", "Torpedo\ de\ Voz\ Brasil-Fixo:\ $numeroDestino ");
        $agi->exec("NOOP", "Data\ da\ ligacao\ $data ");
        $uniqueidTratado = explode('.', $unique);
        $agi->exec("set", "CDR(userfield)={$unique}_{$data}_{$numeroDestino}");
        $agi->exec("set", "CDR(tipo)=Brasil-Fixo");
        $arquivoGravacao = "/var/spool/asterisk/monitor/$dataPasta/$unique" . '_' . "$data" . '_' . "$numeroDestino" . '.wav';
        $agi->exec("set", "__TRANSFBRAZIS=$arquivoGravacao");
        $agi->exec("Playback", "$audio1");
        if (!empty($nome)) {
            $agi->exec("Playback", "$nome");
        }
        if (!empty($audio2)) {
            $agi->exec("Playback", "$audio2");
            ;
        }
        $agi->exec("MixMonitor", "$arquivoGravacao,b");
        if ($DestAten[0] == "QUEUE") {
            $agi->exec("Answer", "");
            $tempoIni = new DateTime("now");
            $tempoIni = $tempoIni->format("Y-m-d H:i:s");
            $tempoIni = strtotime($tempoIni);
            $agi->exec("Queue", "$DestAten[1],,,,2");
            $tempoFim = new DateTime("now");
            $tempoFim = $tempoFim->format("Y-m-d H:i:s");
            $tempoFim = strtotime($tempoFim);
            $tempoFalado = $tempoFim - $tempoIni;
            $agi->exec("NOOP", "Tempo:$tempoFalado");
            ;
        } elseif ($DestAten[0] == "SIP") {
            $agi->exec("Dial", "$destiTrans,60,tT");
        } elseif ($DestAten[0] == "CUSTOM") {
            $agi->exec("Dial", "$destiTrans,60,tT");
        }
        $dialstatus = $agi->get_variable("CDR(disposition)");
        $dialstatus = $dialstatus['data'];
        $agi->verbose("DIAL status " . $dialstatus);
        
        //Preparando o tronco para o CDR
        $troncoTemp = explode('/', $tronco);
        $tronco = $troncoTemp[1];
        

        /*
         * Codigo de teste para Gravar Evento
         */

        if ($tempoFalado > '5') {


            $agent = $agi->get_variable("CDR(dstchannel)");
            $agent = $agent['data'];
            $agent2 = explode("/", $agent);
            $agent = $agent2[1];
            $agent2 = explode("@", $agent);
            $agent3 = $agent2[0];
            $Query = "UPDATE agents SET agent_pause = 'Evento' WHERE agent_user = '{$agent3}'";
            $agi->exec("NOOP", "$agent");
            $agi->verbose("Query " . $Query);
            $conn->Inserir($Query);

            //Colocando agente em pause de evento
            shell_exec("sudo asterisk -rx 'queue pause member Local/$agent3@agents'");
        }
        
        $cdrInsert = new Cdr();
        
        if (!empty($tempoFalado)) {
            $dadosCdr = $cdrInsert->ExeCdr($agi, $tempoFalado, $agent3, $tronco,$numeroDestino);
        } else {
            $dadosCdr = $cdrInsert->ExeCdr($agi,'','',$tronco,$numeroDestino);
        }
        $Query = "$dadosCdr";
        $conn->Inserir($Query);


        if ($dialstatus != 'ANSWERED') {
            unlink($arquivoGravacao);
        }

        $agi->exec("Hangup", "");
        shell_exec("rm -rf $nome.wav");
    }
}      

      







