<?php

/**
 * Did.class [ MODEL ]
 * Classe responsavel por realizar toda manutenção do DID
 * @copyright (c) 11/04/2018, Kleber de Souza BRAZISTELECOM
 */
class Did {

    private $Data;
    private $Did_id;
    private $Erro;
    private $Result;
    private $ArqConf;
    private $NomeArquivo;

    //Nome da tabela no banco de dados.
    const Tabela = "did";
    //Nome do diretorio e o arquivo .conf
    const Diretorio = "/etc/asterisk/did_probilling.conf";
    const Arquivo = "arquivos/";

    /**
     * Metodo inicial, responsavel por executar os dados para ramal did   
     */
    
    public function ExeCreate(array $data) {
        $this->Data = $data;

        $this->setData();
        $this->setNome();
        if (!$this->Result):
            $this->Result = TRUE;
            $this->Erro = array("Opa, você tentou cadastrar um Did que já esta cadastrado no sitema!", KL_ALERT);
        else:
            $this->Create();
        endif;
    }

    /**
     * Metodo responsagem por realizar alterações no ramal did     
     */
    public function ExeUpdate($did_id, $data) {
        $this->Did_id = (int) $did_id;
        $this->Data = $data;
        
        $this->setData();
        $this->Update();
    }

    /** Class resposavel por apagar ramal did */
    public function ExeDelete($did_id) {
        $this->Did_id = (int) $did_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE did_id = :id", "id={$this->Did_id}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover um Ramal Did que não existe no sistema!", KL_INFOR);
        else:
            $this->Delete();
        endif;
    }

    /** Retorna o resultado  */
    public function getResult() {
        return $this->Result;
    }

    /** Retorna o erro  */
    public function getErro() {
        return $this->Erro;
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */

    /** Prepara os dados create */
    private function setData() {

        $tipo = $this->Data['tipo'];
        
        if ($tipo == 'IAX'):
            
            $ramal = $this->Data['ramalIax'];
            $tiporamal = "$tipo/$ramal";
            unset($this->Data['ramalIax']);
            
        elseif ($tipo == 'SIP'):
            
            $ramal = $this->Data['ramalSip'];
            $tiporamal = "$tipo/$ramal";
            unset($this->Data['ramalSip']);
            
        elseif ($tipo == 'QUEUE'):
            
            $ramal = $this->Data['queue'];
//            $tiporamal = "$tipo/$ramal";
            $tiporamal = "$ramal";
            unset($this->Data['queue']);
            
        elseif ($tipo == 'URA'):      
            
            $ramal = $this->Data['ura'];
            $tiporamal = "$ramal";
            unset($this->Data['ura']);
            
        elseif ($tipo == 'CUSTOM'):       
            
            $ramal = $this->Data['custom'];    
            $tiporamal = $ramal;
            unset($this->Data['custom']);
            
        endif;
        
        unset($this->Data['tipo']);

        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

        //nome da imagem
        //$this->NomeArquivo = Check::Name($this->Data['did_arquivo']);
        //repassa o array
        $this->Data['did_destino_func'] = $tipo;
        $this->Data['did_destino'] = $tiporamal;
        
    }

    /** Verifica a existencia de alguma duplicação. */
    private function setNome() {
        $Where = (!empty($this->Did_id) ? "did_id != {$this->Did_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} did_nome = :s", "s={$this->Data['did_nome']}");

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
            $this->Erro = array("<b>Sucesso:</b> O Did {$this->Data['did_nome']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE did_id = :id", "id=$this->Did_id");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> O Did {$this->Data['did_numero']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE did_id = :id", "id={$this->Did_id}");

        if ($deletar->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, Did foi excluido do sistema!", KL_ACCEPT);
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel excluir o Did do sistema!", KL_ERROR);
        endif;
    }

}
