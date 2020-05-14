<?php

/**
 * CheckBoletos.class [ Model ]
 * Class responsavel por checar Clientes e Boletos em debitos 
 * @copyright (c) 12/01/2017, Kleber de Souza BRAZISTELECOM
 */
class CheckBoletos {

    private $Data;
    private $Cli_id;
    private $NomePdf;
    private $Erro;
    private $Result;

    //Nome da tabela no banco de dados.
    const Tabela = "kl_cliente";

    /**
     * Metodo responsavel por criar as páginas cms do site   
     */
    public function ExeBoleto(array $data) {
        $this->Data = $data;

        $this->setData();
        //$this->checkStatus();
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

    /** Verifica a existencia de boleto do cliente */
    private function setData() {

        //Elimina entrada indesejadas
        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

        // verifica os dados no banco        
        $readCli = new Read;
        $readCli->ExeRead(self::Tabela, "WHERE cli_cpf = :cpf  AND cli_chave = :chave", "cpf={$this->Data['cli_cpf']}&chave={$this->Data['cli_chave']}");

        if (!$readCli->getResult()):
            $this->Result = false;
            $this->Erro = array("Opa, Este Cpf: {$this->Data['cli_cpf']}, não possui Boletos, ou não esta cadastrado no sistema!", KL_ALERT);
        else:
            $this->Result = $readCli->getResult();
            if ($this->Result[0]['cli_status'] == "S"):
                $this->Result = $this->Result;
            else:
                $this->Result = false;
                $this->Erro = array("Opa, O Cpf: {$this->Data['cli_cpf']}, não possui Boletos, ou ainda não esta ativado no sistema!", KL_ALERT);
            endif;
        endif;
    }

    /** Retorna os dados */
    private function checkStatus() {
        if (isset($status) && $status[0]['cli_status'] == "S"):
            $this->Result = $status;
        else:
            $this->Result = false;
            $this->Erro = array("Opa, Este Cpf: {$this->Data['cli_cpf']}, não possui Boletos, ou ainda não esta ativado no sistema!", KL_ALERT);
        endif;
    }

}
