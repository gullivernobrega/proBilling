<?php

/**
 * Agentsstatus.class [ HELPER ]
 * Classe responsavel por preparar os status dos agentes.
 * @copyright (c) 08/02/2019, Kleber de Souza BRAZISTELECOM
 */
class Agentsstatus {

    /** Atributos */
    private $Data;
    private $Agent_id;
    private $Agent_user;
    private $Agent_pause;
    private $Tipo_pause;
    private $Pause;
    private $Despause;
    private $Result;
    private $Json;
    private $Asmanager;

    //Constante com as tabela no banco de dados.
    const TabAgents = "agents";
    const TabAgentsStatus = "agents_status";

    /**
     * Metodo para busca de dados do agents
     * 
     * @param type INT $id
     */
    public function exeAgents($id, $user, $maneger) {
        $this->Agent_id = (int) $id;
        $this->Agent_user = $user;
        $this->Asmanager = $maneger;
    }

    /**
     * Metodo responsagem por executar o agent_status
     * 
     * @param String $user
     * @param String $tipo
     */
    public function exeAgentStatus() {
        $this->setAgentStatus();
    }

    /**
     * Metodo responsavel por Retornar o resultado pode ser um array ou String
     *  
     * @return type Array ou String
     */
    public function getResult() {
        return $this->Result;
    }

    public function getAgents() {
        $this->setAgents();
        return $this->Agent_pause;
    }

    public function getPause() {
        return $this->Pause;
    }

    public function getDespause() {
        return $this->Despause;
    }

    public function getJson() {
        return $this->jsonDecode();
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
    private function setAgents() {

        $selectPause = new Select;
        $selectPause->ExeSelect(self::TabAgents, "agent_pause", "WHERE agent_id = :id", "id={$this->Agent_id}");
        $pause = $selectPause->getResult();
        extract($pause[0]);

        $this->Agent_pause = $agent_pause;
    }

    private function setAgentStatus() {

        //Seleciona Dados do agent_status
        $dataAgentStatus = new Read;
        $dataAgentStatus->ExeRead("agents_status", "WHERE agente = :ag", "ag={$this->Agent_user}");
        $result = $dataAgentStatus->getResult();

        if (!empty($result)):
            $arr = $result[0];

            # Se existir uma pausa
            if (!empty($this->Agent_pause)):

                # Analisa se a pausa e igual a Evento
                if ($this->Agent_pause == "Evento"):

                    $arr['status'] = "PAUSE - {$this->Agent_pause}";
                    # cor Evento
                    $arr['fundo'] = "background-color: #9980FA;";

                else:

                    $arr['status'] = "PAUSE - {$this->Agent_pause}"; //cor laranja
                    # cor outras pausa
                    $arr['fundo'] = "background-color: #FFCC66;";

                endif;

                //Comando Pause:                
                $this->pauseAgent();

                $this->Result = $arr;

            else:

                /* Comando despausar */
                $this->despauseAgent();

                /* Analiza e altera cor dos tipos de status */
                if (!empty($arr['status']) && $arr['status'] === "UNAVAILABLE"):
                    $arr['status'] = "INDISPONÍVEL"; //cor vermelho
                    $arr['fundo'] = "background-color: red;";
                elseif (!empty($arr['status']) && $arr['status'] === "NOT_INUSE"):
                    $arr['status'] = "DISPONÍVEL"; // cor verde
                    $arr['fundo'] = "background-color: #06b70d;";
                elseif (!empty($arr['status']) && $arr['status'] === "INUSE"):
                    $arr['status'] = "OCUPADO"; //cor amarelo claro 
                    $arr['fundo'] = "background-color: #FF9933;";
                endif;

                $this->Result = $arr;

            endif;

        endif;
    }

    /** Metodo para pausar o agent no asterisk */
    private function pauseAgent() {
        $this->Pause = $this->Asmanager->Command("queue pause member Local/$this->Agent_user@agents");
    }

    /** Metodo para despausar o agent no asterisk */
    private function despauseAgent() {
        $this->Despause = $this->Asmanager->Command("queue unpause member Local/$this->Agent_user@agents");
    }

    private function jsonDecode() {
//        $arr[] = $this->Result;        
//        echo json_encode($arr, JSON_PRETTY_PRINT);
    }

}
