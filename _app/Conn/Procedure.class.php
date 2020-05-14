<?php

/**
 * <b>Procedure.class</b> 
 * Classe responsagem por realizar leituras de Procedures mysSql
 * @copyright (c) 23/08/2018, Kleber de Souza BRAZISTELECOM
 */
class Procedure extends Conn{

    private $Select;
    private $Places;
    private $Campos;
    private $Result;
    private $Termo;
    private $Procedimento;
    private $Pagina;
    private $Sintax;

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;


    /**
     * <b>ExeProcedure:</b> Executa uma leitura no banco de dados utilizando stored procedure.
     * Basta informar o nome da procedure os termos se houver para executar. 
     *
     * @param STRING $Procedimento = nomedoProcedimento()
     * @param STRING $temo = Condição do procedimento
     */
    public function ExeProcedure($Procedimento, $termo = NULL, $ParseString = null) {
        // Prepara a select
        $this->Procedimento = $Procedimento;
        
        if ($termo):
            $termo = implode(', ', array_values($termo));
            $this->Termo = $termo;
        endif;


        if (!empty($ParseString)):
            parse_str($ParseString, $this->Places);
        endif;
     
        $this->Select = "CALL {$this->Procedimento}({$this->Termo})";
        
        //Executa a Select
        $this->ExecuteProcedure();
    }

    /**
     * <b>Obter resultados</b> Retorna um array com todos os resultados obtidos. Envelope primário numerico.
     * um resultado chame o indice getResult()[0]!
     * @return ARRAY $this = Array ResultSet 
     */
    public function getResult() {
        return $this->Result;
    }


    // Seta uma nova ParseString
    public function setPlaces($ParseString) {
        parse_str($ParseString, $this->Places);
        $this->ExecuteProcedure();
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
    private function ExecuteProcedure() {
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
