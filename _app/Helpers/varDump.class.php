<?php

/**
 * varDump.class [ HELPER ]
 * Class com o metodo statico usado para formatar o metodo de visualização var_dump(). 
 * Exe.: <b>varDump::exeVD(variavel)</b>
 * 
 * @copyright (c) 29/05/2019, Kleber de Souza BRAZISTELECOM
 */
class varDump {

    public static function exeVD($data) {
       
        if (!empty($data)):

            echo '<pre>';
            var_dump($data);
            echo '</pre>';

        endif;
    }

}
