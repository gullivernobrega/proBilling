<?php

/**
 * RedeSocial.class [ MODEL ]
 * Classe responsavel por realizar o cadastro e manutenção das Rede Sociais
 * @copyright (c) 19/12/2016, Kleber de Souza BRAZISTELECOM
 */
class RedeSocial {

    private $Data;
    private $Social_id;
    private $NomeImage;
    private $Erro;
    private $Result;

    //Nome da tabela no banco de dados.
    const Tabela = "kl_sociais";

    /**
     * Metodo responsavel por cadastrar as rede sociais do site   
     */
    public function ExeCreate(array $data) {
        $this->Data = $data;

        $this->setData();
        $this->setNome();

        if ($this->Data['social_image']):
            $upImage = new Upload('../themes/americoadvogados/images');
            $upImage->Image($this->Data['social_image'], $this->NomeImage, 128, "/icons");
        endif;

        if (isset($upImage) && $upImage->getResult()):
            $this->Data['social_image'] = $upImage->getResult();
            $this->Create();
        else:
            $this->Data['social_image'] = null;
            $this->Create();
        endif;
    }

    /**
     * Metodo responsagem por realizar alterações nas rede sociais    
     */
    public function ExeUpdate($social_id, $data) {
        $this->Social_id = $social_id;
        $this->Data = $data;
        
        $this->setData();
        // $this->setNome();

        /** Verifica se existe campos em branco
          if (in_array('', $this->Data)):
          $this->Result = false;
          $this->Erro = array("<b>Erro ao Atualizar:</b> Para atualizar a categoria {$this->Data['categoria_nome']}, preencha todos os campos!", KL_ALERT);
          else: */
        
        if ($this->Data['social_image']):
            $upImage = new Upload('../themes/americoadvogados/images');
            $upImage->Image($this->Data['social_image'], $this->NomeImage, 128, "/icons");
        endif;

        if (isset($upImage) && $upImage->getResult()):
            $this->Data['social_image'] = $upImage->getResult();
            $this->Update();
        else:
            //$this->Data['social_image'] = null;
            unset($this->Data['social_image']);        
            $this->Update();        
        endif;
        
        //endif;
    }

    /** Class resposavel por apagar itens da pagina Cms */
    public function ExeDelete($social_id) {
        $this->Social_id = (int) $social_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE social_id = :id", "id={$this->Social_id}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover uma rede social que não existe no sistema!", KL_INFOR);
        else:
            $ObjImg = $read->getResult();
            extract($ObjImg[0]);

            $dir = "../" . REQUIRE_PATH . "/images";

            if (!empty($social_image) && file_exists($dir . $social_image)):
                unlink($dir . $social_image);
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
        $social_url = $this->Data['social_url'];
        $social_image = $this->Data['social_image'];

        unset($this->Data['social_image']);

        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

        $this->NomeImage = Check::Name($this->Data['social_nome']);

        // repassa os dados        
        $this->Data['social_url'] = $social_url;
        $this->Data['social_image'] = $social_image;         
        
    }

    /** Verifica a existencia de alguma Rede social. */
    private function setNome() {
        $Where = (!empty($this->Social_id) ? "social_id != {$this->Social_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} social_nome = :rs", "rs={$this->Data['social_nome']}");

        if ($readName->getResult()):
            $this->Data['social_nome'] = $this->Data['social_nome'] . '-' . $readName->getRowCount();
        endif;
    }

    /** Execulta a criação dos dados */
    private function Create() {
        $create = new Create;
        $create->ExeCreate(self::Tabela, $this->Data);

        if ($create->getResult()):
            $this->Result = $create->getResult();
            $this->Erro = array("<b>Sucesso:</b> A Rede Social {$this->Data['social_nome']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a alteração dos dados da rede social */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE social_id = :id", "id={$this->Social_id}");
        
        if ($update->getResult()):
            $this->Result = $update->getResult();            
            $this->Erro = array("<b>Sucesso:</b> A rede social {$this->Data['social_nome']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE social_id = :id", "id={$this->Social_id}");

        if ($deletar->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, seu arquivo foi excluido do sistema!", KL_ACCEPT);
        //header("Location: painel.php?exe=paginas/lista");
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel excluir os dados do sistema!", KL_ERROR);
        endif;
    }

}
