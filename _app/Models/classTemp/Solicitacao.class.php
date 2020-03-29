<?php

/**
 * Solicitacao.class [ MODEL ]
 * Classe responsavel por realizar cadastro, alteração e listagem das solicitações. 
 * @copyright (c) 17/03/2017, Kleber de Souza BRAZISTELECOM
 */
class Solicitacao {

    private $Data;
    private $Solicitacao_id;
    private $Erro;
    private $Result;

    //Nome da tabela no banco de dados.
    const Tabela = "kl_solicitacao";

    /**
     * Metodo responsavel por criar as solicitações    
     */
    public function ExeCreate($data) {
        $this->Data = $data;

        $this->setData();
        $this->setNome();
        if (!$this->Result):
            $this->Result = TRUE;
            $this->Erro = array("Opa, Já existe um CPF OU CNPJ com este número VERIFIQUE!", KL_ALERT);
        else:
            $this->Create();
        endif;
    }

    /**
     * Metodo responsavel por realizar alterações nas solicitações     
     */
    public function ExeUpdate($solicitacao_id, $data) {
        $this->Solicitacao_id = $solicitacao_id;
        $this->Data = $data;

        $this->setData();
        //$this->setNome();
        $this->Update();
    }

    /**
     * Metodo responsavel por realizar a exclusão das solicitações
     */
    public function ExeDelete($solicitacao_id) {
        $this->Solicitacao_id = $solicitacao_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE solicitacao_id = :id", "id={$this->Solicitacao_id}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover uma Solicitação que não existe no sistema!", KL_INFOR);
        else:
            $this->Delete();
        endif;
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

    /** Prepara os dados create */
    private function setData() {
        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);
    }

    /** Verifica a existencia de alguma Solicitação. */
    private function setNome() {
        $Where = (!empty($this->Solicitacao_id) ? "solicitacao_id != {$this->Solicitacao_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} solicitacao_cpf_cnpj = :c", "c={$this->Data['solicitacao_cpf_cnpj']}");

        if ($readName->getResult()):
            //$this->Data['solicitacao_cpf_cnpj'] = $this->Data['solicitacao_cpf_cnpj'] . '-' . $readName->getRowCount();
            $this->Result = FALSE;
        else:
            $this->Result = TRUE;
        endif;
    }

    /** Execulta a criação dos dados */
    private function Create() {
        $create = new Create;
        $create->ExeCreate(self::Tabela, $this->Data);

        if ($create->getResult()):
            $this->Result = $create->getResult();
            $this->Erro = array("<b>Sucesso:</b> A Solicitação do(a): {$this->Data['solicitacao_nome']} foi enviada com sucesso, Aguarde nosso contato!", KL_ACCEPT);
        endif;
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE solicitacao_id = :id", "id=$this->Solicitacao_id");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> A solicitação de: {$this->Data['solicitacao_nome']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE solicitacao_id = :id", "id={$this->Solicitacao_id}");

        if ($deletar->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, a solicitação foi removido do sistema!", KL_ACCEPT);
        //header("Location: painel.php?exe=paginas/lista");
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel remover a solicitação do sistema!", KL_ERROR);
        endif;
    }

}

// close Clientes