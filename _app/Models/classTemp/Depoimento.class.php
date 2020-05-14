<?php

/**
 * Depoimento.class [ MODEL ]
 * Classe responsavel por fazer toda manutenção dos depoimentos dos clientes;
 * @copyright (c) 18/10/2016, Kleber de Souza KLSDESIGNER
 */
class Depoimento {

    private $Data;
    private $Dep_id;
    private $Erro;
    private $Result;

    //Nome da tabela no banco de dados.
    const Tabela = "kl_depoimento";

    /**
     * Metodo responsavel por criar as categorias e subcategorias    
     */
    public function ExeCreate($data) {
        $this->Data = $data;

        //Verifica se existe campos em branco
        if (in_array('', $this->Data)):
            $this->Result = false;
            $this->Erro = array('<b>Erro ao Cadastrar:</b> Para cadastrar um depoimento preencha todos os campos!', KL_ALERT);
        else:
            $this->setData();
            $this->setNome();
            $this->Create();
        endif;
    }

    /**
     * Metodo responsagem por realizar alterações nas categorias e subcategorias     
     */
    public function ExeUpdate($depoimento_id, $data) {
        $this->Dep_id = $depoimento_id;
        $this->Data = $data;

        //Verifica se existe campos em branco
//        if (in_array('', $this->Data)):
//            $this->Result = false;
//            $this->Erro = array("<b>Erro ao Atualizar:</b> Para atualizar a categoria {$this->Data['categoria_nome']}, preencha todos os campos!", KL_ALERT);
//        else:
        $this->setData();
        $this->setNome();
        $this->Update();
        //endif;
    }

    /**
     * Metodo responsavel por realizar a exclusão de categorias
     */
    public function ExeDelete($depoimento_id) {
        $this->Dep_id = $depoimento_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE dep_id = :id", "id={$this->Dep_id}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover um depoimento que não existe no sistema!", KL_INFOR);
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
        $depoimento = $this->Data['dep_depoimento'];

        unset($this->Data['dep_depoimento']);

        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

        //Reoganiza o array Dados
        $this->Data['dep_depoimento'] = $depoimento;
    }

    /** Verifica a existencia de alguma Depoimento. */
    private function setNome() {
        $Where = (!empty($this->Dep_id) ? "dep_id != {$this->Dep_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} dep_email = :n", "n={$this->Data['dep_email']}");

        if ($readName->getResult()):
            $this->Data['dep_email'] = $this->Data['dep_email'] . '-' . $readName->getRowCount();
        endif;
    }

    /** Execulta a criação dos dados */
    private function Create() {
        $create = new Create;
        $create->ExeCreate(self::Tabela, $this->Data);

        if ($create->getResult()):
            $this->Result = $create->getResult();
            $this->Erro = array("<b>Sucesso:</b> O Depoimento {$this->Data['dep_nome']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE dep_id = :id", "id=$this->Dep_id");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> O Depoimento {$this->Data['dep_nome']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE dep_id = :id", "id={$this->Dep_id}");

        if ($deletar->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, O depoimento foi removido do sistema!", KL_ACCEPT);
        //header("Location: painel.php?exe=paginas/lista");
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel remover o depoimento do sistema!", KL_ERROR);
        endif;
    }

}
