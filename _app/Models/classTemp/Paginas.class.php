<?php

/**
 * paginas.class [ MODEL ]
 * Classe responsavel por realizar o cadastro do conteúdo das Paginas CMS
 * @copyright (c) 19/12/2016, Kleber de Souza BRAZISTELECOM
 */
class Paginas {

    private $Data;
    private $Cms_id;
    private $nomeImagem;
    private $Erro;
    private $Result;

    //Nome da tabela no banco de dados.
    const Tabela = "kl_cms";

    /**
     * Metodo responsavel por criar as páginas cms do site   
     */
    public function ExeCreate(array $data) {
        $this->Data = $data;

        $this->setData();
        $this->setNome();

        if ($this->Data['cms_imagem']):
            $upImage = new Upload('../themes/americoadvogados/images');
            $upImage->Image($this->Data['cms_imagem'], $this->nomeImagem, 1200, "/cms");
        endif;

        if (isset($upImage) && $upImage->getResult()):
            $this->Data['cms_imagem'] = $upImage->getResult();
            $this->Create();
        else:
            $this->Data['cms_imagem'] = null;
            $this->Create();
        endif;
    }

    /**
     * Metodo responsagem por realizar alterações nas categorias e subcategorias     
     */
    public function ExeUpdate($cms_id, $data) {
        $this->Cms_id = $cms_id;
        $this->Data = $data;

        $this->setData();
        $this->setNome();

        if ($this->Data['cms_imagem']):
            $upImage = new Upload('../themes/americoadvogados/images');
            $upImage->Image($this->Data['cms_imagem'], $this->nomeImagem, 1200, "/cms");
        endif;

        if (isset($upImage) && $upImage->getResult()):
            $this->Data['cms_imagem'] = $upImage->getResult();
            $this->Update();
        else:
            //$this->Data['cms_imagem'] = null;
            unset($this->Data['cms_imagem']);
            $this->Update();
        endif;
    }

    /** Class resposavel por apagar itens da pagina Cms */
    public function ExeDelete($cms_id) {
        $this->Cms_id = (int) $cms_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE cms_id = :id", "id={$this->Cms_id}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover uma pagina que não existe no sistema!", KL_INFOR);
        else:
            //Verifica se existe galerias para a pagina.
            $readGaleria = new Read;
            $readGaleria->ExeRead("kl_galeria", "WHERE cms_id = :c", "c={$this->Cms_id}");

            if ($readGaleria->getRowCount() > 0):
                $this->Result = true;
                $this->Erro = array("Existe Galeria para esta pagina, apague a galeria e todos as suas imagens ante!", KL_INFOR);
            else:
                $ObjImg = $read->getResult();
                extract($ObjImg[0]);

                $dir = "../" . REQUIRE_PATH . "/images";

                if (!empty($cms_imagem) && file_exists($dir . $cms_imagem)):
                    unlink($dir . $cms_imagem);
                    $this->Delete();
                else:
                    $this->Delete();
                endif;
            endif;
            
        endif;
    }

    /** Class resposavel por apagar a imagem da pagina Cms */
    public function ExeDeleteImg($cms_id) {
        $this->Cms_id = (int) $cms_id;
        
        //Diretorio da Imagem
        $dir = "../" . REQUIRE_PATH . "/images";

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE cms_id = :id", "id={$this->Cms_id}");
        $ObjImg = $read->getResult();
        extract($ObjImg[0]);

        if (!empty($cms_imagem) && file_exists($dir . $cms_imagem)):
            unlink($dir . $cms_imagem);
            $DataImg['cms_imagem'] = "";

            $update = new Update;
            $update->ExeUpdate(self::Tabela, $DataImg, "WHERE cms_id = :id", "id={$this->Cms_id}");

            if ($update->getResult()):
                $this->Result = $update->getResult();                
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
        $cms_conteudo = $this->Data['cms_conteudo'];
        $cms_imagem = $this->Data['cms_imagem'];
        $cms_title = Check::Name($this->Data['cms_nome']);

        unset($this->Data['cms_conteudo'], $this->Data['cms_imagem']);

        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

        $this->nomeImagem = Check::Name($this->Data['cms_nome']);

        // repassa os dados
        $this->Data['cms_title'] = $cms_title;
        $this->Data['cms_conteudo'] = $cms_conteudo;
        $this->Data['cms_imagem'] = $cms_imagem;
    }

    /** Prepara os dados para Update  INATIVO */
    private function setDataUpdate() {
        $cms_conteudo = $this->Data['cms_conteudo'];
        $cms_imagem = $this->Data['cms_imagem'];
        $cms_title = Check::Name($this->Data['cms_nome']);

        unset($this->Data['cms_conteudo'], $this->Data['cms_imagem']);

        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

        $this->nomeImagem = Check::Name($this->Data['cms_nome']);

        // repassa os dados
        $this->Data['cms_title'] = $cms_title;
        $this->Data['cms_conteudo'] = $cms_conteudo;
        $this->Data['cms_imagem'] = $cms_imagem;
    }

    /** Verifica a existencia de alguma categoria. */
    private function setNome() {
        $Where = (!empty($this->Cms_id) ? "cms_id != {$this->Cms_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} cms_nome = :c", "c={$this->Data['cms_nome']}");

        if ($readName->getResult()):
            $this->Data['cms_nome'] = $this->Data['cms_nome'] . '-' . $readName->getRowCount();
        endif;
    }

    /** Execulta a criação dos dados */
    private function Create() {
        $create = new Create;
        $create->ExeCreate(self::Tabela, $this->Data);

        if ($create->getResult()):
            $this->Result = $create->getResult();
            $this->Erro = array("<b>Sucesso:</b> A Pagina {$this->Data['cms_nome']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE cms_id = :id", "id={$this->Cms_id}");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> A pagine {$this->Data['cms_nome']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE cms_id = :id", "id={$this->Cms_id}");

        if ($deletar->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, seu arquivo foi apagado do sistema!", KL_ACCEPT);
        //header("Location: painel.php?exe=paginas/lista");
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel apagar os dados do sistema!", KL_ERROR);
        endif;
    }

}
