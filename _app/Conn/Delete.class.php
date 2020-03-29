<?php

/**
 * <b>Delete.class</b> 
 * Classe responsavel por Exclusão generica no banco de dados!
 * 
 * @copyright (c) 2016, Kleber de Souza KLSDESIGNER DESENVOLVIMENTO WEB
 */
class Delete extends Conn {

    private $Tabela;
    private $Termos;
    private $Places;
    private $Result;

    /** @var PDOStatement */
    private $Delete;

    /** @var PDO */
    private $Conn;

    /**
     * <b>ExeDelete</b> Executa uma Exclusão simplificada no banco de dados utilizando prepared statements.
     * Basta informar o nome da tabela e um Id!
     * 
     * @param STRING $Tabela Informe o nome da tabela no banco!
     * @param ARRAY $Dados 
     * @param STRING $Termos = Informe os termos atribuitivo. (  ).
     * @param STRING $ParseString 
     */
    public function ExeDelete($Tabela, $Termos, $ParseString) {
        $this->Tabela = (string) $Tabela;
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
        return $this->Delete->rowCount();
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
        $this->Delete = $this->Conn->prepare($this->Delete);
    }

    // Cria a sintaxe da query para Prepared Statements
    private function getSyntax() {
        $this->Delete = "DELETE FROM {$this->Tabela} {$this->Termos}";
    }

    // Obtém a Conexão e a Syntax, executa a query!
    private function Execute() {
        $this->Connect();
        try {
            $this->Delete->execute($this->Places);
            $this->Result = true;
        } catch (PDOException $e) {
            $this->Result = null;
            WSErro("<b>Erro ao Deletar!</b> {$e->getMessage()}", $e->getCode());
        }
    }

}
