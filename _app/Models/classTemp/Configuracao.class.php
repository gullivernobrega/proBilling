<?php

/**
 * Configuracao.class [ MODEL ]
 * Classe responsavel por fazer toda configuração do site;
 * @copyright (c) 18/10/2016, Kleber de Souza KLSDESIGNER
 */
class Configuracao {

    private $Data;
    private $Conf_id;
    private $Erro;
    private $Result;

    //Nome da tabela no banco de dados.
    const Tabela = "kl_configuracao";

    /**
     * Metodo responsavel por criar as categorias e subcategorias    
     */
//    public function ExeCreate($data) {
//        $this->Data = $data;
//
//        //Verifica se existe campos em branco
//        if (in_array('', $this->Data)):
//            $this->Result = false;
//            $this->Erro = array('<b>Erro ao Cadastrar:</b> Para cadastrar uma categoria preencha todos os campos!', KL_ALERT);
//        else:
//            $this->setData();
//            $this->setNome();
//            $this->Create();
//        endif;
//    }

    /**
     * Metodo responsagem por realizar alterações na configuração do site     
     */
    public function ExeUpdate($conf_id, $data) {
        $this->Conf_id = $conf_id;
        $this->Data = $data;

        //seta os dados tratados. 
        $this->setData();
        //Faz o upload da logomarca
        //$this->UpImage();

        if ($this->Data['conf_logotipo']):
            $upLogo = new Upload("../themes/americoadvogados");
            $upLogo->Image($this->Data['conf_logotipo'], 'logo', 216, "/images");
        endif;

        if (isset($upLogo) && $upLogo->getResult()):
            $this->Data['conf_logotipo'] = $upLogo->getResult();
            $this->Update();
        else:
            $this->Data['conf_logotipo'] = null;
            $this->Update();
        endif;

//        $upIcone = new Upload("../themes/americoadvogados");
//        $upIcone->Image("$this->Data['conf_icone']", "favicon", 32, "/images");
//        if (!$upIcone->getResult()):
//            $this->Result = false;
//            $this->Erro = array("Erro, não foi possivel realizar o upload do icone. Verifique!", KL_ALERT);
//        endif;
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
    //`conf_id`, `conf_nome`, `conf_email`, `conf_url`, 
    //`conf_descricao`, `conf_chave`, `conf_endereco`, 
    //`conf_mapa`, `conf_fone`, `conf_celular`, `conf_logotipo`, `conf_icone`

    /** Prepara os dados */
    private function setData() {
        //$conf_mapa = (!empty($this->Data['conf_mapa']) ? null : $this->Data['conf_mapa']);
        //$conf_logotipo = (!empty($this->Data['conf_logotipo']) ? null : $this->Data['conf_logotipo']);
        $conf_descricao = $this->Data['conf_descricao'];
        $conf_endereco = $this->Data['conf_endereco'];
        $conf_mapa = $this->Data['conf_mapa'];
        $conf_logotipo = $this->Data['conf_logotipo'];

        //$conf_icone = $this->Data['conf_icone'];
        unset($this->Data['conf_descricao'], $this->Data['conf_endereco'], $this->Data['conf_mapa'], $this->Data['conf_logotipo']);

        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

        //Reoganiza o array Dados
        $this->Data['conf_descricao'] = $conf_descricao;
        $this->Data['conf_endereco'] = $conf_endereco;
        $this->Data['conf_mapa'] = $conf_mapa;
        $this->Data['conf_logotipo'] = $conf_logotipo;


        //$this->Data['conf_icone'] = $conf_icone;        
    }

    /** Execulta o upload da Logo */
//    private function UpImage() {
//        $upLogo = new Upload("../themes/americoadvogados");
//        $upLogo->Image($this->Data['conf_logotipo'], 'logo', 216, "/images");
//
//        if (!$upLogo->getResult()):
//            $this->Result = null;
//            $this->Erro = array("Erro, não foi possivel realizar o upload da logomarca. Verifique!", KL_ALERT);
//        else:
//            //faz a inserssão dos dados.
//            //$this->Update();           
//            //echo "vou cadastrar";
//            
//        endif;
//    }

    /** Execulta a criação dos dados */
//    private function Create() {
//        $create = new Create;
//        $create->ExeCreate(self::Tabela, $this->Data);
//
//        if ($create->getResult()):
//            $this->Result = $create->getResult();
//            $this->Erro = array("<b>Sucesso:</b> A categoria {$this->Data['categoria_nome']} foi cadastrado no sietema!", KL_ACCEPT);
//        endif;
//    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE conf_id = :id", "id={$this->Conf_id}");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> A configuração {$this->Data['conf_nome']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

}
