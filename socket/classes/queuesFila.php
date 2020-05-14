<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of queuesFila
 *
 * @author User
 */
//require_once 'Conn.class.php';

class queuesFila extends Conn {

    private $conn;
    private $filaNome;
    private $numeroTel;
    private $tempoFal;

    public function fila($asmanager) {

        $Query = "SELECT queue_name FROM queues";
        $this->conn = $this->Consultar($Query);

        for ($i = 0; $i < count($this->conn); $i++) {
            $server = $asmanager->Command("queue show {$this->conn[$i]['queue_name']}");
            $arr = explode("\n", $server['data']);

//  Buscando no array Campo após Callers, para pegar os canais que estao na espera.
            $key = (!empty(array_search("   Callers: ", $arr))) ? array_search("   Callers: ", $arr) : array_search("   No Callers", $arr);
            $key = $key + 1;
            $quantArray = count($arr);
            $out = array_slice($arr, $key);

            foreach ($out as $value) {
                if (!empty($value)) {
//         Tratando Campo $Value para deixar apenas o canal que esta falando.
                    $channel = explode('. ', $value);
                    $channel = explode(' ', $channel[1]);
                    $channel = $channel[0];
                    $server = $asmanager->Command("core show channel $channel");
                    $arr = explode("\n", $server['data']);

//      Guardando o nome da fila
                    $filaNome = $this->conn[$i]['queue_name'];
                    $this->filaNome = $filaNome;

//       Pegando o numero do telefone aguardando na fila
                    $numeroTel = explode('Extension: ', $arr[25]);
                    $numeroTel = $numeroTel[1];
                    $numeroTel = trim($numeroTel);
                    $this->numeroTel = $numeroTel;
                    
//       Pegando o tempo que esta na fila
                    $tempoFal = explode('Elapsed Time: ', $arr[21]);
                    $tempoFal = $tempoFal[1];
                    $tempoFal = trim($tempoFal);
                    $this->tempoFal = $tempoFal;     

//      Armazenando ligações da fila na tabela queues_fila.
                    
                    $Query = "INSERT INTO queues_fila(fila, numero, tempo) VALUES ('{$this->filaNome}','{$this->numeroTel}','{$this->tempoFal}')";
                    $this->conn = $this->Inserir($Query);

                }
            }
        }

    }

}
