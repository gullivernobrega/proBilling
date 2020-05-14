<?php

/**
 * <b>Createcsv.class</b> 
 * Classe responsavel por cadastros genericos no banco de dados!
 * 
 * @copyright (c) 2016, Kleber de Souza KLSDESIGNER DESENVOLVIMENTO WEB
 */
class Createcsv extends Conn {

    private $Tabela;
    private $Dados;
    private $Fildes;
    private $Places;
    private $Result;
    
    /** @var PDOStatement */
    private $Create;
    
    /** @var PDO */
    private $Conn;
    
    /**
     * <b>ExeCreate</b> Executa um cadastro multiplu no banco de dados.
     * Basta informar o nome da tabela e os fildes atribuitivo com nome da coluna e o places com os valores!
     * 
     * @param STRING $Tabela Informe o nome da tabela no banco!      
     */
    public function ExeCreate($Tabela, $Fildes, $Places) {
        $this->Tabela = (string) $Tabela;
        $this->Fildes = $Fildes;
        $this->Places = $Places;

        $this->getSyntax();                
        $this->Execute();
    }

    /**
     * <b>getResult()</b> Retorna o resultado do cadastro
     */    
    public function getResult() {
        return $this->Result;
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
    private function Connect() {
        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($this->Create);
    }
    
    private function getSyntax() {        
        $this->Create = "INSERT INTO {$this->Tabela} ({$this->Fildes}) VALUES {$this->Places}";  
    }
    
    private function Execute() {
        $this->Connect();
        try {
            $this->Create->execute($this->Dados);
            $this->Result = $this->Conn->lastInsertId();
        } catch (Exception $e) {
            $this->Result = null;
            KLErro("<b>Erro ao Cadastrar:</b> {$e->getMessage()}", $e->getCode());
        }
    }
       
}
