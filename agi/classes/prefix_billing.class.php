<?php

spl_autoload_register(function ($class_name) {
    require 'classes/' . $class_name . '.class.php';
});
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of prefix_billing
 *
 * @author Gulliver Nóbrega
 */
class prefix_billing extends Conn {

    //put your code here
    private $result;

    public function prefix($rota, $numero) {
        //Pegando DDD do servidor
        $ddd = 62;

        $tronco = explode('/', $rota);
        $tronco = $tronco[1];

//Pegando no banco numeros para remover e adicionar TECH-prefixos de rotas.
        $Query = "SELECT tronco_remover_prefixo, tronco_add_prefixo FROM tronco WHERE tronco_nome = '$tronco'";
        $result = $this->Consultar($Query);
        $result = $result[0];


//Condição para verificar se tem que tratar numero discado.

        if (strlen($numero) == 12) {
            //Número celular discando 0+ddd+número
            if ($result['tronco_remover_prefixo'] == '0' && !empty($result['tronco_add_prefixo'])) {
                $numeroTratado = $result['tronco_add_prefixo'] . substr($numero, -11);
            } elseif (empty($result['tronco_remover_prefixo']) && !empty($result['tronco_add_prefixo'])) {
                $numeroTratado = $result['tronco_add_prefixo'] . $numero;
            } else {
                $numeroTratado = $numero;
            }
            //Número fixo discado 0+ddd+número
            }elseif (strlen($numero) == 11) {
                if ($result['tronco_remover_prefixo'] == '0' && !empty($result['tronco_add_prefixo'])) {
                $numeroTratado = $result['tronco_add_prefixo'] . substr($numero, -10);
            } elseif (empty($result['tronco_remover_prefixo']) && !empty($result['tronco_add_prefixo'])) {
                $numeroTratado = $result['tronco_add_prefixo'] . $numero;
            } else {
                $numeroTratado = $numero;
            }
           //    
        }elseif (strlen($numero) == 9 || strlen($numero) == 8) {

            if ($result['tronco_remover_prefixo'] == '0' && !empty($result['tronco_add_prefixo'])) {
                $numeroTratado = $result['tronco_add_prefixo'] . $ddd . $numero;
            } elseif (empty($result['tronco_remover_prefixo']) && !empty($result['tronco_add_prefixo'])) {
                $numeroTratado = $result['tronco_add_prefixo'] . '0' . $ddd . $numero;
            } else {
                $numeroTratado = '0' . $ddd . $numero;
            }
           
        }elseif (strlen($numero) > 12) {
           $numeroTratado =  $numeroTratado = $result['tronco_add_prefixo'] . $numero;
            
        }
        return $numeroTratado;
    }

}
