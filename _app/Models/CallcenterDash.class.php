<?php

/**
 * CallcenterDash.class [ MODEL ]
 * Classe responsavel por gerenciar todo o processo de visualização das lista de: 
 * Campanhas ativas, 
 * Quees,
 * Todos os Agentes da queues
 * 
 * @copyright (c) 30/01/2019, Kleber de Souza BRAZISTELECOM
 */
class CallcenterDash {

    /** Atributos da classe */
    //private $Result;
    private $Campanha;
    private $Campanha_id;
    private $Queues;
    private $Queue_name;
    //    
    private $Agents;
    private $AgentsUser;
    private $AgentsQueues;
    private $Agents_status_Queues;
    private $Data;
    private $DataStatus;
    private $Total_agent_pause = 0;
    private $Total_Deslogado = 0;
    private $Total_Disponivel = 0;
    private $Total_EmAtendimento = 0;
    public $ValorGrfA;
    //
    private $StatusQ;
    private $ResultCampanha;
    private $DataGrafico;

    //Constante com as tabela no banco de dados.
    const TabCampanha = "campanha";
    const TabQueues = "queues";
    const TabAgents = "agents";
    const TabAgentsStatus = "agents_status";
    const TabQueuesFila = "queues_fila";

    /**
     * Metodo resposavel por busca da campanha selecionada
     * 
     * @param type $campanha_id
     */
    public function ExeCampanha($campanha_id) {
        $this->Campanha_id = (int) $campanha_id;

        $this->ReadCampanha();
        $this->setQueues();
        $this->ReadQueues();

        $this->setSelectQueues();
        $this->ReadAgentsQueues();

        $this->ReadAgentsStatusQueues();
        $this->setStatusAgentQueues();


        //$this->totalEstadoAgents();
        $this->setTotalPause();
        $this->grfStatusAgentsQ();
        
    }
 
    /**
     * Retorna o resultado da Campanha 
     */
    public function getCampanha() {
        return $this->Campanha;
    }

    /**
     * Retorna o resultado Agents Id e o tipo da pausa do Agente 
     */
    public function getQueues() {
        return $this->Queues;
    }

    /**
     * Retorna o resultado Agents das queues
     * @return type Array
     */
    public function getAgentsQueues() {
        return $this->AgentsQueues;
    }

    /**
     * Retorna o resultado dos estatos dos agentes das queues
     * @return type Array
     */
    public function getStatusQueues() {
        return $this->StatusQ;
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
//    public function getGrfStatusAgentsSelected() {   
//        //$this->setStatusGrf();
//        //$this->grfStatusAgentsQ();
//        varDump::exeVD($this->ValorGrfA);
////        exit();
//        return $this->ValorGrfA;        
//    }

    /**
     * Retorna o Resultado filas do metodo ReadQueuesFila() 
     */
    public function getFilas() {
        $this->ReadQueuesFila();
        return $this->Filas;
    }

    /**
     * Retorna o resultado geral da Campanha
     * @return type Array
     */
    public function getResultCampanha() {
        return $this->ResultCampanha;
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */

    /**
     * Realiza a busca da Campanha Solicitada 
     */
    private function ReadCampanha() {
        $Campos = "campanha_id, campanha_tipo, campanha_nome, campanha_destino_tipo, campanha_destino_complemento, campanha_agenda, campanha_status";
        $Termos = "WHERE campanha_id = :id AND campanha_tipo = 'D' AND campanha_status = 'A'";
        $select = new Select;
        $select->ExeSelect(self::TabCampanha, $Campos, $Termos, "id={$this->Campanha_id}");

        $this->Campanha = $select->getResult();

        ## Array Geral
        //$this->ResultCampanha['campanha'] = $this->Campanha[0];
    }

    /**
     * Metodo responsavel por buscar a queues da campanha 
     */
    public function setQueues() {
        $this->Queue_name = $this->Campanha[0]['campanha_destino_complemento'];

        ## Array Geral
        $this->ResultCampanha['queue'] = $this->Queue_name;
    }

    /**
     * Realiza a busca da Queues referente a Campanha Solicitada 
     */
    private function ReadQueues() {

        $Campos = "queue_id, queue_name, queue_tipo, queue_ramal";
        $Termos = "WHERE queue_name = :qn AND queue_tipo = 'A'";
        $select = new Select;
        $select->ExeSelect(self::TabQueues, $Campos, $Termos, "qn={$this->Queue_name}");

        $this->Queues = $select->getResult();
    }

    /**
     * ==============================================================
     * Seleciona e prepara um array c/ todos agentes da queues
     * ==============================================================
     */
    private function setSelectQueues() {

        $objQueues = $this->Queues[0];

        if (!empty($objQueues['queue_ramal'])):

            ## Divide em arrays os agentes
            $explode = explode(',', $objQueues['queue_ramal']);

            for ($i = 0; $i < count($explode); $i++):
//                $data[] = array('agent_user' => trim($explode[$i]));
                $data['agent_user'] = trim($explode[$i]);
                $this->Data[] = $data;
            endfor;

        endif;
    }

    /**
     * Seleciona todas a pausa dos agente das queues preparadas com a setSelectQueues
     */
    private function ReadAgentsQueues() {

        foreach ($this->Data as $value):

            $select = new Select();
            $select->ExeSelect(self::TabAgents, "agent_id, agent_user, agent_name, agent_pause, agent_pause_date", "WHERE agent_user = '{$value['agent_user']}' ");
            $result[] = $select->getResult();

        endforeach;

        $this->AgentsQueues = $result;        
        
    }

    /**
     * Seleciona todos os Status dos agentes referente a queues 
     */
    private function ReadAgentsStatusQueues() {

        foreach ($this->AgentsQueues as $value):

            $read = new Read();
            $read->ExeRead(self::TabAgentsStatus, "WHERE agente = '{$value[0]['agent_user']}'");
            $this->Ags[] = $read->getResult();

        endforeach;
        $this->Agents_status_Queues = $this->Ags;
        
    }

    /**
     * Prepara todo o status de cada agents e retorna um array status Selecionados
     */
    private function setStatusAgentQueues() {

        ## Agents_status ##
        if (!empty($this->Agents_status_Queues)):
            foreach ($this->Agents_status_Queues as $valorAgentStatusQ):


                ## Verifica os agentes ##              
                if (!empty($this->AgentsQueues)):
                    foreach ($this->AgentsQueues as $valorAgents):
                        
                        ## Verifica a existencia do agent_pause e se o agent_user e igual ao agente ##
                        if (!empty($valorAgents[0]['agent_pause']) && $valorAgents[0]['agent_user'] === $valorAgentStatusQ[0]['agente'] && $valorAgentStatusQ[0]['status'] != "UNAVAILABLE" && $valorAgentStatusQ[0]['status'] != "Deslogado"):

                            ## tipo da Pausa  
                            $valorAgentStatusQ[0]['status'] = "Em Pausa";
                            $valorAgentStatusQ[0]['pausa_manual'] = $valorAgents[0]['agent_pause'];
                            
                            //função para pegar diferença de data da pause e data atual
                            $date1 = new DateTime($valorAgents[0]['agent_pause_date']);
                            $date2 = new DateTime("now");
                            $diff = date_diff($date2, $date1);
                            $valorAgentStatusQ[0]['tempo'] = $diff->format("%H:%I:%S");

                        else:

                            ## Analiza e altera cor dos tipos de status ##
                            if (!empty($valorAgentStatusQ[0]['status']) && $valorAgentStatusQ[0]['status'] === "UNAVAILABLE"):
                                ## cor #90C            
                                $valorAgentStatusQ[0]['status'] = "Deslogado";
                                $valorAgentStatusQ[0]['pausa_manual'] = (!empty($valorAgents[0]['agent_pause']) && $valorAgents[0]['agent_user'] == $valorAgentStatusQ[0]['agente']) ? $valorAgents[0]['agent_pause'] : null;
                                $valorAgentStatusQ[0]['tempo'] = "";
                                
                            elseif (!empty($valorAgentStatusQ[0]['status']) && $valorAgentStatusQ[0]['status'] === "NOT_INUSE"):
                                ## cor #093                            
                                $valorAgentStatusQ[0]['status'] = "Disponível";
                                $valorAgentStatusQ[0]['pausa_manual'] = (!empty($valorAgents[0]['agent_pause']) && $valorAgents[0]['agent_user'] == $valorAgentStatusQ[0]['agente']) ? $valorAgents[0]['agent_pause'] : null;
                                $valorAgentStatusQ[0]['tempo'] = "";                                
                                
                            elseif (!empty($valorAgentStatusQ[0]['status']) && $valorAgentStatusQ[0]['status'] === "INUSE"):
                                ## cor #06C               
                                $valorAgentStatusQ[0]['status'] = "Em Atendimento";
                                $valorAgentStatusQ[0]['pausa_manual'] = (!empty($valorAgents[0]['agent_pause']) && $valorAgents[0]['agent_user'] == $valorAgentStatusQ[0]['agente']) ? $valorAgents[0]['agent_pause'] : null;
                                $valorAgentStatusQ[0]['tempo'] = "";
                                
                            endif;
                        endif;
                    endforeach;

                endif;

                $this->StatusQ[] = $valorAgentStatusQ;

            endforeach;

        endif;

        ## Array Geral
        //$this->ResultCampanha["agentes"] = $this->StatusQ;
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
     * Contagem dos estados dos agentes
     */
    private function totalEstadoAgents() {

        if (!empty($this->StatusQ)):

            foreach ($this->StatusQ as $statusQ):

                //Pega total de agentes Indisponível (Deslogado)
                if ($statusQ[0]['status'] == "Deslogado"):
                    $this->Total_Deslogado = $this->Total_Deslogado + 1;
                endif;

                //Pega total de agentes Disponivel
                if ($statusQ[0]['status'] == "Disponível"):
                    $this->Total_Disponivel = $this->Total_Disponivel + 1;
                endif;

                //Pega total de agentes Em Atendimento
                if ($statusQ[0]['status'] == "Em Atendimento"):
                    $this->Total_EmAtendimento = $this->Total_EmAtendimento + 1;
                endif;

                //Pega total de agentes Em pausa
                if ($statusQ[0]['status'] == "Em Pausa"):
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

//    private function setStatusGrf() {
//                
//        $tEmAtendimento = $this->DataGrafico['tEmAtendimento'];
//        $tPause = $this->DataGrafico['tPause'];
//        $tDiscando = $this->DataGrafico['tDiscando'];
//        $tDisponivel = $this->DataGrafico['tDisponivel'];
//
//        //unset($this->DataGrafico);
//        
//        $this->DataStatus['tEmAtendimento'] = $tEmAtendimento;
//        $this->DataStatus['tPause'] = $tPause;
//        $this->DataStatus['tDiscando'] = $tDiscando;
//        $this->DataStatus['tDisponivel'] = $tDisponivel;       
//        
//        
//    }
    

    /**
     * Monta o grafico dos Status dos Agents 
     */
    private function grfStatusAgentsQ() {

        //$this->setTotalPause();        
         $disponivelGrf = $this->DataStatus['tDisponivel'];

        //PARA GRAFICO  
        //$valor[] = array('tipo' => "Deslogado", 'total' => $this->DataStatus['tDeslogado']);
        $valor[] = array('tipo' => "Em Atendimento", 'total' => $this->DataStatus['tEmAtendimento']);
        $valor[] = array('tipo' => "Em Pause", 'total' => $this->DataStatus['tPause']);
        $valor[] = array('tipo' => "Discando", 'total' => $this->DataStatus['tDiscando']);
        $valor[] = array('tipo' => "Disponivel", 'total' => $this->DataStatus['tDisponivel']);
        $valor[] = array('tipo' => "Deslogado", 'total' => $this->DataStatus['tDeslogado']);

        $this->ValorGrfA = $valor;
        //varDump::exeVD($this->ValorGrfA);  
        //$obj = new AgentGrf;
       // $obj->ExeGrafico($this->ValorGrfA);
        //header("location: ?exe=relatorio/callcenter/dashboard/lista&valor={$this->ValorGrfA}");

    }

}
