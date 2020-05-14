<?php

/**
 * Agent.class [ MODEL ]
 * Classe responsavel por realizar cadastro, alteração e listagem de dados dos Agentes. 
 * @copyright (c) 27/11/2018, Kleber de Souza BRAZISTELECOM
 */
class Agent {

    private $Data;
    private $Agent_id;
    private $Erro;
    private $Result;
    private $Conta;

    //Nome da tabela no banco de dados.
    const Tabela = "agents";
    //Nome do diretorio e o arquivo .conf
    const Diretorio = "/etc/asterisk/agents_probilling.conf";
    //const Diretorio = "agents_probilling.conf";

    /**
     * Metodo inicial, responsavel por executar os dados para ramal agent   
     */
    public function ExeCreate(array $data) {
        $this->Data = $data;

        $this->setData();
        $this->setNome();
        if (!$this->Result):
            $this->Result = TRUE;
            $this->Erro = array("Opa, você tentou cadastrar um usuário: <b>{$this->Data['agent_user']}</b>, que já existe no sistema!", KL_ALERT);
        else:
            $this->Create();
        endif;
    }

    /**
     * Metodo responsagem por realizar alterações no ramal agent     
     */
    public function ExeUpdate($agent_id, $data) {
        $this->Agent_id = (int) $agent_id;
        $this->Data = $data;

        $this->setData();
        $this->Update();
    }

    /**
     * Metodo responsavel por realizar alterações na pesquisa search     
     */
    public function ExeUpdateSearch($agent_id, $data) {
        $this->Agent_id = (int) $agent_id;
        $this->Data = $data;

        $this->setData();
        $this->Update();
    }

    /** Class resposavel por apagar ramal agent */
    public function ExeDelete($agent_id) {
        $this->Agent_id = (int) $agent_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE agent_id = :id", "id={$this->Agent_id}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover um Agent que não existe no sistema!", KL_INFOR);
        else:
            $this->Delete();
        endif;
    }

    /** Monta o arquivo .conf  */
    public function ExeConf($agent_id) {
        $this->Agent_id = (int) $agent_id;

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

        $senha = (!empty($this->Data['agent_pass'])) ? $this->Data['agent_pass'] : null;
        unset($this->Data['agent_pass']);

        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

        if (!empty($senha)):
            $senhaMd5 = md5($senha);
            $this->Data['agent_pass'] = $senhaMd5;
        endif;
    }

    /** Busca e prepara todo o conteudo do arquivo .conf */
    private function setConf() {
        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE agent_id = :i", "i={$this->Agent_id}");
        $obj = $read->getResult();
        $this->Data = $obj[0];

        $this->ArqConf = "  
[{$this->Data['agent_user']}](probilling)
fullname={$this->Data['agent_name']}
        ";
    }

    /** Busca todo o arquivo e prepara o conteudo do arquivo .conf geral */
    private function setConfGeral() {
        $read = new Read;
        $read->ExeRead(self::Tabela);

        $this->ArqConf[] = "
[probilling](!)                         
ackcall=no
;acceptdtmf=#
autologoff=20
        ";

        foreach ($read->getResult() as $data):
            extract($data);
            $this->ArqConf[] = "                    
[{$agent_user}](probilling)
fullname={$agent_name}
        ";
        endforeach;
    }

    /** Verifica a existencia de alguma duplicação. */
    private function setNome() {
        $Where = (!empty($this->Agent_id) ? "agent_id != {$this->Agent_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} agent_user = :a", "a={$this->Data['agent_user']}");

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
            $this->Erro = array("<b>Sucesso:</b> O Agent {$this->Data['agent_user']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE agent_id = :id", "id=$this->Agent_id");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> O Agent {$this->Data['agent_name']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE agent_id = :id", "id={$this->Agent_id}");

        if ($deletar->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, Agent foi excluido do sistema!", KL_ACCEPT);
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel excluir o Agent do sistema!", KL_ERROR);
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
