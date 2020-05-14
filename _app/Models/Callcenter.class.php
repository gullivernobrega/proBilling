<?php

/**
 * Callcenter.class [ MODEL ]
 * Classe responsavel por gerenciar todo o processo de visualização dos call center
 * @copyright (c) 30/01/2019, Kleber de Souza BRAZISTELECOM
 */
class Callcenter {

    /** Atributos da classe */
    private $Result;
    private $Filas;
    private $Agents;
    private $Status;    
    private $Agents_status;  
    private $DataStatus;
    private $TotalAgents;
    private $Total_agent_pause = 0;
    private $Total_Deslogado = 0;
    private $Total_Disponivel = 0;
    private $Total_EmAtendimento = 0;
    private $ValorGrf;

    ## Constante com as tabela no banco de dados. ##

    const TabAgents = "agents";
    const TabAgentsStatus = "agents_status";
    const TabQueuesFila = "queues_fila";

    /** Construtor da Classe */
    public function __construct() {
        $this->ReadAgents();        
        $this->ReadAgentsStatus();
        $this->setStatusAgent();
        $this->grfStatusAgents();
        $this->ReadQueuesFila();

    }

    /**
     * Retorna o resultado Agents Id e o tipo da pausa do Agente 
     */
    public function getAgents() {
        return $this->Agents;
    }

    /**
     * Retorna todos dados com resultados dos Agents Status  
     */
    public function getAgentsStatus() {
        return $this->Agents_status;
    }

    /**
     * Retorna todos resultados do Agents Status  
     */
    public function getStatus() {
        return $this->Status;
    }

    /**
     * Retorna o total para cada estado dos Status Agents  
     */
    public function getTotalPause() {
        return $this->DataStatus;
    }

    /**
     * Retorna o dados para grafico Status Agents  
     */
    public function getGrfStatusAgents() {
        return $this->ValorGrf;
    }

    /**
     * Retorna o Resultado filas do metodo ReadQueuesFila() 
     */
    public function getFilas() {
        return $this->Filas;
    }
    
    

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */

    /**
     * Seleciona todos os agente 
     */
    private function ReadAgents() {
        $select = new Select;
        $select->ExeSelect(self::TabAgents, "agent_id, agent_user, agent_name, agent_pause, agent_pause_date");
        $this->Agents = $select->getResult();
        
        //varDump::exeVD($this->Agents); exit;
    }


    /**
     * Seleciona todos os Status dos agentes 
     */
    private function ReadAgentsStatus() {
        $read = new Read();
        $read->ExeRead(self::TabAgentsStatus);
        $this->Agents_status = $read->getResult();
    }


    /**
     * Seleciona todas as filas 
     */
    private function ReadQueuesFila() {
        $read = new Read();
        $read->ExeRead(self::TabQueuesFila);
        $this->Filas = $read->getResult();
    }

    /**
     * Prepara todo o status de cada agents e retorna um array status
     */
    private function setStatusAgent() {
        
        ## Agents_status ##
        if (!empty($this->Agents_status)):

            foreach ($this->Agents_status as $valorAgentStatus):

                //Verifica os agentes
                if (!empty($this->Agents)):

                    foreach ($this->Agents as $valorAgent):
                        //Verifica a existencia do agent_pause e se o agent_user e igual ao agente
                        if (!empty($valorAgent['agent_pause']) && $valorAgent['agent_user'] == $valorAgentStatus['agente'] && $valorAgentStatus['status'] != "UNAVAILABLE" && $valorAgentStatus['status'] != "Deslogado"):
                            //tipo da Pausa  
                            $valorAgentStatus['status'] = "Em Pausa";
                            $valorAgentStatus['pausa_manual'] = $valorAgent['agent_pause'];
                            //função para pegar diferença de data da pause e data atual
                            $date1 = new DateTime($valorAgent['agent_pause_date']);
                            $date2 = new DateTime("now");
                            $diff = date_diff($date2, $date1);
                            $valorAgentStatus['pause_date'] = $diff->format("%H:%I:%S");
                            
                        else:

                            /* Analiza e altera cor dos tipos de status */
                            if (!empty($valorAgentStatus['status']) && $valorAgentStatus['status'] === "UNAVAILABLE"):
                                //cor #90C            
                                $valorAgentStatus['status'] = "Deslogado";
                                $valorAgentStatus['pausa_manual'] = (!empty($valorAgent['agent_pause']) && $valorAgent['agent_user'] == $valorAgentStatus['agente']) ? $valorAgent['agent_pause'] : null;
                                $valorAgentStatus['pause_date'] = "";
                                
                            elseif (!empty($valorAgentStatus['status']) && $valorAgentStatus['status'] === "NOT_INUSE"):
                                // cor #093                            
                                $valorAgentStatus['status'] = "Disponível";
                                $valorAgentStatus['pausa_manual'] = (!empty($valorAgent['agent_pause']) && $valorAgent['agent_user'] == $valorAgentStatus['agente']) ? $valorAgent['agent_pause'] : null;
                                $valorAgentStatus['pause_date'] = "";
                                
                            elseif (!empty($valorAgentStatus['status']) && $valorAgentStatus['status'] === "INUSE"):
                                //cor #06C               
                                $valorAgentStatus['status'] = "Em Atendimento";
                                $valorAgentStatus['pausa_manual'] = (!empty($valorAgent['agent_pause']) && $valorAgent['agent_user'] == $valorAgentStatus['agente']) ? $valorAgent['agent_pause'] : null;
                                $valorAgentStatus['pause_date'] = "";
                                
                            endif;
                        endif;
                    endforeach;

                endif;

                $this->Status[] = $valorAgentStatus;

            endforeach;

        endif;
    }
   
    
    /**
     * Contagem dos estados dos agentes
     */
    private function totalEstadoAgents() {
        if (!empty($this->Status)):

            foreach ($this->Status as $status):
                //Pega total de agentes Indisponível (Deslogado)
                if ($status['status'] == "Deslogado"):
                    $this->Total_Deslogado = $this->Total_Deslogado + 1;
                endif;

                //Pega total de agentes Disponivel
                if ($status['status'] == "Disponível"):
                    $this->Total_Disponivel = $this->Total_Disponivel + 1;
                endif;

                //Pega total de agentes Em Atendimento
                if ($status['status'] == "Em Atendimento"):
                    $this->Total_EmAtendimento = $this->Total_EmAtendimento + 1;
                endif;

                //Pega total de agentes Em pausa
                if ($status['status'] == "Em Pausa"):
                    $this->Total_agent_pause = $this->Total_agent_pause + 1;
                endif;

            endforeach;

        endif;
    }

    /**
     * Monta um array com o total de Agent_pause 
     */
    private function setTotalPause() {
        $this->totalEstadoAgents();
        //inicializa o total de pause        
        $this->DataStatus['tEmAtendimento'] = $this->Total_EmAtendimento;
        $this->DataStatus['tPause'] = $this->Total_agent_pause;
        $this->DataStatus['tDisponivel'] = $this->Total_Disponivel;
        $this->DataStatus['tDiscando'] = 0;
        $this->DataStatus['tDeslogado'] = $this->Total_Deslogado;
    }

    /**
     * Monta o grafico dos Status dos Agents 
     */
    private function grfStatusAgents() {

        $this->setTotalPause();

        //PARA GRAFICO  
        $valor[] = array('tipo' => "Em Atendimento", 'total' => $this->DataStatus['tEmAtendimento']);
        $valor[] = array('tipo' => "Em Pause", 'total' => $this->DataStatus['tPause']);
        $valor[] = array('tipo' => "Discando", 'total' => $this->DataStatus['tDiscando']);
        $valor[] = array('tipo' => "Disponivel", 'total' => $this->DataStatus['tDisponivel']);
        //$valor[] = array('tipo' => "Deslogado", 'total' => $this->DataStatus['tDeslogado']);

        $this->ValorGrf = $valor;
    }

}
