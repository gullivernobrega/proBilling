<?php

/**
 * CampanhaSms.class [ MODEL ]
 * Classe responsavel por realizar cadastro, alteração e listagem de dados da Campanha SMS. 
 * @copyright (c) 05/07/2019, Kleber de Souza BRAZISTELECOM
 */
class CampanhaSms {

    private $Data;
    private $Campanha_sms_id;
    private $Tiporamal;
    private $Complemento;
    private $Erro;
    private $Result;
    private $Conta;

    //Nome da tabela no banco de dados.
    const Tabela = "campanha_sms";

    /**
     * Metodo inicial, responsavel por executar os dados para ramal iax   
     */
    public function ExeCreate(array $data) {
        $this->Data = $data;        
        
        $this->setData();
        $this->setNome();
        if (!$this->Result):
            $this->Result = TRUE;
            $this->Erro = array("Opa, você tentou cadastrar uma Campanha sms que já esta cadastrado no sitema!", KL_ALERT);
        else:            
            $this->Create();            
        endif;
    }

    /**
     * Metodo responsagem por realizar alterações no ramal iax     
     */
    public function ExeUpdate($campanha_sms_id, $data) {
        $this->Campanha_sms_id = (int) $campanha_sms_id;
        $this->Data = $data;
        
        $this->setData();
        $this->Update();
    }

    /** Class resposavel por apagar ramal iax */
    public function ExeDelete($campanha_sms_id) {
        $this->Campanha_sms_id = (int) $campanha_sms_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE campanha_sms_id = :id", "id={$this->Campanha_sms_id}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover uma Campanha Sms que não existe no sistema!", KL_INFOR);
        else:
            $this->Delete();
        endif;
    }

    /** Retorna o resultado  */
    public function getResult() {
        return $this->Result;
    }

    /** Retorna o erro  */
    public function getErro() {
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
        
        $campanha_tipo = $this->Data['campanha_sms_tipo'];  
        $campanha_nome = $this->Data['campanha_sms_nome'];        
        $campanha_data_inicio = $this->Data['campanha_sms_data_inicio'];
        $campanha_agenda = $this->Data['campanha_sms_agenda'];
        $campanha_status = $this->Data['campanha_sms_status'];

        unset(
        $this->Data['campanha_sms_tipo'], 
        $this->Data['campanha_sms_nome'], 
        $this->Data['campanha_sms_data_inicio'], 
        $this->Data['campanha_sms_agenda'], 
        $this->Data['campanha_sms_status']
        );
        
        $this->Data['campanha_sms_tipo'] = $campanha_tipo;
        $this->Data['campanha_sms_nome'] = $campanha_nome;        
        $this->Data['campanha_sms_data_inicio'] = $campanha_data_inicio;       
        $this->Data['campanha_sms_agenda'] = $campanha_agenda;
        $this->Data['campanha_sms_status'] = $campanha_status;
        
        
    }

    /** Verifica a existencia de alguma duplicação. */
    private function setNome() {
        $Where = (!empty($this->Campanha_sms_id) ? "campanha_sms_id != {$this->Campanha_sms_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} campanha_sms_nome = :a", "a={$this->Data['campanha_sms_nome']}");

        if ($readName->getResult()):
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
            $this->Erro = array("<b>Sucesso:</b> A Campanha Sms {$this->Data['campanha_sms_nome']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE campanha_sms_id = :id", "id=$this->Campanha_sms_id");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> A Campanha Sms {$this->Data['campanha_sms_nome']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE campanha_sms_id = :id", "id={$this->Campanha_sms_id}");

        if ($deletar->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, Campanha Sms foi excluido do sistema!", KL_ACCEPT);
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel excluir a Campanha Sms do sistema!", KL_ERROR);
        endif;
    }

}

// close Clientes