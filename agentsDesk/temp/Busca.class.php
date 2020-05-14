<?php

require_once './classes/Conexao.class.php';
/**
 * Classe generica simplificada
 */
class Busca extends Conexao {

    //Atributos
    private $Tabela;
    private $Select;
    private $Result;
    private $Query;
    private $Con;
    private $Termos;
    
    /**
     * 
     * @param type $tabela (Nome da dabela)
     * @param type $termos ("WHERE .... ORDER BY ....")
     */
    public function exeBusca($tabela, $termos = null) {
        $this->Tabela = $tabela;
        $this->Select = "SELECT id, ramal, nomedocliente, cpf_cnpj, numero, duracao FROM {$this->Tabela} {$termos}";
       //var_dump($this->Select);
        
       $this->Execute();
    }
    
    /**
     * Metodo generico resposavel por recuperar os dados
     * @return type (Array de dados)
     */
    public function getResult() {
        return $this->Result;
    }

    /** PRIVATES */
    private function Connect() {
        $this->Con = parent::getConn();
        $this->Query = $this->Con->prepare($this->Select);          
    }

    private function Execute() {
        $this->Connect();
        $this->Query->execute();
        $this->Result = $this->Query->fetchAll();
    }

}
