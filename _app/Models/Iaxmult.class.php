<?php

/**
 * Iaxmult.class [ MODEL ]
 * Classe responsavel por realizar cadastro, alteração e listagem de multiplos ramal Iax. 
 * @copyright (c) 16/04/2018, Kleber de Souza BRAZISTELECOM
 */
class Iaxmult {

    private $Data;
    private $Iax_id;
    private $Erro;
    private $Result;
    private $ArqConf;
    private $Conta;
    private $ini;
    private $fim;
    private $Arr;

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
        $this->ini = $this->Data['iaxInicial'];
        $this->fim = $this->Data['iaxFinal'];

        unset($this->Data['iaxInicial'], $this->Data['iaxFinal']);

        $this->setData();
        $this->setNome();
        if (!$this->Result):
            $this->Result = "N";
            $this->Erro = array("Ops, Existe ramal cadastrado com o mesmo numero. Verifique!", KL_ALERT);
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
    
    /** Class resposavel por apagar multiplos ramais iax */
    public function ExeDeleteMult($data) {
        $this->Data = $data;
        $this->Ini = $this->Data['iaxInicial'];
        $this->Fim = $this->Data['iaxFinal'];

        $cont = 0;
        for ($i = $this->Ini; $i <= $this->Fim; $i++):
            $iax_numero = str_pad($this->Ini + $cont, 4, "0", STR_PAD_LEFT);

            $read = new Read;
            $read->ExeRead(self::Tabela, "WHERE iax_numero = :n", "n={$iax_numero}");

            if (!$read->getResult()):
                $this->Result = false;
                $this->Erro = array("Erro, você tentou remover um Ramal Iax que não existe no sistema!", KL_INFOR);
            else:
                $result = $read->getResult();
                extract($result[0]);
                $this->Iax_id = $iax_id;
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

        $codec1 = $this->Data['iax_codec1'];
        $codec2 = $this->Data['iax_codec2'];
        $codec3 = $this->Data['iax_codec3'];
        $host = $this->Data['iax_host'];
        $trunk = $this->Data['iax_trunk'];

        unset($this->Data['iax_codec1'], $this->Data['iax_codec2'], $this->Data['iax_codec3'], $this->Data['iax_host'], $this->Data['iax_trunk']);

        $cont = 0;
        for ($i = $this->ini; $i <= $this->fim; $i++):
            $iax_numero = str_pad($this->ini + $cont, 4, "0", STR_PAD_LEFT);
            $iax_senha = $iax_numero . $iax_numero;
            $iax_callerid = $iax_numero;
            $iax_codec1 = $codec1;
            $iax_codec2 = $codec2;
            $iax_codec3 = $codec3;
            $iax_host = $host;
            $iax_trunk = $trunk;

            $this->Data['iax_numero'] = $iax_numero;
            $this->Data['iax_senha'] = $iax_senha;
            $this->Data['iax_callerid'] = $iax_callerid;
            $this->Data['iax_codec1'] = $iax_codec1;
            $this->Data['iax_codec2'] = $iax_codec2;
            $this->Data['iax_codec3'] = $iax_codec3;
            $this->Data['iax_host'] = $iax_host;
            $this->Data['iax_trunk'] = $iax_trunk;

            $this->Data = array_map('strip_tags', $this->Data);
            $this->Data = array_map('trim', $this->Data);

            $this->Arr[$i] = $this->Data;

            $cont++;
        endfor;
    }

    /** Verifica a existencia de alguma duplicação. */
    private function setNome() {
        foreach ($this->Arr as $val):

            $Where = (!empty($this->Iax_id) ? "iax_id != {$this->Iax_id} AND" : '');

            $readName = new Read;
            $readName->ExeRead(self::Tabela, "WHERE {$Where} iax_numero = :s", "s={$val['iax_numero']}");

            if ($readName->getResult()):
                $this->Result = FALSE;
            else:
                $this->Result = TRUE;
            endif;

        endforeach;
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

        foreach ($this->Arr as $val):

            $create = new Create;
            $create->ExeCreate(self::Tabela, $val);

            if ($create->getResult()):
                $this->Result = $create->getResult();
            //$this->Erro = array("<b>Sucesso:</b> O foram cadastrado: {$contaCad} ramal(is) no sietema!", KL_ACCEPT);
            endif;

        endforeach;
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