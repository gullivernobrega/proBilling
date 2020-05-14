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
class Conn {

    public $consultaRetorno;

    public function Consultar($Query) {
//        echo 'Chegou na consulta';

        $dsn = 'mysql:host=localhost;dbname=probilling';
        $user = 'root';
        $pass = 'Brazis3122#';
        $conexao = new PDO($dsn, $user, $pass);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $Query;
        $retorno = $conexao->query($query);
        $this->consultaRetorno = $retorno->fetchAll(PDO::FETCH_ASSOC);
        return $this->consultaRetorno;
    }

    public function Inserir($Query) {

        try {
            $dsn = 'mysql:host=localhost;dbname=probilling';
            $user = 'root';
            $pass = 'Brazis3122#';
            $conexao = new PDO($dsn, $user, $pass);
            $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = $Query;
            $retorno = $conexao->exec($query);
        } catch (PDOException $e) {
            echo $query . "<br>" . $e->getMessage();
        }
    }

}
