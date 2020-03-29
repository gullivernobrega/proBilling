<?php

/**
 * News.class [ MODEL ]
 * Classe responsavel por fazer toda manutenção de Noticias;
 * @copyright (c) 16/05/2017, Kleber de Souza KLSDESIGNER
 */
class News {

    private $Data;
    private $Not_id;
    private $Cat_id;
    private $Erro;
    private $Result;

    //Nome da tabela no banco de dados.
    const Tabela = "kl_noticia";

    /**
     * Metodo responsavel por criar as categorias e subcategorias    
     */
    public function ExeCreate($data) {
        $this->Data = $data;

        //Verifica se existe campos em branco
        if (in_array('', $this->Data)):
            $this->Result = false;
            $this->Erro = array('<b>Erro ao Cadastrar:</b> Para cadastrar uma noticia preencha todos os campos!', KL_ALERT);
        else:
            $this->setData();
            $this->setNome();
            if (!$this->Result):
                $this->Result = TRUE;
                $this->Erro = array("Opa, você tentou cadastrar uma Noticia existente no sitema. Verifique!", KL_ERROR);
            else:
                $this->Create();
            endif;
        endif;
    }

    /**
     * Metodo responsagem por realizar alterações nas categorias e subcategorias     
     */
    public function ExeUpdate($not_id, $data) {
        $this->Not_id = $not_id;
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
    public function ExeDelete($not_id) {
        $this->Not_id = $not_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE not_id = :id", "id={$this->Not_id}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover uma Noticia que não existe no sistema!", KL_INFOR);
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
        $cat_id = (int) $this->Data[cat_id];
        
        unset($this->Data['cat_id']);
        
        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

       
        $this->Data['cat_id'] = $cat_id;
        
    }


    /** Verifica a existencia de alguma categoria. */
    private function setNome() {
        $Where = (!empty($this->not_id) ? "not_id != {$this->not_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} not_titulo = :n", "n={$this->Data['not_titulo']}");

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
            $this->Erro = array("<b>Sucesso:</b> A Noticia {$this->Data['not_titulo']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE not_id = :id", "id=$this->Not_id");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> A noticia {$this->Data['not_titulo']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE not_id = :id", "id={$this->Not_id}");

        if ($deletar->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, a noticia foi removido do sistema!", KL_ACCEPT);
        //header("Location: painel.php?exe=paginas/lista");
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel remover a noticia do sistema!", KL_ERROR);
        endif;
    }

}
