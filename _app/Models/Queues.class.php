<?php

/**
 * Queues.class [ MODEL ]
 * Classe responsavel por realizar toda manutenção das Queues
 * @copyright (c) 04/09/2018, Kleber de Souza BRAZISTELECOM
 */
class Queues {

    private $Data;
    private $Queue_id;
    private $Erro;
    private $Result;
    private $ArqConf;
    private $NomeArquivo;

    //Nome da tabela no banco de dados.
    const Tabela = "queues";
    //Nome do diretorio e o arquivo .conf
    const Diretorio = "/etc/asterisk/queues_probilling.conf";
    //const Diretorio = "queues_probilling.conf";
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
            $this->Erro = array("Opa, você tentou cadastrar uma Queues que já esta cadastrado no sitema!", KL_ALERT);
        else:
            $this->Create();
        endif;
    }

    /**
     * Metodo responsagem por realizar alterações no ramal did     
     */
    public function ExeUpdate($queue_id, $data) {
        $this->Queue_id = (int) $queue_id;
        $this->Data = $data;        
        
        $this->setData();
        $this->Update();
    }

    /** Class resposavel por apagar ramal did */
    public function ExeDelete($queue_id) {
        $this->Queue_id = (int) $queue_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE $queue_id = :id", "id={$this->Queue_id}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover uma Queue que não existe no sistema!", KL_INFOR);
        else:
            $this->Delete();
        endif;
    }

    /** Montagem o arquivo .conf  */
    public function ExeConf($queue_id) {
        $this->Queue_id = (int) $queue_id;

        $this->setConf();
        $this->WriteConf();
    }

    /** Monta o arquivo .conf geral */
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

    /** Prepara os dados fila RamalSip ou AgentsList */
    private function setData() {
                
        if (!empty($this->Data['ramalSip'])):
            $arrRamal = $this->Data['ramalSip'];
       
            unset($this->Data['ramalSip']);

            $ramais = implode(', ', array_values($arrRamal));             
            $this->Data['queue_ramal'] = $ramais;            

        elseif (!empty($this->Data['agentsList'])):
            $arrAgents = $this->Data['agentsList'];
            unset($this->Data['agentsList']);

            $agents = implode(', ', array_values($arrAgents));
            $this->Data['queue_ramal'] = $agents;
        
        elseif(empty($this->Data['ramalSip']) || empty($this->Data['agentsList'])):
            $this->Data['queue_ramal'] = "";
        endif;

        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

    }

    /** Verifica a existencia de alguma duplicação. */
    private function setNome() {
        $Where = (!empty($this->Queue_id) ? "queue_id != {$this->Queue_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} queue_name = :s", "s={$this->Data['queue_name']}");

        if ($readName->getResult()):
            $this->Result = FALSE;
        else:
            $this->Result = TRUE;
        endif;
    }

    /** Busca e prepara todo o conteudo do arquivo .conf */
    private function setConf() {
        $readSip = new Read;
        $readSip->ExeRead(self::Tabela, "WHERE queue_id = :i", "i={$this->Queue_id}");
        $obj = $readSip->getResult();
        $this->Data = $obj[0];

        //Prepara a queue_ramal;        
        $queueRamal = explode(',', $this->Data['queue_ramal']);
        $queueRamal = array_map('trim', $queueRamal);

        $this->ArqConf = "";
        $this->ArqConf .= "\n[{$this->Data['queue_name']}]\n";
        $this->ArqConf .= "musiclass = default\n";
        $this->ArqConf .= "strategy={$this->Data['queue_strategy']}\n";
        $this->ArqConf .= "timeout={$this->Data['queue_timeout']}\n";
        $this->ArqConf .= "retry={$this->Data['queue_retry']}\n";
        $this->ArqConf .= "ringinuse={$this->Data['queue_ringinuse']}\n";
        $this->ArqConf .= "wrapuptime={$this->Data['queue_wrapuptime']}\n";
        $this->ArqConf .= "maxlen={$this->Data['queue_maxlen']}\n";
        $this->ArqConf .= "announce-frequency={$this->Data['queue_announce_frequency']}\n";
        $this->ArqConf .= "periodic-announce-frequency = 60\n";
        $this->ArqConf .= "announce-holdtime = once\n";
        $this->ArqConf .= "monitor-format = wav49\n";
        // member = Local/gulliver.nobrega@agents,,Gulliver Nobrega,Agent:gulliver.nobrega

        if ($this->Data['queue_tipo'] == "R"):

            for ($i = 0; $i < count($queueRamal); $i++):
                $this->ArqConf .= "member => SIP/{$queueRamal[$i]}\n";
            endfor;

        elseif ($this->Data['queue_tipo'] == "A"):
            $readA = new Read;
            for ($i = 0; $i < count($queueRamal); $i++):
                $readA->ExeRead("agents", "WHERE agent_user = :qr", "qr={$queueRamal[$i]}");
                $objAg = $readA->getResult();
                $agentMenber = $objAg[0];
                $this->ArqConf .= "member = Local/{$agentMenber['agent_user']}@agents,,{$agentMenber['agent_name']},Agent:{$agentMenber['agent_user']}\n";
            endfor;

        endif;
    }

    /** Busca todo o arquivo e prepara o conteudo do arquivo .conf geral */
    private function setConfGeral() {
        $readSip = new Read;
        $readSip->ExeRead(self::Tabela);

        foreach ($readSip->getResult() as $data):
            extract($data);

            //Prepara a queue_ramal;
            $queueRamal = explode(',', $queue_ramal);
            $queueRamal = array_map('trim', $queueRamal);

            $this->ArqConf[] = "";
            $this->ArqConf[] .= "\n[{$queue_name}]\n";
            $this->ArqConf[] .= "musiclass = default\n";
            $this->ArqConf[] .= "strategy={$queue_strategy}\n";
            $this->ArqConf[] .= "timeout={$queue_timeout}\n";
            $this->ArqConf[] .= "retry={$queue_retry}\n";
            $this->ArqConf[] .= "ringinuse={$queue_ringinuse}\n";
            $this->ArqConf[] .= "wrapuptime={$queue_wrapuptime}\n";
            $this->ArqConf[] .= "maxlen={$queue_maxlen}\n";
            $this->ArqConf[] .= "announce-frequency={$queue_announce_frequency}\n";
            $this->ArqConf[] .= "periodic-announce-frequency = 60\n";
            $this->ArqConf[] .= "announce-holdtime = once\n";
            $this->ArqConf[] .= "monitor-format = wav49\n";

            if ($queue_tipo == "R"):

                for ($i = 0; $i < count($queueRamal); $i++):
                    $this->ArqConf[] .= "member => SIP/{$queueRamal[$i]}\n";
                endfor;

            elseif ($queue_tipo == "A"):

                for ($i = 0; $i < count($queueRamal); $i++):
                    $readA = new Read;
                    $readA->ExeRead("agents", "WHERE agent_user = :au", "au={$queueRamal[$i]}");
                    $objAg = $readA->getResult();
                    $agentMember = $objAg[0];
                    $this->ArqConf[] .= "member = Local/{$agentMember['agent_user']}@agents,,{$agentMember['agent_name']},Agent:{$agentMember['agent_user']}\n";
                endfor;

            endif;

        endforeach;
        // var_dump($this->ArqConf);
    }

    /** Execulta a criação dos dados */
    private function Create() {
        $create = new Create;
        $create->ExeCreate(self::Tabela, $this->Data);

        if ($create->getResult()):
            $this->Result = $create->getResult();
            $this->Erro = array("<b>Sucesso:</b> O Queue {$this->Data['queue_name']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE queue_id = :id", "id=$this->Queue_id");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> O Queue {$this->Data['queue_name']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE queue_id = :id", "id={$this->Queue_id}");

        if ($deletar->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, Queue foi excluido do sistema!", KL_ACCEPT);
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel excluir o Queue do sistema!", KL_ERROR);
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
