<?php

/**
 * Campanha.class [ MODEL ]
 * Classe responsavel por realizar cadastro, alteração e listagem de dados da Campanha. 
 * @copyright (c) 09/05/2018, Kleber de Souza BRAZISTELECOM
 */
class Campanha {

    private $Data;
    private $Campanha_id;
    private $Tiporamal;
    private $Complemento;
    private $Erro;
    private $Result;
    private $Conta;

    //Nome da tabela no banco de dados.
    const Tabela = "campanha";

    /**
     * Metodo inicial, responsavel por executar os dados para ramal iax   
     */
    public function ExeCreate(array $data) {
        $this->Data = $data;

        $this->setComplemento();
        $this->setData();
        $this->setNome();
        if (!$this->Result):
            $this->Result = TRUE;
            $this->Erro = array("Opa, você tentou cadastrar uma Campanha que já esta cadastrado no sitema!", KL_ALERT);
        else:
            $this->Create();            
        endif;
    }

    /**
     * Metodo responsagem por realizar alterações no ramal iax     
     */
    public function ExeUpdate($campanha_id, $data) {
        $this->Campanha_id = (int) $campanha_id;
        $this->Data = $data;

        $this->setComplemento();
        $this->setData();
        $this->Update();
    }

    /** Class resposavel por apagar ramal iax */
    public function ExeDelete($campanha_id) {
        $this->Campanha_id = (int) $campanha_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE campanha_id = :id", "id={$this->Campanha_id}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover uma Campanha que não existe no sistema!", KL_INFOR);
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

    /** Prepara o tipo e o ramal */
    private function setComplemento() {
        
        if ($this->Data['campanha_destino_tipo'] == 'IAX' && !empty($this->Data['ramalIax'])):
            
            $campanha_destino_complemento = $this->Data['ramalIax'];            
            unset($this->Data['ramalIax']);
            
        elseif ($this->Data['campanha_destino_tipo'] == 'SIP' && !empty($this->Data['ramalSip'])):
            
            $campanha_destino_complemento = $this->Data['ramalSip'];            
            unset($this->Data['ramalSip']);
            
        elseif ($this->Data['campanha_destino_tipo'] == 'QUEUE' && !empty($this->Data['queue'])):
            
            $campanha_destino_complemento = $this->Data['queue'];            
            unset($this->Data['queue']);
            
        elseif ($this->Data['campanha_destino_tipo'] == 'GROUP' && !empty($this->Data['group'])):
            
            $campanha_destino_complemento = $this->Data['group'];            
            unset($this->Data['group']);
            
        elseif ($this->Data['campanha_destino_tipo'] == 'CUSTOM' && !empty($this->Data['custom'])):
            
            $campanha_destino_complemento = $this->Data['custom'];            
            unset($this->Data['custom']);
            
        endif;

        $this->Complemento = $campanha_destino_complemento;    
        
    }

    /** Prepara os dados create */
    private function setData() {
               
        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);
        
        $campanha_tipo = $this->Data['campanha_tipo'];  
        $campanha_nome = $this->Data['campanha_nome'];        
        $campanha_data_inicio = $this->Data['campanha_data_inicio'];
        $campanha_data_fim = $this->Data['campanha_data_fim'];
//        $campanha_data_inicio = Check::Data($this->Data['campanha_data_inicio']);
//        $campanha_data_fim = Check::Data($this->Data['campanha_data_fim']);
        $campanha_audio_1 = $this->Data['campanha_audio_1'];
        $campanha_audio_2 = $this->Data['campanha_audio_2'];
        $campanha_limite_chamada = $this->Data['campanha_limite_chamada'];
        $campanha_tts_1 = $this->Data['campanha_tts_1'];
        $campanha_tts_2 = $this->Data['campanha_tts_2'];
        $campanha_asr = $this->Data['campanha_asr'];
        $campanha_destino_tipo = $this->Data['campanha_destino_tipo'];
        $campanha_agenda = $this->Data['campanha_agenda'];
        $campanha_status = $this->Data['campanha_status'];

        unset(
        $this->Data['campanha_tipo'], $this->Data['campanha_nome'], $this->Data['campanha_data_inicio'], $this->Data['campanha_data_fim'], $this->Data['campanha_audio_1'], $this->Data['campanha_audio_2'], $this->Data['campanha_limite_chamada'], $this->Data['campanha_tts_1'], $this->Data['campanha_tts_2'], $this->Data['campanha_asr'], $this->Data['campanha_destino_tipo'], $this->Data['campanha_agenda'], $this->Data['campanha_status']
        );
        
        $this->Data['campanha_nome'] = $campanha_nome;
        $this->Data['campanha_tipo'] = $campanha_tipo;
        $this->Data['campanha_data_inicio'] = $campanha_data_inicio;
        $this->Data['campanha_data_fim'] = $campanha_data_fim;
        $this->Data['campanha_audio_1'] = $campanha_audio_1;
        $this->Data['campanha_audio_2'] = $campanha_audio_2;
        $this->Data['campanha_limite_chamada'] = $campanha_limite_chamada;
        $this->Data['campanha_tts_1'] = $campanha_tts_1;
        $this->Data['campanha_tts_2'] = $campanha_tts_2;
        $this->Data['campanha_asr'] = $campanha_asr;
        $this->Data['campanha_destino_tipo'] = $campanha_destino_tipo;
        $this->Data['campanha_destino_complemento'] = $this->Complemento;
        $this->Data['campanha_agenda'] = $campanha_agenda;
        $this->Data['campanha_status'] = $campanha_status;
        
    }

    /** Verifica a existencia de alguma duplicação. */
    private function setNome() {
        $Where = (!empty($this->Campanha_id) ? "campanha_id != {$this->Campanha_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} campanha_nome = :a", "a={$this->Data['campanha_nome']}");

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
            $this->Erro = array("<b>Sucesso:</b> A Campanha {$this->Data['campanha_nome']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE campanha_id = :id", "id=$this->Campanha_id");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> A Campanha {$this->Data['campanha_nome']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE campanha_id = :id", "id={$this->Campanha_id}");

        if ($deletar->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, Campanha foi excluido do sistema!", KL_ACCEPT);
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel excluir a Campanha do sistema!", KL_ERROR);
        endif;
    }

}

// close Clientes