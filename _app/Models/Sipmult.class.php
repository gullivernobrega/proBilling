<?php

/**
 * Sipmult.class [ MODEL ]
 * Classe responsavel por realizar cadastro, alteração e listagem de multiplos ramal Sip. 
 * @copyright (c) 16/04/2018, Kleber de Souza BRAZISTELECOM
 */
class Sipmult {

    private $Data;
    private $Sip_id;
    private $Erro;
    private $Result;
    private $ArqConf;
    private $Conta;
    private $Ini;
    private $Fim;
    private $Arr;

    //Nome da tabela no banco de dados.
    const Tabela = "ramalsip";
    //Nome do diretorio e o arquivo .conf
    const Diretorio = "/etc/asterisk/sip_probilling.conf";
    //const Diretorio = "sip_probilling.conf";

    /**
     * Metodo inicial, responsavel por executar os dados para ramal sip   
     */
    public function ExeCreate(array $data) {
        $this->Data = $data;
        $this->ini = $this->Data['sipInicial'];
        $this->fim = $this->Data['sipFinal'];
        unset($this->Data['sipInicial'], $this->Data['sipFinal']);

        $this->setData();
        $this->setNome();
        if (!$this->Result):
            $this->Result = 'N';
            $this->Erro = array("Ops, Existe ramal cadastrado com o mesmo numero. Verifique!", KL_ALERT);
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

    /** Class resposavel por apagar multiplos ramais sip */
    public function ExeDeleteMult($data) {
        $this->Data = $data;
        $this->Ini = $this->Data['sipInicial'];
        $this->Fim = $this->Data['sipFinal'];

        $cont = 0;
        for ($i = $this->Ini; $i <= $this->Fim; $i++):
            $sip_numero = str_pad($this->Ini + $cont, 4, "0", STR_PAD_LEFT);

            $read = new Read;
            $read->ExeRead(self::Tabela, "WHERE sip_numero = :n", "n={$sip_numero}");

            if (!$read->getResult()):
                $this->Result = false;
                $this->Erro = array("Erro, você tentou remover um Ramal Sip que não existe no sistema!", KL_INFOR);
            else:
                $result = $read->getResult();
                extract($result[0]);
                $this->Sip_id = $sip_id;
                $this->Delete();
            endif;

            $cont++;
        endfor;
        //$this->DeleteMult();
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

        $host = $this->Data['sip_host'];
        $mold = $this->Data['sip_dtmf_mold'];
        $dmedia = $this->Data['sip_directmedia'];
        $nat = $this->Data['sip_nat'];
        $codec1 = $this->Data['sip_codec1'];
        $codec2 = $this->Data['sip_codec2'];
        $codec3 = $this->Data['sip_codec3'];
        $qualify = $this->Data['sip_qualifily'];

        unset($this->Data['sip_host'], $this->Data['sip_dtmf_mold'], $this->Data['sip_directmedia'], $this->Data['sip_nat'], $this->Data['sip_codec1'], $this->Data['sip_codec2'], $this->Data['sip_codec3'], $this->Data['sip_qualifily']);

        $cont = 0;
        for ($i = $this->ini; $i <= $this->fim; $i++):
            $sip_numero = str_pad($this->ini + $cont, 4, "0", STR_PAD_LEFT);
            $sip_senha = $sip_numero . $sip_numero;
            $sip_callerid = $sip_numero;
            $sip_host = $host;
            $sip_dtmf_mold = $mold;
            $sip_directmedia = $dmedia;
            $sip_nat = $nat;
            $sip_codec1 = $codec1;
            $sip_codec2 = $codec2;
            $sip_codec3 = $codec3;
            $sip_qualifily = $qualify;

            $this->Data['sip_numero'] = $sip_numero;
            $this->Data['sip_senha'] = $sip_senha;
            $this->Data['sip_callerid'] = $sip_callerid;
            $this->Data['sip_host'] = $sip_host;
            $this->Data['sip_dtmf_mold'] = $sip_dtmf_mold;
            $this->Data['sip_directmedia'] = $sip_directmedia;
            $this->Data['sip_nat'] = $sip_nat;
            $this->Data['sip_codec1'] = $sip_codec1;
            $this->Data['sip_codec2'] = $sip_codec2;
            $this->Data['sip_codec3'] = $sip_codec3;
            $this->Data['sip_qualifily'] = $sip_qualifily;

            $this->Data = array_map('strip_tags', $this->Data);
            $this->Data = array_map('trim', $this->Data);

            $this->Arr[$i] = $this->Data;

            $cont++;
        endfor;
    }

    /** Verifica a existencia de alguma duplicação. */
    private function setNome() {
        foreach ($this->Arr as $val):

            $Where = (!empty($this->Sip_id) ? "sip_id != {$this->Sip_id} AND" : '');

            $readName = new Read;
            $readName->ExeRead(self::Tabela, "WHERE {$Where} sip_numero = :s", "s={$val['sip_numero']}");

            if ($readName->getResult()):
                $this->Result = FALSE;
            else:
                $this->Result = TRUE;
            endif;

        endforeach;
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
qualify={$sip_qualifily}
context=probilling
callerid={$sip_numero}
        ";
        endforeach;
    }

    /** Execulta a criação dos dados */
    private function Create() {
        foreach ($this->Arr as $val):

            $create = new Create;
            $create->ExeCreate(self::Tabela, $val);

            if ($create->getResult()):
                $this->Result = $create->getResult();
            //$this->Erro = array("<b>Sucesso:</b> O Sip {$this->Data['sip_numero']} foi cadastrado no sietema!", KL_ACCEPT);
            endif;

        endforeach;
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

    private function ShellReload() {
        //Reloada no asterisk
        shell_exec("sudo asterisk -rx 'reload'");
    }

}

// close Clientes