<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'classes/Conn.class.php';
require_once 'classes/phpagi-asmanager.php';

$today = date("Y-m-d H:i:s");

$asmanager = new AGI_AsteriskManager;
$conectaServidor = $conectaServidor = $asmanager->connect('127.0.0.1:5038', 'proBilling', 'proBilling');

$conn = new Conn();
$Query = "SELECT * FROM `rotas`";
$conn->Consultar($Query);


$mostra = $conn->consultaRetorno;
$rota = $mostra[0];
extract($rota);

$Query = "SELECT * FROM campanha WHERE campanha_status = 'A' AND campanha_tipo = 'T'";
$value = $conn->Consultar($Query);





//foreach ($conn as $value) {
    
    for ($i = 0; $i < count($value); $i++) {
        
	if (!empty($arrAudio)){
            unset($arrAudio);
        }

	//Montado as Rotas para completamento das chamadas
        $rotaFixo = $value[$i]['campanha_rota_fixo'];
        $rotaMovel = $value[$i]['campanha_rota_movel'];
        $rotaInter = $value[$i]['campanha_rota_internacional'];

        
        if ($value[$i]['campanha_destino_tipo'] == "QUEUE") {
            $destiTrans = "QUEUE/" . $value[$i]['campanha_destino_complemento'];
        } elseif ($value[$i]['campanha_destino_tipo'] == "SIP") {
            $destiTrans = "SIP/" . $value[$i]['campanha_destino_complemento'];
        } elseif ($value[$i]['campanha_destino_tipo'] == "CUSTOM") {
            $destiTrans = $value[$i]['campanha_destino_complemento'];
        }

        $campanhaAmd = $value[$i]['campanha_amd'];

        if ($today >= $value[$i]['campanha_data_inicio'] && $today <= $value[$i]['campanha_data_fim']) {
            if ($value[$i]['campanha_audio_1'] && $value[$i]['campanha_audio_2']) {
                $audio1 = $value[$i]['campanha_audio_1'];
                $audio2 = $value[$i]['campanha_audio_2'];
                $arrAudio = array("/var/www/html/proBilling/arquivos/$audio1", "/var/www/html/proBilling/arquivos/$audio2");
            } elseif ($value[$i]['campanha_audio_1'] && !$value[$i]['campanha_audio_2']) {
                $audio1 = $value[$i]['campanha_audio_1'];
                $arrAudio[] = "/var/www/html/proBilling/arquivos/$audio1";
            } elseif ($value[$i]['campanha_tts_1'] && $value[$i]['campanha_tts_2']) {
                $nomeArq = str_replace(" ", "-", $value[$i]['campanha_nome']);
                $audioTts1 = $value[$i]['campanha_tts_1'];
                $audioTts1 = utf8_encode($audioTts1);
                $audioTts1 = str_replace(" ", "%20", $audioTts1);
                $url = "http://localhost/tts-aws/index.php?text=$audioTts1";
                $pasta = "/var/www/html/proBilling/sounds/tts1-$nomeArq.mp3";
                file_put_contents($pasta, file_get_contents($url));
                shell_exec("lame --decode /var/www/html/proBilling/sounds/tts1-$nomeArq.mp3 - | sox -v 2.0 -t wav - -t wav -b 16 -r 8000 -c 1 /var/www/html/proBilling/sounds/tts1-$nomeArq.wav");
                shell_exec("rm -rf /var/www/html/proBilling/sounds/tts1-$nomeArq.mp3");


                $audioTts2 = $value[$i]['campanha_tts_2'];
                $audioTts2 = utf8_encode($audioTts2);
                $audioTts2 = str_replace(" ", "%20", $audioTts2);
                $url = "http://localhost/tts-aws/index.php?text=$audioTts2";
                $pasta = "/var/www/html/proBilling/sounds/tts2-$nomeArq.mp3";
                file_put_contents($pasta, file_get_contents($url));
                shell_exec("lame --decode /var/www/html/proBilling/sounds/tts2-$nomeArq.mp3 - | sox -v 2.0 -t wav - -t wav -b 16 -r 8000 -c 1 /var/www/html/proBilling/sounds/tts2-$nomeArq.wav");
                shell_exec("rm -rf /var/www/html/proBilling/sounds/tts2-$nomeArq.mp3");



                $arrAudio = array("/var/www/html/proBilling/sounds/tts1-$nomeArq", "/var/www/html/proBilling/sounds/tts2-$nomeArq");
            } elseif ($value[$i]['campanha_tts_1'] && !$value[$i]['campanha_tts_2']) {
                $nomeArq = str_replace(" ", "-", $value[$i]['campanha_nome']);
                $audioTts1 = $value[$i]['campanha_tts_1'];
                $audioTts1 = str_replace(" ", "%20", $audioTts1);
                $url = "http://localhost/tts-aws/index.php?text=$audioTts1";
                $pasta = "/var/www/html/proBilling/sounds/tts1-$nomeArq.mp3";
                file_put_contents($pasta, file_get_contents($url));
                shell_exec("lame --decode /var/www/html/proBilling/sounds/tts1-$nomeArq.mp3 - | sox -v 2.0 -t wav - -t wav -b 16 -r 8000 -c 1 /var/www/html/proBilling/sounds/tts1-$nomeArq.wav");
                shell_exec("rm -rf /var/www/html/proBilling/sounds/tts1-$nomeArq.mp3");
                $arrAudio[] = "/var/www/html/proBilling/sounds/tts1-$nomeArq";
            }
            
                /*
                 * Pegando limite de chamadas para discador ou para Torpedo.
                 */
                if (preg_match('/\//', $value[$i]['campanha_limite_chamada'])){
                   
                    $limiteChamadas = explode("/", $value[$i]['campanha_limite_chamada']);
                    $limitMin = $limiteChamadas[0];
                    $limitMax = $limiteChamadas[1];
                    
                } else {
                    $limitMax = $value[$i]['campanha_limite_chamada'];
                
                }
                
            /*
             * Colocando limite de chamadas de acordo com o tipo de campanha.
             * Torpedo = Limite de chamadas dele mesmo a cada minuto
             * Discador = Limite de chamadas multiplicado pela quantidade de agentes disponivel.
             */

            if ($value[$i]['campanha_tipo'] == 'D' && $value[$i]['campanha_destino_tipo'] == 'QUEUE') {
                $asterisk = $asmanager->Command("queue show {$value[$i]['campanha_destino_complemento']}");
                $resultAst = explode("\n", $asterisk['data']);
                
                $agentsLivre = '';
                $agentsTotal = '';

                foreach ($resultAst as $valueLimit) {
                    
                    //Condição para pegar agentes logados e que estão livres para receber chamadas.
                    if (preg_match('/Not in use/', $valueLimit) && !preg_match('/paused/', $valueLimit)) {
                        $agentsLivre++;
                    }
                    //Condição para pegar total de agentes logados, pode ser agentes logados com pause.
                    if (preg_match('/@agents/', $valueLimit) && !preg_match('/Unavailable/', $valueLimit)) {
                        $agentsTotal++;
                    }
                }
//                echo "\n\n\nTotal de agents livres: $agentsLivre\n\n";
//                echo "\n\n\nTotal de agents       : $agentsTotal\n\n";

                
                
                /*
                 * Bloco para pegar a porcentagem de agentes livres.
                 * De acordo com o total de agentes logados e que não estão em pause
                 * 
                 */
                $PorcentoAgents = ($agentsLivre - $agentsTotal) / $agentsTotal * 100;
                $PorcentoAgents = $PorcentoAgents + 100;

//                echo "\n\n\nPorcentagem  de agents       : $PorcentoAgents\n\n";
                
                
                //Bloco para fazer o calculo de simultaneas de acordo com %(porcentagem) de agentes Livres.
                //Função ceil arredonda para mais.
                //floor() - Arredonda frações para baixo
                //round() - Arredonda um número
                if(!empty($limitMax) && !empty($limitMin)) {
                    if ($PorcentoAgents <= 25) {
                        $quantiaChamadas = $limitMin;
                    } elseif ($PorcentoAgents > 25 && $PorcentoAgents < 50) {
                        //Pegando 80% de $limitMax
                        $quantiaChamadas = 0.80 * $limitMax;
                        if ($quantiaChamadas < $limitMin){
                            $quantiaChamadas = $limitMin;
                        } else {
                            $quantiaChamadas = ceil($quantiaChamadas);
                        }
                        
                        
                    } elseif ($PorcentoAgents >= 50 && $PorcentoAgents < 60) {
                        //Pegando 90% de $limitMax
                        $quantiaChamadas = 0.90 * $limitMax;
                        if ($quantiaChamadas < $limitMin){
                            $quantiaChamadas = $limitMin;
                        } else {
                            $quantiaChamadas = ceil($quantiaChamadas);
                        }
                        
                    } else {
                        //Pegando 100% de $limitMax
                        $quantiaChamadas = $limitMax;
                    }
                
                }
                
                    $quantiaChamadas = $limitMax * $agentsLivre;
                        



                
            } else {
                $quantiaChamadas = $limitMax;
            }

            if ($quantiaChamadas == 0) {
                continue;
            }
            
            unset($limitMax, $limitMin, $limiteChamadas);
            
            $Query = "SELECT * FROM `numero` WHERE `numero_status` = 'A' AND `agenda_id` = (SELECT `agenda_id` FROM `agenda` WHERE `agenda_nome` = '{$value[$i]['campanha_agenda']}') ORDER BY RAND() LIMIT $quantiaChamadas";
            $conn->Consultar($Query);
            $sleepNext = 1;
            $limit = $quantiaChamadas;



            if ($limit <= 60) {
                $sleep = 60 / $limit;
            } else {
                $sleep = $limit / 60;
            }

            foreach ($conn as $numeros) {
                for ($cont = 0; $cont < count($numeros); $cont++) {
                    // Abre ou cria o arquivo exemplo1.call
                    // "a" representa que o arquivo é aberto para ser escrito
                    $numeroDestino = $numeros[$cont]['numero_fone'];
                    $numeroDestino = substr($numeroDestino, 2);
                    $callerid = $numeroDestino;
                    $numeroDestino = "0" . "$numeroDestino";

                    $file = "{$numeros[$cont]['numero_fone']}-{$numeros[$cont]['numero_id']}.call";


                    /*
                     * Montando o Arquivo .call
                     * Autor: Gulliver Nóbrega
                     */

                    if (strlen($numeroDestino) == 12) {

                        /* Tratamento de Prefixo para enviar para Operadora.
                         * Autor: Gulliver Nóbrega
                         */
                        $rotaNome = explode("/", $rotaMovel);
                        $rotaNome = $rotaNome[1];
                        $Query = "SELECT tronco_remover_prefixo, tronco_add_prefixo FROM tronco WHERE tronco_nome = '$rotaNome' ";
                        $conn->Consultar($Query);
                        $rotaPrefixo = $conn->consultaRetorno;
                        extract($rotaPrefixo[0]);
                        $tronco = $rotaMovel;
                        $tipoCall = 'Brasil-Movel';
			
			if ($tronco_remover_prefixo == '0' || !empty($tronco_add_prefixo)) {
 		         
        			$numeroDestino = ($tronco_remover_prefixo == '0') ? $numeroDestino = substr($numeroDestino, '1') : "$numeroDestino";
        			$numeroDestino = (!empty($tronco_add_prefixo)) ? $numeroDestino = $tronco_add_prefixo . "$numeroDestino" : "$numeroDestino";
    			       
    
			}			

                        //Forma correta de deixar para montar o Channel.
                        $call = "Channel: " . $rotaMovel . "/$numeroDestino" . "\n";
                                                
                    } elseif (strlen($numeroDestino) == 11) {

                        /* Tratamento de Prefixo para enviar para Operadora.
                         * Autor: Gulliver Nóbrega
                         */
                        $rotaNome = explode("/", $rotaFixo);
                        $rotaNome = $rotaNome[1];
                        $Query = "SELECT tronco_remover_prefixo, tronco_add_prefixo FROM tronco WHERE tronco_nome = '$rotaNome' ";
                        $conn->Consultar($Query);
                        $rotaPrefixo = $conn->consultaRetorno;
                        extract($rotaPrefixo[0]);
                        $tronco = $rotaFixo;
                        $tipoCall = 'Brasil-Fixo';
                        
                        if ($tronco_remover_prefixo == '0' || !empty($tronco_add_prefixo)) {
                            
			    $numeroDestino = ($tronco_remover_prefixo == '0') ? $numeroDestino = substr($numeroDestino, '1') : "$numeroDestino";
                            $numeroDestino = (!empty($tronco_add_prefixo)) ? $numeroDestino = $tronco_add_prefixo . "$numeroDestino" : "$numeroDestino";
                        }

                        $call = "Channel: " . $rotaFixo . "/$numeroDestino" . "\n";
                    }


                    $call .= "Callerid: " . "$callerid" . "\n";
                    $call .= "Context: torpedo\n";
                    $call .= "Extension: " . $numeroDestino . "\n";
                    $call .= "Priority: 1\n";
                    $call .= "MaxRetries: 1\n";
                    $call .= "RetryTime: 20\n";
                    $call .= "WaitTime: 60\n";
                    $call .= "Set:CALLED=" . $numeroDestino . "\n";
                    $call .= "Set:AUDIO1=" . $arrAudio[0] . "\n";
                    $call .= (array_key_exists(1, $arrAudio) ? "Set:AUDIO2=" . $arrAudio[1] . "\n" : "Set:AUDIO2= " . "\n");
                    $call .= "Set:NOME=" . utf8_encode($numeros[$cont]['numero_nome']) . "\n";
                    $call .= "Set:CPF=" . $numeros[$cont]['numero_cpf_cnpj'] . "\n";
                    $call .= "Set:AGENDAID=" . $numeros[$cont]['agenda_id'] . "\n";
                    $call .= "Set:NUMEROID=" . $numeros[$cont]['numero_id'] . "\n";
                    $call .= "Set:DESTITRANS=" . $destiTrans . "\n";
                    $call .= "Set:CAMPANHAAMD=" . $campanhaAmd . "\n";
                    $call .= "Set:OPERADORA=" . $operadora . "\n";
                    $call .= "Set:TIPOCALL=" . $tipoCall . "\n";
                    $call .= "Set:TRONCO=" . $tronco . "\n";

                    generateCallFile($call, $file, $sleepNext);

                    //30.    60 /30 == 2
                    if ($limit <= 60) {
                        $sleepNext += $sleep;
                    } else {
                        //a cada multiplo do resultado, passo para o proximo segundo
                        if (($numeros % $sleep) == 0) {
                            $sleepNext += 1;
                        }
                    }

                    $Query = "UPDATE `numero` SET `numero_status`='P' WHERE `numero_id` = '{$numeros[$cont]['numero_id']}'";
                    $conn->Inserir($Query);
                }
            }
        }
    }
     
        
//}

function generateCallFile($callFile, $file, $time = 0) {

    $fp = fopen("/var/www/html/proBilling/tmp/$file", "a+");
    fwrite($fp, $callFile);
    fclose($fp);

    $time += time();

    touch("/var/www/html/proBilling/tmp/$file", $time);
    chmod("/var/www/html/proBilling/tmp/$file", 0777);

    shell_exec("sudo mv /var/www/html/proBilling/tmp/$file /var/spool/asterisk/outgoing/$file");
}
