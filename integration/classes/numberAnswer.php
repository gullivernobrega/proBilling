<?php

/*
 * Classe com metodos para analisar agente QUEUE do asteris, e armazenar dados 
 * no BD.
 * Integração com CRM via URL-API.
 * 
 * @AUTOR= Gulliver Nóbrega
 * @DATA = 26/04/2019
 * @PART= Kleber de Souza
 */

require_once 'Conn.class.php';

class numberAnswer extends Conn {

    private $conn;
    public $agente;
    public $ramalLogado;
    public $statusAgente;
    public $channel;
    public $numero;
    public $codigoCliente;
    public $nomeCliente;
    public $fila;
    public $tempo;
    public $tempoLogado;
    public $url;

    public function pegaNumero($Logon, $asmanager, $arrayAgents) {

        //Recebendo o Agente
        $this->agente = $Logon;
        //Pegando informações de chamada do Agente
        $server = $asmanager->Command("agent show $Logon");
        $arr = explode("\n", $server['data']);

//        Pegando Ramal Logado pelo Agente:

        $ramalLogado = $arr[7];
        $ramalLogado = explode(":", $ramalLogado);
        $ramalLogado = explode("-", $ramalLogado[1]);
        $ramalLogado = $ramalLogado[0];
        $this->ramalLogado = $ramalLogado;

//        Pegando Status do Agente:

        $statusAgente = $arr[6];
        $statusAgente = explode(":", $statusAgente);
        $statusAgente = $statusAgente[1];
        $statusAgente = trim($statusAgente);

        $this->statusAgente = $statusAgente;

//        Pegando Tempo Logado:

        $microTime = explode(":", $arr[8]);
        $microTime = $microTime[1];
        $microTime = trim($microTime);
        $dateLogado = date("Y-m-d H:i:s", $microTime);
        $dateAtual = date("Y-m-d H:i:s");
        $date = new DateTime("$dateLogado");
        $interval = $date->diff(new DateTime());

        foreach ($interval as $key => $value) {
            $arrDate[$key] = $value;
        }
        $TempoLogado = $arrDate['h'] . "hs" . ":" . $arrDate['i'] . "m" . ":" . $arrDate['s'] . "s";

        $this->tempoLogado = $TempoLogado;

        if (count($arr) == 12) {

            // Explodindo o canal que o Agente esta falando    
            $canal = explode(":", $arr["9"]);

            //Armazenando o canal que esta utilizando.
            $this->channel = $canal[1];


            $result = $asmanager->Command("core show channel $canal[1]");
            $arr2 = explode("\n", $result["data"]);
           
            
            //Pegando o Tipo = Fixo ou movel
            $tipo = preg_grep('/tipo=.(\w+)/', $arr2);
            
            foreach ($tipo as $value) {
                $tipo = explode("=", $value);
                $tipo2 = $tipo[1];
                unset($tipo);
                $tipo = $tipo2;
                
                
            }
            //Pegando o numero que esta falando
            $numero = preg_grep('/CALLED=.(\w+)/', $arr2);
            foreach ($numero as $value) {
                $num = explode("=", $value);
                $num2 = $num[1];
                unset($numero);
                $numero = ($tipo == 'Brasil-Movel') ? $numero = substr($num2, -11) : substr($num2, -10);
                
            }

            //Pegando o nome do Cliente.    
            $nome = preg_grep('/NOME=.(\w+)/', $arr2);
            foreach ($nome as $value) {
                $nomeCliente = explode("=", $value);
                $this->nomeCliente = $nomeCliente[1];
            }

            //Pegando o CPF ou o codigo do cliente.    
            $cpf = preg_grep('/CPF=.(\w+)/', $arr2);
            foreach ($cpf as $value) {
                $codigoCliente = explode("=", $value);
                $this->codigoCliente = $codigoCliente[1];
            }

            //Tratamento para retornar o cliente.
            $this->numero = $numero;

            //Pegando a fila de Atendimento
            $fila = explode(":", $arr2[30]);
            $fila = $fila[1];
            $fila = trim($fila);
            $this->fila = $fila;

            //Pegando tempo de Atendimento
            $tempo = explode(":", $arr2[21]);
            $tempo = $tempo[1];
            $tempo = trim($tempo);
            $this->tempo = $tempo;


            //Preparando URL para envio API

            $Query = "INSERT INTO agents_status(agente, ramal, status, channel, numero, nome, codigo, fila, tempo, tempo_logado) VALUES ('{$this->agente}','{$this->ramalLogado}','{$this->statusAgente}','{$this->channel}','{$this->numero}','{$this->nomeCliente}','{$this->codigoCliente}','{$this->fila}','{$this->tempo}','{$this->tempoLogado}')";
            $this->conn = $this->Inserir($Query);


            $arrayResult = array($this->agente, $this->statusAgente, $this->numero, $this->codigoCliente);
        } elseif (count($arr) == 10) {
            $Query = "INSERT INTO agents_status(agente, ramal, status, channel, numero, nome, codigo, fila, tempo, tempo_logado) VALUES ('{$this->agente}','{$this->ramalLogado}','{$this->statusAgente}','','','','','','','{$this->tempoLogado}')";
            $this->conn = $this->Inserir($Query);
            $this->url = '';
            $arrayResult = array($this->agente, $this->statusAgente, NULL, NULL);
        } elseif (count($arr) == 8) {
            $Query = "INSERT INTO agents_status(agente, ramal, status, channel, numero, nome, codigo, fila, tempo, tempo_logado) VALUES ('{$this->agente}','','{$this->statusAgente}','','','','','','','')";
            $this->conn = $this->Inserir($Query);
            $this->url = '';
            $arrayResult = array($this->agente, $this->statusAgente, NULL, NULL);
        }
        
        
        unset($this->numero);
        unset($this->nomeCliente);
        unset($this->codigoCliente);
        
        return $arrayResult;
    }

    public function EnviaUrl($user, $agents, $arrayAgents) {

        /*
         * Função para analisar numeros atendidos e enviar URL.
         * Envio de URL apenas uma vez por AGENTE(USUARIO)
         * 
         */

        if ($agents[1] == 'INUSE') {
            if (array_key_exists($user, $arrayAgents) === false) {
                $arrayAgents[$user] = $agents;

                /*
                 * Bloco para enviar URL via Curl
                 */

                $url = "http://192.168.1.9/virtua/integracao/enviarMsgDiscador.php?CLIENTE={$agents[3]}&RAMAL={$agents[0]}&NOMEUSUARIO={$agents[0]}&TELEFONE={$agents[2]}&IDLIGACAO=&SAIR=";
                echo "$url \n";
                $iniciar = curl_init();
                curl_setopt($iniciar, CURLOPT_URL, $url);
                curl_exec($iniciar);
                curl_close($iniciar);
            }
        } elseif ($agents[1] == 'NOT_INUSE' || $agents[1] == 'UNAVAILABLE') {

            if (array_key_exists($user, $arrayAgents)) {
                echo 'Entrou';
                unset($arrayAgents[$user]);
            }
        }

        return $arrayAgents;
    }

}
