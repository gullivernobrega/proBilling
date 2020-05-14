<?php

/**
 * Troncoiax.class [ MODEL ]
 * Classe responsavel por realizar cadastro, alteração e listagem de dados do tronco Iax. 
 * @copyright (c) 12/03/2018, Kleber de Souza BRAZISTELECOM
 */
class Troncoiax {

    private $Data;
    private $Tronco_id;
    private $Erro;
    private $Result;
    private $ArqConf;
    private $Conta;

    //Nome da tabela no banco de dados.
    const Tabela = "tronco";
    //Nome do diretorio e o arquivo .conf
    const Diretorio = "/etc/asterisk/iax_probilling_tronco.conf";
    //const Diretorio = "iax_probilling_tronco.conf";

    /**
     * Metodo inicial, responsavel por executar os dados para ramal tronco   
     */
    public function ExeCreate(array $data) {
        $this->Data = $data;

        $this->setData();
        $this->setNome();
        if (!$this->Result):
            $this->Result = TRUE;
            $this->Erro = array("Opa, você tentou cadastrar um Tronco Iax que já esta cadastrado no sitema!", KL_ALERT);
        else:
            $this->Create();
        endif;
    }

    /**
     * Metodo responsagem por realizar alterações no ramal tronco     
     */
    public function ExeUpdate($tronco_id, $data) {
        $this->Tronco_id = (int) $tronco_id;
        $this->Data = $data;
        
        $this->setData();
        $this->Update();
    }

    /** Class resposavel por apagar ramal tronco */
    public function ExeDelete($tronco_id) {
        $this->Tronco_id = (int) $tronco_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE tronco_id = :id", "id={$this->Tronco_id}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover uma Tronco que não existe no sistema!", KL_INFOR);
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
        $nome = $this->Data['tronco_nome'];        
        $callerid = (!empty($this->Data['tronco_callerid']) ? $this->Data['tronco_callerid'] : null);  
        
        //Instancia da classe para limpara o nome
        $check = new Check;
        $nome = $check->NameLinpo($nome);         
        
        unset($this->Data['tronco_callerid'], $this->Data['tronco_nome']);

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

    /** Busca e prepara todo o conteudo do arquivo .conf */
    private function setConf() {
        $readTronco = new Read;
        $readTronco->ExeRead(self::Tabela, "WHERE tronco_id = :i", "i={$this->Tronco_id}");
        $obj = $readTronco->getResult();
        $this->Data = $obj[0];

        $this->ArqConf = "                    
[{$this->Data['tronco_nome']}]                         
type=friend
context=entrada
username={$this->Data['tronco_username']} 
secret={$this->Data['tronco_senha']} 
callerid={$this->Data['tronco_callerid']}  
allow={$this->Data['tronco_codec1']}  
allow={$this->Data['tronco_codec2']}  
allow={$this->Data['tronco_codec3']}     
host={$this->Data['tronco_host']}                       
fromdomain={$this->Data['tronco_fromdomain']}
trunk={$this->Data['tronco_trunk']}
        ";
    }

    /** Busca todo o arquivo e prepara o conteudo do arquivo .conf geral */
    private function setConfGeral() {
        $readTronco = new Read;
        $readTronco->ExeRead(self::Tabela);

        $this->Conta = $readTronco->getRowCount();

        foreach ($readTronco->getResult() as $data):
            extract($data);
            if ($tronco_tipo == 'IAX2'):

                $this->ArqConf[] = "                    
[{$tronco_nome}]                         
type=friend
context=entrada
username={$tronco_username}
secret={$tronco_senha} 
callerid={$tronco_callerid}  
allow={$tronco_codec1}  
allow={$tronco_codec2}  
allow={$tronco_codec3}     
host={$tronco_host}                       
fromdomain={$tronco_fromdomain}
trunk={$tronco_trunk}
        ";

            endif;
        endforeach;
    }

    /** Execulta a criação dos dados */
    private function Create() {
        $create = new Create;
        $create->ExeCreate(self::Tabela, $this->Data);

        if ($create->getResult()):
            $this->Result = $create->getResult();
            $this->Erro = array("<b>Sucesso:</b> O Tronco Tronco {$this->Data['tronco_nome']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE tronco_id = :id", "id=$this->Tronco_id");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> O Tronco Tronco {$this->Data['tronco_nome']} foi alterado no sietema!", KL_ACCEPT);
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