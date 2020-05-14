<?php

/**
 * <b>Update.class</b> 
 * Classe responsavel por Alteração generica no banco de dados!
 * 
 * @copyright (c) 2016, Kleber de Souza KLSDESIGNER DESENVOLVIMENTO WEB
 */
class Update extends Conn {

    private $Tabela;
    private $Dados;
    private $Termos;
    private $Places;
    private $Result;
    
    /** @var PDOStatement */
    private $Update;
    
    /** @var PDO */
    private $Conn;
    
    /**
     * <b>ExeUpdate</b> Executa uma Alteração simplificada no banco de dados utilizando prepared statements.
     * Basta informar o nome da tabela e um array atribuitivo com nome da coluna e valor!
     * 
     * @param STRING $Tabela Informe o nome da tabela no banco!
     * @param ARRAY $Dados 
     * @param STRING $Termos = Informe os termos atribuitivo. (  ).
     * @param STRING $ParseString 
     */
    public function ExeUpdate($Tabela, array $Dados, $Termos, $ParseString) {
//        varDump::exeVD($Tabela);
//        varDump::exeVD($Dados);
//        varDump::exeVD($Termos);
//        varDump::exeVD($ParseString);
//        exit();
        
        $this->Tabela =  (string) $Tabela;
        $this->Dados = $Dados;
        $this->Termos = (string) $Termos;
        
        parse_str($ParseString, $this->Places);
        $this->getSyntax();
        $this->Execute();
    }

    /**
     * <b>Obter Resultado:</b> Retorna o ID do Registro inserido ou FALSE caso nenhum registro seja inserido!
     * @return INT $Variavel = lastInsertId OR FALSE
     */
    public function getResult() {
        return $this->Result;
    }
    
    /*
     * Re
     */
    public function getRowCount() {
        return $this->Update->rowCount();
    }
   
    // Seta uma nova ParseString
    public function setPlaces($ParseString) {
        parse_str($ParseString, $this->Places);
        $this->getSyntax();
        $this->Execute();
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
    
    // Obtem o PDO e Prepara a query
    private function Connect() {
        $this->Conn = parent::getConn();
        $this->Update = $this->Conn->prepare($this->Update);       
    }
    
    // Cria a sintaxe da query para Prepared Statements
    private function getSyntax() {
        foreach ($this->Dados as $Key => $Value) {
            $Places[] = $Key . ' =:' .$Key;
        }
        $Places = implode(', ', $Places);
        $this->Update = "UPDATE {$this->Tabela} SET {$Places} {$this->Termos}";
        
    }
    
    // Obtém a Conexão e a Syntax, executa a query!
    private function Execute() {
        $this->Connect();
        try {
            $this->Update->execute(array_merge($this->Dados, $this->Places));
            $this->Result = true;
        } catch (PDOException $e) {
            $this->Result = null;
            KlErro("<b>Erro ao Realizar a Leitura!</b> {$e->getMessage()}", $e->getCode());
        }
    }
       
}
