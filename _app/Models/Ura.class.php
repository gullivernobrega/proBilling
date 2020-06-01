<?php

/**
 * Campanha.class [ MODEL ]
 * Classe responsavel por realizar cadastro, alteração e listagem de dados da Campanha. 
 * @copyright (c) 09/05/2018, Kleber de Souza BRAZISTELECOM
 */
class Ura {

    private $Data;
    private $Ura_id;
    private $Tiporamal;
    private $Complemento;
    private $Erro;
    private $Result;
    private $Conta;

    //Nome da tabela no banco de dados.
    const Tabela = "ura";

    /**
     * Metodo inicial, responsavel por executar os dados para ramal iax   
     */
    public function ExeCreate(array $data) {
        $this->Data = $data;

        $this->setData();
        $this->setNome();
        if (!$this->Result):
            $this->Result = TRUE;
            $this->Erro = array("Opa, você tentou cadastrar uma URA que já esta cadastrado no sitema!", KL_ALERT);
        else:
            $this->Create();
        endif;
    }

    /**
     * Metodo responsagem por realizar alterações no ramal iax     
     */
    public function ExeUpdate($ura_id, $data) {
        $this->Ura_id = (int) $ura_id;
        $this->Data = $data;

        $this->setData();
        $this->Update();
    }

    /** Class resposavel por apagar ramal iax */
    public function ExeDelete($ura_id) {
        $this->Ura_id = (int) $ura_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE ura_id = :id", "id={$this->Ura_id}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover uma URA que não existe no sistema!", KL_INFOR);
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

        $ura_nome = $this->Data['ura_nome'];
        $ura_audio = $this->Data['ura_audio'];
        $ura_op_1= $this->Data['ura_op_1'];
        $ura_op_2= $this->Data['ura_op_2'];
        $ura_op_3 = $this->Data['ura_op_3'];
        $ura_op_4 = $this->Data['ura_op_4'];
        $ura_op_5 = $this->Data['ura_op_5'];
        $ura_op_6 = $this->Data['ura_op_6'];
        $ura_op_7 = $this->Data['ura_op_7'];
        $ura_op_8 = $this->Data['ura_op_8'];
        $ura_op_9 = $this->Data['ura_op_9'];
        $ura_timeout = $this->Data['ura_timeout'];
        unset($this->Data);

        $this->Data['ura_nome'] = $ura_nome;
        $this->Data['ura_audio'] = $ura_audio;
            
        $this->Data['op_1'] = $ura_op_1;
        $this->Data['op_2'] = $ura_op_2;
        $this->Data['op_3'] = $ura_op_3;
        $this->Data['op_4'] = $ura_op_4;
        $this->Data['op_5'] = $ura_op_5;
        $this->Data['op_6'] = $ura_op_6;
        $this->Data['op_7'] = $ura_op_7;
        $this->Data['op_8'] = $ura_op_8;
        $this->Data['op_9'] = $ura_op_9;
        
        $this->Data['op_t'] = $ura_timeout;
        
       
    }

    /** Verifica a existencia de alguma duplicação. */
    private function setNome() {
        $Where = (!empty($this->Ura_id) ? "ura_id != {$this->Ura_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} ura_nome = :a", "a={$this->Data['ura_nome']}");

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
            $this->Erro = array("<b>Sucesso:</b> A URA {$this->Data['ura_nome']} foi cadastrado no sistema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE ura_id = :id", "id=$this->Ura_id");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> A URA {$this->Data['ura_nome']} foi alterado no sistema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE ura_id = :id", "id={$this->Ura_id}");

        if ($deletar->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, Campanha foi excluido do sistema!", KL_ACCEPT);
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel excluir a Campanha do sistema!", KL_ERROR);
        endif;
    }

}

// close Clientes