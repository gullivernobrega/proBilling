<?php

/**
 * Banner.class [ MODEL ]
 * Classe responsavel por realizar o cadastro dos banners do site
 * @copyright (c) 19/12/2016, Kleber de Souza BRAZISTELECOM
 */
class Banner {

    private $Data;
    private $Ban_id;
    private $NomeImagem;
    private $Erro;
    private $Result;

    //Nome da tabela no banco de dados.
    const Tabela = "kl_banner";

    /**
     * Metodo responsavel por criar as páginas cms do site   
     */
    public function ExeCreate(array $data) {
        $this->Data = $data;

        $this->setData();
        $this->setNome();

        if ($this->Data['ban_image']):
            $upImage = new Upload('../themes/americoadvogados/images');
            $upImage->Image($this->Data['ban_image'], $this->NomeImagem, 1343, "/banner");
        endif;

        if (isset($upImage) && $upImage->getResult()):
            $this->Data['ban_image'] = $upImage->getResult();
            $this->Create();
        else:
            $this->Data['ban_image'] = null;
            $this->Create();
        endif;
    }

    /**
     * Metodo responsagem por realizar alterações nas categorias e subcategorias     
     */
    public function ExeUpdate($ban_id, $data) {
        $this->Ban_id = $ban_id;
        $this->Data = $data;

        $this->setData();
        $this->setNome();

        /*         * Verifica se existe campos em branco
          if (in_array('', $this->Data)):
          $this->Result = false;
          $this->Erro = array("<b>Erro ao Atualizar:</b> Para atualizar a categoria {$this->Data['categoria_nome']}, preencha todos os campos!", KL_ALERT);
          else: */

        if ($this->Data['ban_image']):
            $upImage = new Upload('../themes/americoadvogados/images');
            $upImage->Image($this->Data['ban_image'], $this->NomeImagem, 1343, "/banner");
        endif;

        if (isset($upImage) && $upImage->getResult()):
            $this->Data['ban_image'] = $upImage->getResult();
            $this->Update();
        else:
            //$this->Data['cms_imagem'] = null;
            unset($this->Data['ban_image']);
            $this->Update();
        endif;
        //endif;
    }

    /** Class resposavel por apagar itens da pagina Cms */
    public function ExeDelete($ban_id) {
        $this->Ban_id = $ban_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE ban_id = :id", "id={$this->Ban_id}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover um banner que não existe no sistema!", KL_INFOR);
        else:
            $ObjImg = $read->getResult();
            extract($ObjImg[0]);

            $dir = "../" . REQUIRE_PATH . "/images";

            if (!empty($ban_image) && file_exists($dir . $ban_image)):
                unlink($dir . $ban_image);
                $this->Delete();
            else:
                $this->Delete();
            endif;
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
        $ban_image = $this->Data['ban_image'];        

        unset($this->Data['ban_image']);

        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

        $this->NomeImagem = Check::Name($this->Data['ban_titulo']);

        // repassa os dados
        $this->Data['ban_image'] = $ban_image;
    }

    /** Verifica a existencia de alguma categoria. */
    private function setNome() {
        $Where = (!empty($this->Ban_id) ? "ban_id != {$this->Ban_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} ban_titulo = :b", "b={$this->Data['ban_titulo']}");

        if ($readName->getResult()):
            $this->Data['ban_titulo'] = $this->Data['ban_titulo'] . '-' . $readName->getRowCount();
        endif;
    }

    /** Execulta a criação dos dados */
    private function Create() {
        $create = new Create;
        $create->ExeCreate(self::Tabela, $this->Data);

        if ($create->getResult()):
            $this->Result = $create->getResult();
            $this->Erro = array("<b>Sucesso:</b> O Banner {$this->Data['ban_titulo']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE ban_id = :id", "id={$this->Ban_id}");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> O banner {$this->Data['ban_titulo']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE ban_id = :id", "id={$this->Ban_id}");

        if ($deletar->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, banner apagado com sucesso!", KL_ACCEPT);
        //header("Location: painel.php?exe=paginas/lista");
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel apagar o Banner do sistema!", KL_ERROR);
        endif;
    }
}
