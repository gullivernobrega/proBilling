<?php

/**
 * <b>Create.class</b> 
 * Classe responsavel por cadastros genericos no banco de dados!
 * 
 * @copyright (c) 2016, Kleber de Souza KLSDESIGNER DESENVOLVIMENTO WEB
 */
class Create extends Conn {

    private $Tabela;
    private $Dados;
    private $Result;
    
    /** @var PDOStatement */
    private $Create;
    
    /** @var PDO */
    private $Conn;
    
    /**
     * <b>ExeCreate</b> Executa um cadastro simplificado no banco de dados utilizando prepared statements.
     * Basta informar o nome da tabela e um array atribuitivo com nome da coluna e valor!
     * 
     * @param STRING $Tabela Informe o nome da tabela no banco!
     * @param array $Dados = Informe um array atribuitivo. ( Nome da Coluna => Valor ). 
     */
    public function ExeCreate($Tabela, array $Dados) {
        $this->Tabela = (string) $Tabela;
        $this->Dados = $Dados;
        
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
        $Fildes = implode(', ', array_keys($this->Dados));
        $Places = ':' . implode(', :', array_keys($this->Dados));
        $this->Create = "INSERT INTO {$this->Tabela} ({$Fildes}) VALUES ({$Places})";  
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
