#!/usr/bin/php -q
<?php
if (function_exists('pcntl_signal')) {
    pcntl_signal(SIGHUP, SIG_IGN);
}

spl_autoload_register(function ($class_name) {
    require 'classes/' . $class_name . '.class.php';
});

//Instanciando a Classe do asterisk
$agi = new AGI();
$unique = $agi->request['agi_uniqueid'];
$numeroDestino = $agi->request['agi_extension'];

//Instanciando a Classe do banco
$conn = new Conn();
$router = new router_billing();

//Variaveis utilizadas nos contextos:
$data = date('Y-m-d_H:i:s');
$dataPasta = date('d-m-Y');

//Query para armazenar rotas cadastradas no sistema:
$Query = "SELECT * FROM `rotas`";
$dados = $conn->Consultar($Query);
$dados = $dados[0];

//Pegando rotas
$rota = $router->router($dados);

//Pegando DDD local do sistema.
$ddd = '62';

//Status Call
$dialstatus = '';
//Iniciando aplicação

if (strlen($numeroDestino) == 12 || strlen($numeroDestino) == 9) {
    foreach ($rota['movel'] as $valueMovel) {

        if ($dialstatus == 'ANSWERED' || $dialstatus == 'CANCEL' || $dialstatus == 'NO ANSWER') {
            exit();
        } elseif (empty($dialstatus) || $dialstatus == 'FAILED') {
            //Chamando classe que trata os numeros.
//Add prefixo, remove prefixo e retorna o número        
            $prefix = new prefix_billing();
            $numCall = $prefix->prefix($valueMovel, $numeroDestino);

//**********************************||************************************************************************//
//                      Iniciando interação com asterisk.                                                     //   
//**********************************||************************************************************************//        

            $interacao = new Interacao_Asterisk();
            $interacao->Noop_Asterisk($agi, $numCall, $numeroDestino, 'celular', $valueMovel);


//      Tratando Unique-ID
            $unique = $interacao->Unique_Asterisk($unique);

            $numeroDestino = (strlen($numeroDestino) == 9 ? '0' . $ddd . $numeroDestino : $numeroDestino);
            $agi->exec("set", "CDR(userfield)={$unique}_{$data}_{$numeroDestino}");

//          Setando no CDR(Tipo)
            $agi->exec("set", "CDR(tipo)=Brasil-Movel");

//          Organizando local e pasta para gravação de chamadas.
            $arquivoGravacao = "/var/spool/asterisk/monitor/$dataPasta/$unique" . '_' . "$data" . '_' . "$numeroDestino" . '.wav';

//          Setando nome da gravação para transferencias  
            $agi->exec("set", "__TRANSFBRAZIS=$arquivoGravacao");
            $agi->exec("MixMonitor", "$arquivoGravacao,b");

//          Executando o comando Dial  
            $agi->exec("Dial", "$valueMovel/$numCall,60,tT");

//**********************************||************************************************************************//
//                      Status da chamada após o comando Dial.                                                //   
//**********************************||************************************************************************//
            $dialstatus = $interacao->dialStatus();


//          Chamando Classe CDR para armazenar no banco.  
            $tronco = explode('/', $valueMovel);
            $tronco = $tronco[1];
            $cdrInsert = new Cdr();
            $dadosCdr = $cdrInsert->ExeCdr($agi, NULL, NULL, $tronco);

//          Armazenando no banco.
            $Query = "$dadosCdr";
            $conn->Inserir($Query);

            if ($dialstatus != 'ANSWERED') {
                unlink($arquivoGravacao);
            }if ($dialstatus == 'ANSWERED' || $dialstatus == 'CANCEL') {
                $agi->exec("Hangup", "");
                break;
            }
        }
    }
} elseif (strlen($numeroDestino) == 11 || strlen($numeroDestino) == 8) {
    foreach ($rota['fixo'] as $valueFixo) {

        if ($dialstatus == 'ANSWERED' || $dialstatus == 'CANCEL' || $dialstatus == 'NO ANSWER') {
            exit();
        } elseif (empty($dialstatus) || $dialstatus == 'FAILED') {
//Chamando classe que trata os numeros.
//Add prefixo, remove prefixo e retorna o número        
            $prefix = new prefix_billing();
            $numCall = $prefix->prefix($valueFixo, $numeroDestino);

//**********************************||************************************************************************//
//                      Iniciando interação com asterisk.                                                     //   
//**********************************||************************************************************************//        

            $interacao = new Interacao_Asterisk();
            $interacao->Noop_Asterisk($agi, $numCall, $numeroDestino, 'Fixo', $valueFixo);


//      Tratando Unique-ID
            $unique = $interacao->Unique_Asterisk($unique);

            $numeroDestino = (strlen($numeroDestino) == 8 ? '0' . $ddd . $numeroDestino : $numeroDestino);
            $agi->exec("set", "CDR(userfield)={$unique}_{$data}_{$numeroDestino}");

//          Setando no CDR(Tipo)
            $agi->exec("set", "CDR(tipo)=Brasil-Fixo");

//          Organizando local e pasta para gravação de chamadas.
            $arquivoGravacao = "/var/spool/asterisk/monitor/$dataPasta/$unique" . '_' . "$data" . '_' . "$numeroDestino" . '.wav';

//          Setando nome da gravação para transferencias  
            $agi->exec("set", "__TRANSFBRAZIS=$arquivoGravacao");
            $agi->exec("MixMonitor", "$arquivoGravacao,b");

//          Executando o comando Dial  
            $agi->exec("Dial", "$valueFixo/$numCall,60,tT");

//**********************************||************************************************************************//
//                      Status da chamada após o comando Dial.                                                //   
//**********************************||************************************************************************//
            $dialstatus = $interacao->dialStatus();


//          Chamando Classe CDR para armazenar no banco.  
            $tronco = explode('/', $valueFixo);
            $tronco = $tronco[1];
            $cdrInsert = new Cdr();
            $dadosCdr = $cdrInsert->ExeCdr($agi, NULL, NULL, $tronco);

//          Armazenando no banco.
            $Query = "$dadosCdr";
            $conn->Inserir($Query);

            if ($dialstatus != 'ANSWERED') {
                unlink($arquivoGravacao);
            }if ($dialstatus == 'ANSWERED' || $dialstatus == 'CANCEL') {
                $agi->exec("Hangup", "");
                break;
            }
        }
    }
} elseif (strlen($numeroDestino) == 4) {

    $agi->exec("set", "CDR(tipo)=Internas");
    $agi->exec("Dial", "SIP/$numeroDestino,60,tT");

    $cdrInsert = new Cdr();
    $dadosCdr = $cdrInsert->ExeCdr($agi, NULL, NULL, 'proBilling');
    $conn = new Conn();
    $Query = "$dadosCdr";
    $conn->Inserir($Query);
} elseif (strlen($numeroDestino) > 12) {
    foreach ($rota['inter'] as $valueInter) {
        if ($dialstatus == 'ANSWERED' || $dialstatus == 'CANCEL' || $dialstatus == 'NO ANSWER') {
            exit();
        } elseif (empty($dialstatus) || $dialstatus == 'FAILED') {
            //Chamando classe que trata os numeros.
            //Add prefixo, remove prefixo e retorna o número        
            $prefix = new prefix_billing();
            $numCall = $prefix->prefix($valueInter, $numeroDestino);

//**********************************||************************************************************************//
//                      Iniciando interação com asterisk.                                                     //   
//**********************************||************************************************************************//        

            $interacao = new Interacao_Asterisk();
            $interacao->Noop_Asterisk($agi, $numCall, $numeroDestino, 'Internacional', $valueInter);


//      Tratando Unique-ID
            $unique = $interacao->Unique_Asterisk($unique);

            $agi->exec("set", "CDR(userfield)={$unique}_{$data}_{$numeroDestino}");

//          Setando no CDR(Tipo)
            $agi->exec("set", "CDR(tipo)=Internacional");

//          Organizando local e pasta para gravação de chamadas.
            $arquivoGravacao = "/var/spool/asterisk/monitor/$dataPasta/$unique" . '_' . "$data" . '_' . "$numeroDestino" . '.wav';

//          Setando nome da gravação para transferencias  
            $agi->exec("set", "__TRANSFBRAZIS=$arquivoGravacao");
            $agi->exec("MixMonitor", "$arquivoGravacao,b");

//          Executando o comando Dial  
            $agi->exec("Dial", "$valueInter/$numCall,60,tT");

//**********************************||************************************************************************//
//                      Status da chamada após o comando Dial.                                                //   
//**********************************||************************************************************************//
            $dialstatus = $interacao->dialStatus();


//          Chamando Classe CDR para armazenar no banco.  
            $tronco = explode('/', $valueInter);
            $tronco = $tronco[1];
            $cdrInsert = new Cdr();
            $dadosCdr = $cdrInsert->ExeCdr($agi, NULL, NULL, $tronco);

//          Armazenando no banco.
            $Query = "$dadosCdr";
            $conn->Inserir($Query);

            if ($dialstatus != 'ANSWERED') {
                unlink($arquivoGravacao);
            }if ($dialstatus == 'ANSWERED' || $dialstatus == 'CANCEL') {
                $agi->exec("Hangup", "");
                break;
            }
        }
    }
}




