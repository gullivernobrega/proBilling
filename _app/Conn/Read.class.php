<?php

/**
 * <b>Read.class</b> 
 * Classe responsavel por leituras generica no banco de dados!
 * 
 * @copyright (c) 2016, Kleber de Souza KLSDESIGNER DESENVOLVIMENTO WEB
 */
class Read extends Conn {

    private $Select;
    private $Places;
    private $Result;

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    /**
     * <b>ExeRead:</b> Executa uma leitura simplificada no banco de dados utilizando prepared statements.
     * Basta informar o nome da tabela os termos da seleção e uma analize em cadeia (ParseString) para executar. 
     * 
     * @param STRING $Tabela Informe o nome da tabela no banco!
     * @param STRING $Termos = WHERE | ORDER | LIMIT :limit | OFFSET :offset
     * @param STRING $ParseString = link={$link}&link2={$Link2}
     */
    public function ExeRead($Tabela, $Termos = null, $ParseString = null) {
        if (!empty($ParseString)) {
            parse_str($ParseString, $this->Places);
        }                
        // Prepara a select
        $this->Select = "SELECT * FROM {$Tabela} {$Termos}";        
        //Executa a query
        $this->Execute();
        
    }
    
    /**
     * <b>Obter resultados</b> Retorna um array com todos os resultados obtidos. Envelope primário numerico.
     * um resultado chame o indice getResult()[0]!
     * @return ARRAY $this = Array ResultSet 
     */
    public function getResult() {
        return $this->Result;
    }

    /**
     * <b>Contar Registro:</b> Retorna o número de registros encotrados pelo select!
     * @return INT $Var = Quatidade de registros encontrados
     */
    public function getRowCount() {
        return $this->Read->rowCount();
    }

    /**
     * <b>Full Read</b> Executa leitura de dados via query que deve ser montada manualmente para possibilitar
     * seleção de multiplas tabelas em uma unica query!
     * 
     * @param STRING $Query =  Query Select Syntax
     * @param STRING $ParseString = link={$link}&link2={$link2}
     */
    public function FullRead($Query, $ParseString = null) {
        $this->Select = (string) $Query;
        if (!empty($ParseString)) {
            parse_str($ParseString, $this->Places);
        }
        $this->Execute();
    }

    // Seta uma nova ParseString
    public function setPlaces($ParseString) {
        parse_str($ParseString, $this->Places);
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
        $this->Read = $this->Conn->prepare($this->Select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
    }

    // Cria a sintaxe da query para Prepared Statements
    private function getSyntax() {
        if ($this->Places) {
            foreach ($this->Places as $Vinculo => $Valor) {
                if ($Vinculo == 'limit' || $Vinculo == 'offset') {
                    $Valor = (int) $Valor;
                }
                $this->Read->bindValue(":{$Vinculo}", $Valor, ( is_int($Valor) ? PDO::PARAM_INT : PDO::PARAM_STR));
            }
        }
    }

    // Obtém a Conexão e a Syntax, executa a query!
    private function Execute() {
        $this->Connect();
        try {
            $this->getSyntax();
            $this->Read->execute();
            $this->Result = $this->Read->fetchAll();
        } catch (PDOException $e) {
            $this->Result = null;
            KLErro("<b>Erro ao Realizar a Leitura!</b> {$e->getMessage()}", $e->getCode());
        }
    }

}
