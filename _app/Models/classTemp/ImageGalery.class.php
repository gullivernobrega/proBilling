<?php

/**
 * ImageGalery.class [ TIPO ]
 * Classe responsavel por cadastrar e fazer o upload das imagens da Galeria
 * @copyright (c) 27/042017, Kleber de Souza BRAZISTELECOM
 */
class ImageGalery {

    private $Data;
    private $DataImg;
    private $Galeria_id;
    private $NomeGallery;
    private $Img_id;
    private $NomeImagem;
    private $Extensao;
    private $Erro;
    private $Result;

    //Nome da tabela no banco de dados.
    const Tabela = "kl_imagem";

    /**
     * Metodo responsavel por criar as imgens da galeria.
     */
    public function ExeCreate($galeria_id, array $imagem) {
        $this->Galeria_id = (int) $galeria_id;
        $this->Data = $imagem;

        //Faz a leitura da Galeria
        $readGa = new Read;
        $readGa->ExeRead("kl_galeria", "WHERE galeria_id = :id", "id={$this->Galeria_id}");

        if (!$readGa->getResult()):
            $this->Erro = array("ERRO, O índice {$this->Galeria_id} não foi encontrado. Verifique!", KL_ERROR);
        else:
            $res = $readGa->getResult();
            $this->NomeGallery = Check::Name($res[0]['galeria_nome']);

            $imgImagem = array();
            $imgCount = count($this->Data['tmp_name']);
            $imgKeys = array_keys($this->Data);

            //loop por todas imagens
            for ($img = 0; $img < $imgCount; $img++):
                //loop por cada indice das imagens
                foreach ($imgKeys as $keys):
                    $imgFiles[$img][$keys] = $this->Data[$keys][$img];
                endforeach;
            endfor;

            //chamada da classe upload
            $uploadImg = new Upload('../themes/americoadvogados/images');
            $i = 0;
            $u = 0;

            //loop para upload das imagem
            foreach ($imgFiles as $imgUpload):
                $tipo = $imgUpload['type'];
                //Verifica a extensão
                if ($tipo == "image/png"):
                    $this->Extensao = ".png";
                else:
                    $this->Extensao = ".jpg";
                endif;

                $i++;
                $this->NomeImagem = "{$this->NomeGallery}-{$this->Galeria_id}-" . (substr(md5(time() + $i), 0, 5));

                //faz o upload
                $uploadImg->Image($imgUpload, $this->NomeImagem, "", "/galeria");

                if ($uploadImg->getResult()):
                    //prepara os dados
                    $this->DataImg['galeria_id'] = $this->Galeria_id;
                    $this->DataImg['img_dir'] = "images/galeria/";
                    $this->DataImg['img_nome'] = $this->NomeImagem;
                    $this->DataImg['img_imagem'] = $this->NomeImagem . $this->Extensao;
                    $this->DataImg['img_status'] = "S";

                    //grava no banco
                    $this->Create();

                    //incrementa as imagens enviadas
                    $u++;
                endif;
            endforeach;

            //Faz a verificação do total de imagens enviado e exibe a menssagem.
            if ($u > 1):
                $this->Erro = array("Foram enviado com sucesso: {$u} imagens para a galeria, {$this->NomeGallery}.", KL_ACCEPT);
                $this->Result = true;
            endif;

        endif;
    }

    /**
     * Metodo responsagem por realizar alterações nas categorias e subcategorias     
     */
    public function ExeUpdate($img_id, $data) {
        $this->Img_id = (int) $img_id;
        $this->DataImg = $data;
        
        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE img_id = :id", "id={$this->Img_id}");
        $Img = $read->getResult();
        extract($Img[0]);
        
        $this->Galeria_id = $galeria_id;
        $this->Update();
    }

    /** Class resposavel por apagar itens da pagina Cms */
    public function ExeDelete($img_id) {
        $this->Img_id = (int) $img_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE img_id = :id", "id={$this->Img_id}");

        //Verifico se existe a galeria
        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover uma imagem que não existe no sistema!", KL_INFOR);
        else:

            $Img = $read->getResult();
            extract($Img[0]);

            $dir = "../" . REQUIRE_PATH . "/";

            if (!empty($img_imagem) && file_exists($dir . $img_dir . $img_imagem)):
                unlink($dir . $img_dir . $img_imagem);
                $this->Galeria_id = $galeria_id;
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

//        $this->DataImg['galeria_id'] = $this->Galeria_id;
//        $this->DataImg['img_dir'] = "images/galeria/";
//        $this->DataImg['img_name'] = $this->NomeImagem;
//        $this->DataImg['img_name'] = $this->NomeImagem . $this->Extensao;
//        $this->DataImg['img_status'] = "S";
//        $this->DataImg['img_ordem'] = $i;
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
        $create->ExeCreate(self::Tabela, $this->DataImg);
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->DataImg, "WHERE img_id = :id", "id=$this->Img_id");

        if ($update->getResult()):
            header("Location: painel.php?exe=galerias/imagens&galeria_id={$this->Galeria_id}");
        else:
            $this->Result = $update->getResult();
            $this->Erro = array("Erro, Não foi possivel alterar o status da imagem!", KL_ERROR);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE img_id = :id", "id={$this->Img_id}");

        if ($deletar->getResult()):
            //$this->Result = false;
            //$this->Erro = array("Erro, Não foi possivel apagar os dados do sistema!", KL_ERROR);
            header("Location: painel.php?exe=galerias/imagens&galeria_id={$this->Galeria_id}");
        else:

        endif;
    }

}
