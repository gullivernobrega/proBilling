<?php

/**
 * Sip.class [ MODEL ]
 * Classe responsavel por realizar cadastro, alteração e listagem de dados do ramal Sip. 
 * @copyright (c) 02/03/2018, Kleber de Souza BRAZISTELECOM
 */
class Sip {

    private $Data;
    private $Sip_id;
    private $Erro;
    private $Result;
    private $ArqConf;

    //Nome da tabela no banco de dados.
    const Tabela = "ramalsip";
    //Nome do diretorio e o arquivo .conf
    const Diretorio = "/etc/asterisk/sip_probilling.conf";
    //const Diretorio = "a sip_probilling.conf";

    /**
     * Metodo inicial, responsavel por executar os dados para ramal sip   
     */
    public function ExeCreate(array $data) {
        $this->Data = $data;

        $this->setData();
        $this->setNome();
        if (!$this->Result):
            $this->Result = TRUE;
            $this->Erro = array("Opa, você tentou cadastrar um Ramal Sip que já esta cadastrado no sitema!", KL_ALERT);
        else:
            $this->Create();
        endif;
    }

    /**
     * Metodo responsagem por realizar alterações no ramal sip     
     */
    public function ExeUpdate($sip_id, $data) {
        $this->Sip_id = (int) $sip_id;
        $this->Data = $data;

        $this->setData();
        $this->Update();
    }

    /** Class resposavel por apagar ramal sip */
    public function ExeDelete($sip_id) {
        $this->Sip_id = (int) $sip_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE sip_id = :id", "id={$this->Sip_id}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover um Ramal Sip que não existe no sistema!", KL_INFOR);
        else:
            $this->Delete();
        endif;
    }

    /** Monta o arquivo .conf  */
    public function ExeConf($sip_id) {
        $this->Sip_id = (int) $sip_id;

        $this->setConf();
        $this->WriteConf();
    }

    /** Monta o arquivo .conf  */
    public function ExeConfGeral() {
        $this->setConfGeral();
        $this->WriteConfGeral();
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
        $this->setNat();
        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);
    }

    /** Verifica a existencia de alguma duplicação. */
    private function setNome() {
        $Where = (!empty($this->Sip_id) ? "sip_id != {$this->Sip_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} sip_numero = :s", "s={$this->Data['sip_numero']}");

        if ($readName->getResult()):
            $this->Result = FALSE;
        else:
            $this->Result = TRUE;
        endif;
    }

    /** Tratamento no NAT * */
    private function setNat() {
        $sip_nat = '';
        foreach ($this->Data['sip_nat'] as $val):
            $sip_nat .= $val . ',';
        endforeach;
        $sip_nat = substr($sip_nat, 0, -1);

        $this->Data['sip_nat'] = $sip_nat;
    }

    /** Busca e prepara todo o conteudo do arquivo .conf */
    private function setConf() {
        $readSip = new Read;
        $readSip->ExeRead(self::Tabela, "WHERE sip_id = :i", "i={$this->Sip_id}");
        $obj = $readSip->getResult();
        $this->Data = $obj[0];

        $this->ArqConf = "                    
[{$this->Data['sip_numero']}]                         
type=friend
secret={$this->Data['sip_senha']} 
host={$this->Data['sip_host']}
dtmfmode={$this->Data['sip_dtmf_mold']}                        
mailbox={$this->Data['sip_numero']}
directmedia={$this->Data['sip_directmedia']}
nat={$this->Data['sip_nat']}                                                
allow={$this->Data['sip_codec1']}  
allow={$this->Data['sip_codec2']} 
allow={$this->Data['sip_codec3']}  
quality={$this->Data['sip_qualifily']}
context=probilling
callerid={$this->Data['sip_callerid']}
callgroup=1
pickupgroup=1
        ";
    }

    /** Busca todo o arquivo e prepara o conteudo do arquivo .conf geral */
    private function setConfGeral() {
        $readSip = new Read;
        $readSip->ExeRead(self::Tabela);

        foreach ($readSip->getResult() as $data):
            extract($data);
            $this->ArqConf[] = "                    
[{$sip_numero}]                         
type=friend
secret={$sip_senha} 
host={$sip_host}
dtmfmode={$sip_dtmf_mold}                        
mailbox={$sip_numero}
directmedia={$sip_directmedia}
nat={$sip_nat}                                                
allow={$sip_codec1}  
allow={$sip_codec2} 
allow={$sip_codec3}    
quality={$sip_qualifily}
context=probilling
callerid={$sip_callerid}
callgroup=1
pickupgroup=1
        ";
        endforeach;
    }

    /** Execulta a criação dos dados */
    private function Create() {
        $create = new Create;
        $create->ExeCreate(self::Tabela, $this->Data);

        if ($create->getResult()):
            $this->Result = $create->getResult();
            $this->Erro = array("<b>Sucesso:</b> O Sip {$this->Data['sip_numero']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE sip_id = :id", "id=$this->Sip_id");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> O Sip {$this->Data['sip_numero']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE sip_id = :id", "id={$this->Sip_id}");

        if ($deletar->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, Ramal Sip foi excluido do sistema!", KL_ACCEPT);
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel excluir o Ramal Sip do sistema!", KL_ERROR);
        endif;
    }

    /** Realiza a leitura e a gravação do arquivo .conf */
    private function WriteConf() {

        // Abre o arquivo em modo de leitura e escrita, e coloca o ponteiro no final do arquivo.
        $handle = fopen(self::Diretorio, 'a+');
        if (!$handle):
            echo "Não foi possível abrir o arquivo " . self::Diretorio . "!";
            exit;
        endif;

        // Escreve $conteudo no arquivo aberto.
        $escreve = fwrite($handle, $this->ArqConf);
        if ($escreve):
            $this->Result = TRUE;
        else:
            echo "Não foi possível escrever no arquivo " . self::Diretorio . "!";
            exit;
        endif;
        // Fecha a edição do arquivo
        fclose($handle);
    }

    /** Realiza a leitura e a gravação do arquivo .conf geral */
    private function WriteConfGeral() {

        // Abre o arquivo em modo de leitura e escrita, e coloca o ponteiro no final do arquivo.
        $handle = fopen(self::Diretorio, 'w+');
        if (!$handle):
            echo "Não foi possível abrir o arquivo " . self::Diretorio . "!";
            exit;
        endif;

        foreach ($this->ArqConf as $val):
            // Escreve $conteudo no arquivo aberto.
            $escreve = fwrite($handle, $val);
        endforeach;

        if ($escreve):
            $this->Result = TRUE;
        else:
            echo "Não foi possível escrever no arquivo " . self::Diretorio . "!";
            exit;
        endif;

        fclose($handle);
    }

}

// close Clientes