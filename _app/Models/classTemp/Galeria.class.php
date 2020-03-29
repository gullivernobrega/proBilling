<?php

/**
 * galeria.class [ MODEL ]
 * Classe responsavel por realizar o cadastro do conteúdo das Galerias
 * @copyright (c) 18/01/2017, Kleber de Souza BRAZISTELECOM
 */
class Galeria {

    private $Data;
    private $Galeria_id;
    private $nomeImagem;
    private $Erro;
    private $Result;

    //Nome da tabela no banco de dados.
    const Tabela = "kl_galeria";

    /**
     * Metodo responsavel por criar as páginas cms do site   
     */
    public function ExeCreate(array $data) {
        $this->Data = $data;

        $this->setData();
        $this->setNome();

        if ($this->Data['galeria_img_capa']):
            $upImage = new Upload('../themes/americoadvogados/images');
            $upImage->Image($this->Data['galeria_img_capa'], $this->nomeImagem, 1024, "/galeria");
        endif;

        if (isset($upImage) && $upImage->getResult()):
            $this->Data['galeria_img_capa'] = $upImage->getResult();
            $this->Create();
        else:
            $this->Data['galeria_img_capa'] = null;
            $this->Create();
        endif;
    }

    /**
     * Metodo responsagem por realizar alterações nas categorias e subcategorias     
     */
    public function ExeUpdate($galeria_id, $data) {
        $this->Galeria_id = $galeria_id;
        $this->Data = $data;

        $this->setData();
        $this->setNome();

        if ($this->Data['galeria_img_capa']):
            $upImage = new Upload('../themes/americoadvogados/images');
            $upImage->Image($this->Data['galeria_img_capa'], $this->nomeImagem, 1024, "/galeria");
        endif;

        if (isset($upImage) && $upImage->getResult()):
            $this->Data['galeria_img_capa'] = $upImage->getResult();
            $this->Update();
        else:
            //$this->Data['galeria_img_capa'] = null;
            unset($this->Data['galeria_img_capa']);
            $this->Update();
        endif;
    }

    /** Class resposavel por apagar itens da pagina Cms */
    public function ExeDelete($galeria_id) {
        $this->Galeria_id = (int) $galeria_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE galeria_id = :id", "id={$this->Galeria_id}");

        //Verifico se existe a galeria
        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover uma galeria que não existe no sistema!", KL_INFOR);
        else:

            $readImg = new Read;
            $readImg->ExeRead("kl_imagem", "WHERE galeria_id = :g", "g={$this->Galeria_id}");

            // Verifica se existe alguma imagem na galeria $readImg->getRowCount() > 0
            if ($readImg->getRowCount() > 0):

                $dir = "../" . REQUIRE_PATH . "/";

                //echo "tem Imagem";
                foreach ($readImg->getResult() as $imgs):
                    extract($imgs);
                    //Verifica se existe imagens e apaga
                    if (!empty($img_imagem) && file_exists($dir . $img_dir . $img_imagem)):
                        unlink($dir . $img_dir . $img_imagem);
                        $this->DeleteImg();
                    endif;
                endforeach;
            endif;

            //Verifico se existe imagem capa e apaga
            $imgCapa = $read->getResult();
            extract($imgCapa[0]);

            $dirCapa = "../" . REQUIRE_PATH . "/images/";
           
            if (!empty($galeria_img_capa) && file_exists($dirCapa . $galeria_img_capa)):
                unlink($dirCapa . $galeria_img_capa);                
                $this->Delete();
            else:
                $this->Delete();
            endif;

        endif;
    }

    /** Class resposavel por apagar a imagem capa da Galeria */
    public function ExeDeleteImg($galeria_id) {
        $this->Galeria_id = (int) $galeria_id;

        //Diretorio da Imagem
        $dir = "../" . REQUIRE_PATH . "/images/";

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE galeria_id = :id", "id={$this->Galeria_id}");
        $ObjImg = $read->getResult();
        extract($ObjImg[0]);

        if (!empty($galeria_img_capa) && file_exists($dir . $galeria_img_capa)):
            unlink($dir . $galeria_img_capa);
            $DataImg['galeria_img_capa'] = "";

            $update = new Update;
            $update->ExeUpdate(self::Tabela, $DataImg, "WHERE galeria_id = :id", "id={$this->Galeria_id}");

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
        $galeria_img_capa = $this->Data['galeria_img_capa'];

        unset($this->Data['galeria_img_capa']);

        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

        $this->nomeImagem = Check::Name($this->Data['galeria_nome']);

        // repassa os dados
        $this->Data['galeria_img_capa'] = $galeria_img_capa;
    }

    /** Verifica a existencia de alguma categoria. */
    private function setNome() {
        $Where = (!empty($this->Galeria_id) ? "galeria_id != {$this->Galeria_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} galeria_nome = :c", "c={$this->Data['galeria_nome']}");

        if ($readName->getResult()):
            $this->Data['galeria_nome'] = $this->Data['galeria_nome'] . '-' . $readName->getRowCount();
        endif;
    }

    /** Execulta a criação dos dados */
    private function Create() {
        $create = new Create;
        $create->ExeCreate(self::Tabela, $this->Data);

        if ($create->getResult()):
            $this->Result = $create->getResult();
            $this->Erro = array("<b>Sucesso:</b> A Galeria {$this->Data['galeria_nome']} foi cadastrada no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE galeria_id = :id", "id=$this->Galeria_id");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> A galeria {$this->Data['galeria_nome']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE galeria_id = :id", "id={$this->Galeria_id}");

        if ($deletar->getResult()):
            $this->Result = true;
            //$this->Erro = array("Sucesso, seu arquivo foi apagado do sistema!", KL_ACCEPT);
            header("Location: painel.php?exe=galerias/lista");
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel apagar os dados do sistema!", KL_ERROR);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function DeleteImg() {
        $deletarImg = new Delete();
        $deletarImg->ExeDelete("kl_imagem", "WHERE galeria_id = :idImg", "idImg={$this->Galeria_id}");

        if ($deletarImg->getResult()):
            $this->Result = true;


        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel apagar os dados do sistema!", KL_ERROR);
        endif;
    }

}
