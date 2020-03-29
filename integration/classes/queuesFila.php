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
        $cont = 0;
        $arrayInsert[]='';
        $Query = "SELECT queue_name FROM queues";
        $this->conn = $this->Consultar($Query);
                
        foreach ($this->conn as $valueQueue) {
           
           $server = $asmanager->Command("queue show {$valueQueue['queue_name']}");
            $arr = explode("\n", $server['data']);
            
           
           
            
            
//  Buscando no array Campo após Callers, para pegar os canais que estao na espera.
            $key = (!empty(array_search("   Callers: ", $arr))) ? array_search("   Callers: ", $arr) : array_search("   No Callers", $arr);
            $key = $key + 1;
            $quantArray = count($arr);
            $out = array_slice($arr, $key);
            
            
            if ($out[0] == ''){
                continue;
            }
            //            
            
             
//
//    //      Guardando o nome da fila
                    $filaNome = $valueQueue['queue_name'];
                   $this->filaNome = $filaNome; 
//            
//                    
                    foreach ($out as $value) {
                        
                       if (empty($value)){
                           continue;
                       }
                       print_r($value);          
                    
                       $cont++;
//         Tratando Campo $Value para deixar apenas o canal que esta falando.
                    $channel = explode('. ', $value);
                    $channel = explode(' ', $channel[1]);
                    $channel = $channel[0];
                    $server = $asmanager->Command("core show channel $channel");
                    $arr = explode("\n", $server['data']);
                    
                    

//                    $nome = explode('. ', $value);
//                    $channel = explode(' ', $channel[1]);
//                    $channel = $channel[0];
//                    $server = $asmanager->Command("core show channel $channel");
//                    $arr = explode("\n", $server['data']);

//       Pegando o numero do telefone aguardando na fila
                    $numeroTel = explode('Extension: ', $arr[25]);
                    $numeroTel = $numeroTel[1];
                    $numeroTel = trim($numeroTel);
                    $this->numeroTel = $numeroTel;

//       Pegando o tempo que esta na fila
                    $tempo = preg_grep('/billsec=(\w+)/', $arr);
                    foreach ($tempo as $value) {
                        $tempoFala = explode("=", $value);
                        $tempoFala = $tempoFala[1];
                        $this->tempoFal = gmdate("H:i:s", $tempoFala);
                        
                        
                    }
//  
//
                    $arrayInsert[$cont]['nome'] = $this->filaNome;
                    $arrayInsert[$cont]['numeroTel'] = $this->numeroTel;
                    $arrayInsert[$cont]['tempo'] = $this->tempoFal;
                    
//
////      Armazenando ligações da fila na tabela queues_fila.
//                  
                    echo "\n\nNome da Fila_____________________{$this->filaNome}\n";
                    
                    echo "\n\nNome da Fila**********************{$this->filaNome}\n";
                    
                    echo "\n\nNome da Fila_____________________{$this->filaNome}\n";
                   
                    echo "\n\nNome da Fila_____________________{$this->filaNome}\n";
                   
                    echo "\n\nNome da Fila_____________________{$this->filaNome}\n";
                    if (empty($this->filaNome)){
                        continue;
                    }
                    
                    $cont++;
                    
                    
                } 
                unset($cont);
                
                
                
        }
        foreach ($arrayInsert as $valueFinal) {
                   if ($valueFinal['nome'] == '' || $valueFinal['numeroTel'] == '' || $valueFinal['tempo'] == '' ){
                       continue;
                   }
                    $Query = "INSERT INTO queues_fila(fila, numero, tempo) VALUES ('{$valueFinal['nome']}','{$valueFinal['numeroTel']}','{$valueFinal['tempo']}')";
                    echo "\n\n\n";
                    print_r($Query);
                    $this->conn = $this->Inserir($Query);
        }
            
                    
        
//        for ($i = 0; $i < count($this->conn); $i++) {
//            
//        }
    }

}
