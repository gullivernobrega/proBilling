<?php

/**
 * DataLimite.class [ MODEL ]
 * Classe responsavel por fazer alteração da data limite de negociação;
 * @copyright (c) 15/03/2017, Kleber de Souza BRAZISTELECOM
 */
class DataLimite {

    private $Data;
    private $Limite_id;
    private $Erro;
    private $Result;

    //Nome da tabela no banco de dados.
    const Tabela = "kl_data_limite";
    
    /**
     * Metodo responsagem por realizar alterações na configuração do site     
     */
    public function ExeUpdate($limite_id, $data) {
        $this->Limite_id = $limite_id;
        $this->Data = $data;

        //Faz a alteração da data. 
        $this->Update();
    }

    function getResult() {
        return $this->Result;
    }

    function getErro() {
        return $this->Erro;
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */

    /** Prepara os dados */
    private function setData() {
      
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE limite_id = :id", "id={$this->Limite_id}");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> A Data Limite: {$this->Data['limite_data']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

}
