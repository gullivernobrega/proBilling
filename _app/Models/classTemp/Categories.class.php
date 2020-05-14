<?php

/**
 * Categories.class [ MODEL ]
 * Classe responsavel por fazer toda manutenção de categorias (menu e submenu);
 * @copyright (c) 18/10/2016, Kleber de Souza KLSDESIGNER
 */
class Categories {

    private $Data;
    private $Categoria_id;
    private $Erro;
    private $Result;

    //Nome da tabela no banco de dados.
    const Tabela = "kl_categorias";

    /**
     * Metodo responsavel por criar as categorias e subcategorias    
     */
    public function ExeCreate($data) {
        $this->Data = $data;

        //Verifica se existe campos em branco
        if (in_array('', $this->Data)):
            $this->Result = false;
            $this->Erro = array('<b>Erro ao Cadastrar:</b> Para cadastrar uma categoria preencha todos os campos!', KL_ALERT);
        else:
            $this->setData();
            $this->setNome();
            $this->Create();
        endif;
    }
    
    /**
     * Metodo responsagem por realizar alterações nas categorias e subcategorias     
     */
    public function ExeUpdate($categoria_id, $data) {
        $this->Categoria_id = $categoria_id;
        $this->Data = $data;        
        
        //Verifica se existe campos em branco
        if (in_array('', $this->Data)):
            $this->Result = false;
            $this->Erro = array("<b>Erro ao Atualizar:</b> Para atualizar a categoria {$this->Data['categoria_nome']}, preencha todos os campos!", KL_ALERT);
        else:
            $this->setData();
            $this->setNome();
            $this->Update();
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

    /** Prepara os dados */
    private function setData() {
        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

        //$this->Data['categoria_nome'] = Check::Name($this->Data['categoria_title']);
        $this->Data['categoria_parente'] = ($this->Data['categoria_parente'] == 'null' ? null : $this->Data['categoria_parente']);

        //Recebe os Dados        
        $categoria_parente = $this->Data['categoria_parente'];
        $categoria_nome = $this->Data['categoria_nome'];
        $categoria_data = $this->Data['categoria_data'];
        $categoria_ordem = $this->Data['categoria_ordem'];
        $categoria_status = $this->Data['categoria_status'];
        //Reoganiza o array Dados
        $this->Data['categoria_parente'] = $categoria_parente;
        $this->Data['categoria_nome'] = $categoria_nome;
        $this->Data['categoria_data'] = $categoria_data;
        $this->Data['categoria_ordem'] = $categoria_ordem;
        $this->Data['categoria_status'] = $categoria_status;
        
    }

    /** Verifica a existencia de alguma categoria. */
    private function setNome() {
        $Where = (!empty($this->Categoria_id) ? "categoria_id := {$this->Categoria_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} categoria_nome = :n", "n={$this->Data['categoria_nome']}");

        if ($readName->getResult()):
            $this->Data['categoria_nome'] = $this->Data['categoria_nome'] . '-' . $readName->getRowCount();
        endif;
    }

    /** Execulta a criação dos dados */
    private function Create() {
        $create = new Create;
        $create->ExeCreate(self::Tabela, $this->Data);

        if ($create->getResult()):
            $this->Result = $create->getResult();
            $this->Erro = array("<b>Sucesso:</b> A categoria {$this->Data['categoria_nome']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }
    
    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, "WHERE categoria_id = $this->Categoria_id" ,$this->Data);

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> A categoria {$this->Data['categoria_nome']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

}
