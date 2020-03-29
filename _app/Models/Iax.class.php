<?php

/**
 * Iax.class [ MODEL ]
 * Classe responsavel por realizar cadastro, alteração e listagem de dados do ramal Iax. 
 * @copyright (c) 12/03/2018, Kleber de Souza BRAZISTELECOM
 */
class Iax {

    private $Data;
    private $Iax_id;
    private $Erro;
    private $Result;
    private $ArqConf;
    private $Conta;

    //Nome da tabela no banco de dados.
    const Tabela = "ramaliax";
    //Nome do diretorio e o arquivo .conf
    const Diretorio = "/etc/asterisk/iax_probilling.conf";
    //const Diretorio = "iax_probilling.conf";

    /**
     * Metodo inicial, responsavel por executar os dados para ramal iax   
     */
    public function ExeCreate(array $data) {
        $this->Data = $data;

        $this->setData();
        $this->setNome();
        if (!$this->Result):
            $this->Result = TRUE;
            $this->Erro = array("Opa, você tentou cadastrar um Ramal Iax que já esta cadastrado no sitema!", KL_ALERT);
        else:
            $this->Create();
        endif;
    }

    /**
     * Metodo responsagem por realizar alterações no ramal iax     
     */
    public function ExeUpdate($iax_id, $data) {
        $this->Iax_id = (int) $iax_id;
        $this->Data = $data;

        $this->setData();
        $this->Update();
    }

    /** Class resposavel por apagar ramal iax */
    public function ExeDelete($iax_id) {
        $this->Iax_id = (int) $iax_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE iax_id = :id", "id={$this->Iax_id}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover um Ramal Iax que não existe no sistema!", KL_INFOR);
        else:
            $this->Delete();
        endif;
    }

    /** Monta o arquivo .conf  */
    public function ExeConf($iax_id) {
        $this->Iax_id = (int) $iax_id;

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
        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);
    }

    /** Verifica a existencia de alguma duplicação. */
    private function setNome() {
        $Where = (!empty($this->Iax_id) ? "iax_id != {$this->Iax_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} iax_numero = :s", "s={$this->Data['iax_numero']}");

        if ($readName->getResult()):
            $this->Result = FALSE;
        else:
            $this->Result = TRUE;
        endif;
    }

    /** Busca e prepara todo o conteudo do arquivo .conf */
    private function setConf() {
        $readIax = new Read;
        $readIax->ExeRead(self::Tabela, "WHERE iax_id = :i", "i={$this->Iax_id}");
        $obj = $readIax->getResult();
        $this->Data = $obj[0];

        $this->ArqConf = "                    
[{$this->Data['iax_numero']}]                         
type=friend
context=probilling
secret={$this->Data['iax_senha']} 
callerid={$this->Data['iax_numero']}  
allow={$this->Data['iax_codec1']}  
allow={$this->Data['iax_codec2']}  
allow={$this->Data['iax_codec3']}     
host={$this->Data['iax_host']}                       
fromdomain={$this->Data['iax_host']}
trunk={$this->Data['iax_trunk']}
        ";
    }

    /** Busca todo o arquivo e prepara o conteudo do arquivo .conf geral */
    private function setConfGeral() {
        $readIax = new Read;
        $readIax->ExeRead(self::Tabela);

        $this->Conta = $readIax->getRowCount();

        foreach ($readIax->getResult() as $data):
            extract($data);

            $this->ArqConf[] = "                    
[{$iax_numero}]                         
type=friend
context=probilling
secret={$iax_senha} 
callerid={$iax_numero}  
allow={$iax_codec1}  
allow={$iax_codec2}  
allow={$iax_codec3}     
host={$iax_host}                       
fromdomain={$iax_host}
trunk={$iax_trunk}
        ";
        endforeach;
    }

    /** Execulta a criação dos dados */
    private function Create() {
        $create = new Create;
        $create->ExeCreate(self::Tabela, $this->Data);

        if ($create->getResult()):
            $this->Result = $create->getResult();
            $this->Erro = array("<b>Sucesso:</b> O Iax {$this->Data['iax_numero']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE iax_id = :id", "id=$this->Iax_id");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> O Iax {$this->Data['iax_numero']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE iax_id = :id", "id={$this->Iax_id}");

        if ($deletar->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, Ramal Iax foi excluido do sistema!", KL_ACCEPT);
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel excluir o Ramal Iax do sistema!", KL_ERROR);
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