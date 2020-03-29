<?php

/**
 * <b>DeleteAll.class</b> 
 * Classe responsavel por Exclusão generica no banco de dados!
 * 
 * @copyright (c) 2016, Kleber de Souza KLSDESIGNER DESENVOLVIMENTO WEB
 */
class DeleteAll extends Conn {

    private $Tabela;
    private $Termos;
    private $Places;
    private $Result;

    /** @var PDOStatement */
    private $DeleteAll;

    /** @var PDO */
    private $ConnAll;

    /**
     * <b>ExeDeleteAll</b> Executa uma Exclusão garal no banco de dados utilizando prepared statements.
     * Basta informar o nome da tabela!
     * 
     * @param STRING $Tabela Informe o nome da tabela no banco!
     */
    public function ExeDeleteAll($Tabela) {
        $this->Tabela = (string) $Tabela;

        $this->getSyntaxAll();
        $this->ExecuteAll();
    }

    /**
     * <b>ExeDeleteSearch</b> Executa uma Exclusão dos dados da pesquisa no banco de dados
     * Basta informar o nome da tabela!
     * 
     * @param STRING $Tabela Informe o nome da tabela no banco!
     * @param STRING $Termo contem os id da pesquisa
     */
    public function ExeDeleteSearch($Tabela, $Termo) {
        $this->Tabela = (string) $Tabela;
        $this->Places = $Termo; 
        
        $this->getSyntaxSearch();
        $this->ExecuteAll();
    }

    /**
     * <b>Obter Resultado:</b> Retorna o ID do Registro inserido ou FALSE caso nenhum registro seja inserido!
     * @return INT $Variavel = lastInsertId OR FALSE
     */
    public function getResult() {
        return $this->Result;
    }

    /*
     * Resposta
     */
    public function getRowCountAll() {
        return $this->DeleteAll->rowCount();
    }

    // Seta uma nova ParseString
    public function setPlaces($ParseString) {
        parse_str($ParseString, $this->Places);
        $this->getSyntax();
        $this->ExecuteAll();
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
    // Obtem o PDO e Prepara a query    
    private function ConnectAll() {
        $this->ConnAll = parent::getConn();
        $this->DeleteAll = $this->ConnAll->prepare($this->DeleteAll);
    }

    // Cria a sintaxe da query para Prepared Statements
    private function getSyntaxAll() {
        $this->DeleteAll = "TRUNCATE TABLE {$this->Tabela}";
    }

    // Cria a sintaxe da query para Prepared Statements
    private function getSyntaxSearch() {
        //$this->DeleteAll = "DELETE FROM {$this->Tabela} WHERE numero_id IN ({$this->Places})";         
        $this->DeleteAll = "DELETE FROM {$this->Tabela} {$this->Places}"; 
    }

    // Obtém a Conexão e a Syntax, executa a query!
    private function ExecuteAll() {
        $this->ConnectAll();
        try {
            $this->DeleteAll->execute();
            $this->Result = true;
        } catch (PDOException $e) {
            $this->Result = null;
            KLErro("<b>Erro ao Deletar!</b> {$e->getMessage()}", $e->getCode());
        }
    }

}
