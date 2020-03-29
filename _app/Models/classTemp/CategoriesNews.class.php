<?php

/**
 * CategoriesNews.class [ MODEL ]
 * Classe responsavel por fazer toda manutenção de categorias das (Noticias);
 * @copyright (c) 16/05/2017, Kleber de Souza KLSDESIGNER
 */
class CategoriesNews {

    private $Data;
    private $Cat_id;
    private $Erro;
    private $Result;

    //Nome da tabela no banco de dados.
    const Tabela = "kl_noticia_categoria";

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

            if (!$this->Result):
                $this->Result = TRUE;
                $this->Erro = array("Opa, você tentou cadastrar uma Categoria Noticia já existente no sitema. Verifique!", KL_ERROR);
            else:
                $this->Create();
            endif;

        endif;
    }

    /**
     * Metodo responsagem por realizar alterações nas categorias e subcategorias     
     */
    public function ExeUpdate($cat_id, $data) {
        $this->Cat_id = $cat_id;
        $this->Data = $data;

        $this->setData();
        $this->setNome();
        if (!$this->Result):
            $this->Result = TRUE;
            $this->Erro = array("Opa, você tentou alterar uma Categoria Noticia já existente no sitema. Verifique!", KL_ERROR);
        else:
            $this->Update();
        endif;
    }

    /**
     * Metodo responsavel por realizar a exclusão de categorias
     */
    public function ExeDelete($cat_id) {
        $this->Cat_id = $cat_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE cat_id = :id", "id={$this->Cat_id}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover uma Categoria Noticia que não existe no sistema!", KL_INFOR);
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

        //$this->Data['categoria_nome'] = Check::Name($this->Data['categoria_title']);
        //$this->Data['categoria_parente'] = ($this->Data['categoria_parente'] == 'null' ? 0 : $this->Data['categoria_parente']);
    }

    /** Verifica a existencia de alguma categoria. */
    private function setNome() {
        $Where = (!empty($this->Cat_id) ? "cat_id != {$this->Cat_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} cat_descricao = :d", "d={$this->Data['cat_descricao']}");

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
            $this->Erro = array("<b>Sucesso:</b> A categoria noticia: {$this->Data['cat_descricao']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE cat_id = :id", "id=$this->Cat_id");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> A categoria noticia: {$this->Data['cat_descricao']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE cat_id = :id", "id={$this->Cat_id}");

        if ($deletar->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, a Categoria Noticia foi removido do sistema!", KL_ACCEPT);
        //header("Location: painel.php?exe=paginas/lista");
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel remover a categoria do sistema!", KL_ERROR);
        endif;
    }

}
