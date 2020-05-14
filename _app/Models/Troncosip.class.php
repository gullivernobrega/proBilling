<?php

/**
 * Troncosip.class [ MODEL ]
 * Classe responsavel por realizar cadastro, alteração e listagem de dados do tronco sip. 
 * @copyright (c) 12/03/2018, Kleber de Souza BRAZISTELECOM
 */
class Troncosip {

    private $Data;
    private $Tronco_id;
    private $Erro;
    private $Result;
    private $ArqConf;
    private $ArqConfRegister;

    //Nome da tabela no banco de dados.
    const Tabela = "tronco";
    //Nome do diretorio e o arquivo .conf
    const Diretorio = "/etc/asterisk/sip_probilling_tronco.conf";
    const Diretorio1 = "/etc/asterisk/sip_probilling_register.conf";
    //const Diretorio = "sip_probilling_tronco.conf";
    //const Diretorio1 = "sip_probilling_register.conf";

    /**
     * Metodo inicial, responsavel por executar os dados para tronco tronco   
     */
    public function ExeCreate(array $data) {
        $this->Data = $data;

        $this->setData();
        $this->setNome();
        if (!$this->Result):
            $this->Result = TRUE;
            $this->Erro = array("Opa, você tentou cadastrar um Tronco que já esta cadastrado no sitema!", KL_ALERT);
        else:
            $this->Create();
        endif;
    }

    /**
     * Metodo responsagem por realizar alterações no tronco tronco     
     */
    public function ExeUpdate($tronco_id, $data) {
        $this->Tronco_id = (int) $tronco_id;
        $this->Data = $data;

        $this->setData();
        $this->Update();
    }

    /** Class resposavel por apagar tronco tronco */
    public function ExeDelete($tronco_id) {
        $this->Tronco_id = (int) $tronco_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE tronco_id = :id", "id={$this->Tronco_id}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover um Tronco que não existe no sistema!", KL_INFOR);
        else:
            $this->Delete();
        endif;
    }

    /** Monta o arquivo .conf  */
    public function ExeConf($tronco_id) {
        $this->Tronco_id = (int) $tronco_id;

        $this->setConf();
        $this->WriteConf();
    }
    
    /** Monta o arquivo .conf do Register  */
    public function ExeConfReg($tronco_id) {
        $this->Tronco_id = (int) $tronco_id;

        $this->setConfReg();
        $this->WriteConfReg();
    }

    /** Monta o arquivo .conf  */
    public function ExeConfGeral() {
        $this->setConfGeral();
        $this->WriteConfGeral();
    }
    
    /** Monta o arquivo .conf  */
    public function ExeConfGeralReg() {
        $this->setConfGeralReg();
        $this->WriteConfGeralReg();
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
        $nome = $this->Data['tronco_nome'];
        $callerid = (!empty($this->Data['tronco_callerid']) ? $this->Data['tronco_callerid'] : null);

        $check = new Check;
        $nome = $check->NameLinpo($nome);

        unset($this->Data['tronco_callerid'], $this->Data['tronco_nome']);

        $this->setNat();
        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

        $this->Data['tronco_nome'] = $nome;
        $this->Data['tronco_callerid'] = $callerid;
    }

    /** Verifica a existencia de alguma duplicação. */
    private function setNome() {
        $Where = (!empty($this->Tronco_id) ? "tronco_id != {$this->Tronco_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} tronco_nome = :s", "s={$this->Data['tronco_nome']}");

        if ($readName->getResult()):
            $this->Result = FALSE;
        else:
            $this->Result = TRUE;
        endif;
    }

    /** Tratamento no NAT * */
    private function setNat() {
        $tronco_nat = '';
        foreach ($this->Data['tronco_nat'] as $val):
            $tronco_nat .= $val . ',';
        endforeach;
        $tronco_nat = substr($tronco_nat, 0, -1);

        $this->Data['tronco_nat'] = $tronco_nat;
    }

    /** Busca e prepara todo o conteudo do arquivo .conf */
    private function setConf() {
        $readTronco = new Read;
        $readTronco->ExeRead(self::Tabela, "WHERE tronco_id = :i", "i={$this->Tronco_id}");
        $obj = $readTronco->getResult();
        $this->Data = $obj[0];

        // Prepara o arquivo conf
        $this->ArqConf = "                    
[{$this->Data['tronco_nome']}]  
user={$this->Data['tronco_username']}     
username={$this->Data['tronco_username']} 
fromuser={$this->Data['tronco_fromuser']}     
secret={$this->Data['tronco_senha']} 
host={$this->Data['tronco_host']}
fromdomain={$this->Data['tronco_fromdomain']}   
port={$this->Data['tronco_port']}   
dtmfmode={$this->Data['tronco_dtmf_mold']}                        
mailbox={$this->Data['tronco_username']}
directmedia={$this->Data['tronco_directmedia']}
nat={$this->Data['tronco_nat']}  
insecure={$this->Data['tronco_insecure']}    
allow={$this->Data['tronco_codec1']}  
allow={$this->Data['tronco_codec2']} 
allow={$this->Data['tronco_codec3']}  
quality={$this->Data['tronco_qualify']}
type=friend
context=entrada
callerid={$this->Data['tronco_callerid']}
        ";

    }
 
    /** Busca e prepara todo o conteudo do arquivo .conf Register */
    private function setConfReg() {
        $readTronco = new Read;
        $readTronco->ExeRead(self::Tabela, "WHERE tronco_id = :i", "i={$this->Tronco_id}");
        $obj = $readTronco->getResult();
        $this->Data = $obj[0];
        
        //Prepara o arquivo conf do register
        $this->ArqConfReg = "{$this->Data['tronco_register']} \r\n";
    }    
    
    /** Busca todo o arquivo e prepara o conteudo do arquivo .conf geral */
    private function setConfGeral() {
        $readTronco = new Read;
        $readTronco->ExeRead(self::Tabela);

        foreach ($readTronco->getResult() as $data):
            extract($data);
            if ($tronco_tipo == 'SIP'):

                $this->ArqConf[] = "                    
[{$tronco_nome}]  
user={$tronco_username}     
username={$tronco_username} 
fromuser={$tronco_fromuser}     
secret={$tronco_senha} 
host={$tronco_host}
fromdomain={$tronco_fromdomain}   
port={$tronco_port}   
dtmfmode={$tronco_dtmf_mold}                        
mailbox={$tronco_username}
directmedia={$tronco_directmedia}
nat={$tronco_nat}  
insecure={$tronco_insecure}    
allow={$tronco_codec1}  
allow={$tronco_codec2} 
allow={$tronco_codec3}  
quality={$tronco_qualify}
type=friend
context=entrada
callerid={$tronco_callerid}
        ";
                
            endif;
        endforeach;
    }
 
    /** Busca todo o arquivo e prepara o conteudo do arquivo .conf geral Register*/
    private function setConfGeralReg() {
        $readTronco = new Read;
        $readTronco->ExeRead(self::Tabela);

        foreach ($readTronco->getResult() as $data):
            extract($data);
            if ($tronco_tipo == 'SIP'):
              
                //Prepara o arquivo conf do register
                $this->ArqConfReg[] = "{$tronco_register} \r\n";
                
            endif;
        endforeach;
    }

    /** Execulta a criação dos dados */
    private function Create() {
        $create = new Create;
        $create->ExeCreate(self::Tabela, $this->Data);

        if ($create->getResult()):
            $this->Result = $create->getResult();
            $this->Erro = array("<b>Sucesso:</b> O Tronco {$this->Data['tronco_nome']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE tronco_id = :id", "id=$this->Tronco_id");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> O Tronco {$this->Data['tronco_nome']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE tronco_id = :id", "id={$this->Tronco_id}");

        if ($deletar->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, Tronco Tronco foi excluido do sistema!", KL_ACCEPT);
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel excluir o Tronco Tronco do sistema!", KL_ERROR);
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


    /** Realiza a leitura e a gravação do arquivo .conf do Register */
    private function WriteConfReg() {

        // Abre o arquivo em modo de leitura e escrita, e coloca o ponteiro no final do arquivo.
        $handle = fopen(self::Diretorio1, 'a+');
        if (!$handle):
            echo "Não foi possível abrir o arquivo " . self::Diretorio1 . "!";
            exit;
        endif;

        // Escreve $conteudo no arquivo aberto.
        $escreve = fwrite($handle, $this->ArqConfReg);
        if ($escreve):
            $this->Result = TRUE;
        else:
            echo "Não foi possível escrever no arquivo " . self::Diretorio1 . "!";
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
            echo "Não foi possível escrever no arquivo " . self::Diretorio . "geral!";
            exit;
        endif;

        fclose($handle);
    }
 
    /** Realiza a leitura e a gravação do arquivo .conf geral Register*/
    private function WriteConfGeralReg() {

        // Abre o arquivo em modo de leitura e escrita, e coloca o ponteiro no final do arquivo.
        $handle = fopen(self::Diretorio1, 'w+');
        if (!$handle):
            echo "Não foi possível abrir o arquivo " . self::Diretorio1 . "geral register!";
            exit;
        endif;

        foreach ($this->ArqConfReg as $val):

            // Escreve $conteudo no arquivo aberto.
            $escreve = fwrite($handle, $val);           

        endforeach;

        if ($escreve):
            $this->Result = TRUE;
        else:
            echo "Não foi possível escrever no arquivo " . self::Diretorio1 . "!";
            exit;
        endif;

        fclose($handle);
    }

}

// close Clientes