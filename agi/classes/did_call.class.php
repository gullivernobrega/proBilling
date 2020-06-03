<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Conn
 *
 * @author Gulliver Nobrega
 */
class did_call {

    private $conn;
    private $unique;
    private $uniqueTratado;
    private $numeroArquivo;
    private $tronco;
    private $data;
    private $dado;
    private $arquivoGravacao;
    private $tempoFalado;
    private $dialstatus;

    public function exeDID($agi, $conn, $cdr, $unique, $numeroArquivo, $tronco, $data, $dado) {

        $this->conn = $conn;
        $this->unique = $unique;
        $this->numeroArquivo = $numeroArquivo;
        $this->tronco = $tronco;
        $this->data = $data;
        $this->dado = $dado;

        $this->did_noop($agi);
        $this->set_did_grav($agi);
        $this->set_did_var($agi, $conn);
        $this->did_dest($agi, $conn);
        $this->did_dialStatus($agi);
        $this->did_CDR($agi, $conn, $cdr);
        $this->did_finish($agi);
    }

    private function did_noop($agi) {

        $agi->verbose(print_r($agi->request, true));
        $agi->verbose(print_r($agi->request['agi_extension'], true));
        $agi->exec("NOOP", "Ligacao\ Recebida\ de\ :\ $this->numeroArquivo ");
        $agi->exec("NOOP", "Data\ da\ ligacao\ $this->data ");
        $agi->exec("NOOP", "Destino\ da\ ligacao\ :\ {$this->dado['did_destino']} ");
    }

    private function set_did_grav($agi) {
// Organizando para gravar chamadas.
        $dataPasta = date('d-m-Y');

        $uniqueidTratado = explode('.', $this->unique);
        $this->uniqueTratado = $uniqueidTratado[0];
        $arquivoGravacao = "/var/spool/asterisk/monitor/$dataPasta/$this->uniqueTratado" . '_' . "$this->data" . '_' . "$this->numeroArquivo" . '.wav';
        $this->arquivoGravacao = $arquivoGravacao;

        $agi->exec("set", "CDR(userfield)={$uniqueidTratado[0]}_{$data}_{$numeroArquivo}");
        $agi->exec("set", "CALLERID(num)={$numeroArquivo}");
        $agi->exec("set", "CDR(tipo)=Recebida");
        $agi->exec("set", "__TRANSFBRAZIS=$this->arquivoGravacao");
        $agi->exec("MixMonitor", "$this->arquivoGravacao,b");
    }

    private function set_did_var($agi) {

//Setando Variaveis utilizadas para CDR.
        $agi->exec("set", "CDR(userfield)={$this->uniqueTratado}_{$this->data}_{$this->numeroArquivo}");
        $agi->exec("set", "CALLERID(num)={$this->numeroArquivo}");
        $agi->exec("set", "CDR(tipo)=Recebida");
        $agi->exec("set", "__TRANSFBRAZIS=$this->arquivoGravacao");
        $agi->exec("MixMonitor", "$this->arquivoGravacao,b");
    }

    private function did_dest($agi, $conn) {

//Destino de Atendimento da chamada

        if ($this->dado['did_destino_func'] == "SIP") {
            $agi->exec("Dial", "{$this->dado[did_destino]},60,tT");
        } elseif ($this->dado['did_destino_func'] == "QUEUE") {
            $agi->exec("Answer", "");
            $agi->exec("Playback", "/var/www/html/proBilling/sounds/Brazistelecom2");
            $tempoIni = new DateTime("now");
            $tempoIni = $tempoIni->format("Y-m-d H:i:s");
            $tempoIni = strtotime($tempoIni);
            $agi->exec("NOOP", "$DataEntrada:$tempoIni");
            $channel = $agi->request['agi_channel'];
            $agi->exec("set", "__CANALCALL=$channel");
//            $agi->exec("Queue", "{$this->dado['did_destino']},tT,,,,,,setstartcall,s,1($channel)");
            $agi->exec("Queue", "{$this->dado['did_destino']},tT,,,,/var/www/html/proBilling/agi/did-teste.php");
            $tempoFim = new DateTime("now");
            $tempoFim = $tempoFim->format("Y-m-d H:i:s");
            $tempoFim = strtotime($tempoFim);
            $agi->exec("NOOP", "$DataFinal:$tempoFim");
            $this->tempoFalado = $tempoFim - $tempoIni;
            $agi->exec("NOOP", "DiferencadeTempo:$tempoFalado");
            $agent = $agi->get_variable("CDR(dstchannel)");
            $agent = $agent['data'];
            $agent2 = explode("/", $agent);
            $agent = $agent2[1];
            $agent2 = explode("@", $agent);
            $agent3 = $agent2[0];
            $Query = "UPDATE agents SET agent_pause = 'Evento' WHERE agent_user = '{$agent3}'";
            $agi->exec("NOOP", "$agent3");
            $agi->verbose("Query " . $Query);

            $conn->Inserir($Query);
//Colocando agente em pause de evento
            shell_exec("sudo asterisk -rx 'queue pause member Local/$agent3@agents'");
        } elseif ($this->dado['did_destino_func'] == "CUSTOM") {
            $agi->exec("Answer", "");
            $agi->exec("Dial", "{$this->dado['did_destino']},60,tT");
        } elseif ($this->dado['did_destino_func'] == "URA") {

            $Query = "SELECT * FROM ura WHERE ura_nome = '{$this->dado['did_destino']}'";
            $agi->exec("NoOp", "$Query");
            $uraOp = $conn->Consultar($Query);
            $uraOp = $uraOp[0];
            $new = array_filter($uraOp);
            $ve = (int) $uraOp['ura_tentativa'];
            $opInvalida = '/var/www/html/proBilling/arquivos/' . $uraOp['ura_audio_invalida'];
            $opTentativa = '/var/www/html/proBilling/arquivos/' . $uraOp['ura_audio_tentativa'];
            $uraAudio = '/var/www/html/proBilling/arquivos/' . $uraOp['ura_audio'];
            $agi->exec("NoOp", "Audio:$uraAudio");
            $agi->exec("Answer", "");
            if (isset($new['op_t'])) {
                $uraTOut = $new['op_t'];
            }
        
         /*
         * Fazendo um loop com número de tentativas da URA
         * 
         */
            
            for ($i = 1; $i <= $ve; $i++) {

                $get_resp = $agi->get_data($uraAudio, 3000, 1);
                $get_resp = $get_resp['result'];
                $agi->exec("NoOp", "$get_resp");


                if (!empty($get_resp)) {
                    $destKey = 'op_' . $get_resp;
                    $ver = array_key_exists($destKey, $new);
                }

                if ($i < $ve && empty($get_resp)) {
                    $agi->exec("Playback", "$opTentativa");
                }
//
                if ($i < $ve && !empty($get_resp) && $ver == false) {
                    $agi->exec("NoOp", "OpcaoInValida");
                    $agi->exec("Playback", "$opInvalida");
                }
            }
           
         /*
         * Pegando se foi digitado algo ou não e tomando decisões
         */

            if (!empty($get_resp && $ver == true)) {
                $destKey = 'op_' . $get_resp;
                $agi->exec("Dial", "{$uraOp["'$destKey'"]},60,tT");
            } elseif (empty($get_resp)) {
                $agi->exec("Dial", "{$uraTOut},60,tT");
            } elseif (!empty($get_resp) && $ver == false) {
                $agi->exec("Playback", "$opInvalida");
                $agi->exec("Dial", "{$uraTOut},60,tT");
            }
        }
    }

    private function did_dialStatus($agi) {

//pegando status da chamada
        $dialstatus = $agi->get_variable("CDR(disposition)");
        $this->dialstatus = $dialstatus['data'];
        $agi->verbose("DIAL status " . $this->dialstatus);
    }

    private function did_CDR($agi, $conn, $cdr) {

//armazenando log em cdr
        if (!empty($this->tempoFalado)) {
            $dadosCdr = $cdr->ExeCdr($agi, $this->tempoFalado, NULL, $this->tronco);
        } else {
            $dadosCdr = $cdr->ExeCdr($agi, NULL, NULL, $this->tronco);
        }
        $Query = "$dadosCdr";
        $conn->Inserir($Query);
    }

    private function did_finish($agi) {

//checa se finaliza a chamada
        if ($this->dialstatus != 'ANSWERED') {
            unlink($this->arquivoGravacao);
        }if ($this->dialstatus == 'ANSWERED' || $this->dialstatus == 'CANCEL' || $this->dialstatus == 'BUSY') {
            $agi->exec("Hangup", "");
        }
    }

}
