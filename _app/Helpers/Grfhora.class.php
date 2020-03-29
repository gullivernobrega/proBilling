<?php

/**
 * Grfhora.class [ HEPER ]
 * Classe responsavel por retornar os dados para montagem do gráfico por hora
 * @copyright (c) 31/07/2018, Kleber de Souza BRAZISTELECOM
 */
class Grfhora {

    /** Atributos data e horas */
    private $Das08as10;
    private $Das11as13;
    private $Das14as16;
    private $Das17as19;
    private $Das20as22;

    /** Atributos  */
    private $Atendidas;
    private $NaoAtendidas;
    private $Invalidas;
    private $Indisponivel;
    private $Result;
    private $Error;

    //Nome da tabela no banco de dados.
    const Tabela = "cdr";
    const Campos = "COUNT(dst) AS val";

    /**
     * Metodo inicial, responsavel por ligações atendidas   
     */
    public function ExeAtendidas() {        
        $this->GrfAtendida();   
    }
    
    /**
     * Metodo inicial, responsavel por ligações não atendidas   
     */
    public function ExeNaoAtendidas() {        
        $this->GrfNaoAtendida();   
    }

    /**
     * Metodo inicial, responsavel por ligações indisponivel   
     */
    public function ExeIndisponivel() {        
        $this->GrfIndisponivel();   
    }
    
    /**
     * Metodo inicial, responsavel por ligações invalidas   
     */
    public function ExeInvalidas() {        
        $this->GrfInvalidas();   
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
    private function setDataHora() {
        //Data atual
        $data = date("Y-m-d");
        //$data = date("2018-05-17");

        $this->Das08as10 = "calldate >= '{$data} 08:00:00' AND calldate <= '{$data} 10:59:00'";
        $this->Das11as13 = "calldate >= '{$data} 11:00:00' AND calldate <= '{$data} 13:59:00'";
        $this->Das14as16 = "calldate >= '{$data} 14:00:00' AND calldate <= '{$data} 16:59:00'";
        $this->Das17as19 = "calldate >= '{$data} 17:00:00' AND calldate <= '{$data} 19:59:00'";
        $this->Das20as22 = "calldate >= '{$data} 20:00:00' AND calldate <= '{$data} 22:59:00'";        
        
    }
    
    /**
     * Retorna todos as ligações atendidas
     */
    private function GrfAtendida() {
        $this->setDataHora();
        
        //Retorna lligações atendidas das 08 as 10
        $select = new Select;
        $select->ExeSelect(self::Tabela, self::Campos, "WHERE $this->Das08as10 AND disposition = 'ANSWERED'");
        $Atendidas1 = $this->Result = $select->getResult(); 
        $this->Atendidas['at8as10'] = (int) $Atendidas1[0]['val'];         
        
        //Retorna lligações atendidas das 11 as 13
        $select->ExeSelect(self::Tabela, self::Campos, "WHERE $this->Das11as13 AND disposition = 'ANSWERED'");
        $Atendidas2 = $this->Result = $select->getResult();        
        $this->Atendidas['at11as13'] = (int) $Atendidas2[0]['val'];  
        
        //Retorna lligações atendidas das 14 as 16
        $select->ExeSelect(self::Tabela, self::Campos, "WHERE $this->Das14as16 AND disposition = 'ANSWERED'");
        $Atendidas3 = $this->Result = $select->getResult();        
        $this->Atendidas['at14as16'] = (int) $Atendidas3[0]['val'];  
        
        //Retorna lligações atendidas das 17 as 19
        $select->ExeSelect(self::Tabela, self::Campos, "WHERE $this->Das17as19 AND disposition = 'ANSWERED'");
        $Atendidas4 = $this->Result = $select->getResult();
        $this->Atendidas['at17as19'] = (int) $Atendidas4[0]['val'];  
        
        //Retorna lligações atendidas das 20 as 22
        $select->ExeSelect(self::Tabela, self::Campos, "WHERE $this->Das20as22 AND disposition = 'ANSWERED'");
        $Atendidas5 = $this->Result = $select->getResult();        
        $this->Atendidas['at20as22'] = (int) $Atendidas5[0]['val']; 
        
        $this->Result = $this->Atendidas;
    }

    /** 
     * Metodo responsagem por todas ligaçoes não atendidas NO ANSWER
     */
    private function GrfNaoAtendida() {
        $this->setDataHora();
        
        //Retorna lligações atendidas das 08 as 10
        $select = new Select;
        $select->ExeSelect(self::Tabela, self::Campos, "WHERE $this->Das08as10 AND (disposition = 'CANCEL' || disposition = 'NO ANSWER')");
        $NaoAtendidas1 = $this->Result = $select->getResult();        
        $this->NaoAtendidas['Nat8as10'] = (int) $NaoAtendidas1[0]['val'];  
        
        //Retorna lligações atendidas das 11 as 13
        $select->ExeSelect(self::Tabela, self::Campos, "WHERE $this->Das11as13 AND (disposition = 'CANCEL' || disposition = 'NO ANSWER')");
        $NaoAtendidas2 = $this->Result = $select->getResult();        
        $this->NaoAtendidas['Nat11as13'] = (int) $NaoAtendidas2[0]['val'];  
        
        //Retorna lligações atendidas das 14 as 16
        $select->ExeSelect(self::Tabela, self::Campos, "WHERE $this->Das14as16 AND (disposition = 'CANCEL' || disposition = 'NO ANSWER')");
        $NaoAtendidas3 = $this->Result = $select->getResult();        
        $this->NaoAtendidas['Nat14as16'] = (int) $NaoAtendidas3[0]['val'];  
        
        //Retorna lligações atendidas das 17 as 19
        $select->ExeSelect(self::Tabela, self::Campos, "WHERE $this->Das17as19 AND (disposition = 'CANCEL' || disposition = 'NO ANSWER')");
        $NaoAtendidas4 = $this->Result = $select->getResult();
        $this->NaoAtendidas['Nat17as19'] = (int) $NaoAtendidas4[0]['val'];  
        
        //Retorna lligações atendidas das 20 as 22
        $select->ExeSelect(self::Tabela, self::Campos, "WHERE $this->Das20as22 AND (disposition = 'CANCEL' || disposition = 'NO ANSWER')");        
        $NaoAtendidas5 = $this->Result = $select->getResult();        
        $this->NaoAtendidas['Nat20as22'] = (int) $NaoAtendidas5[0]['val']; 
        
        $this->Result = $this->NaoAtendidas;
        
    }

    /** 
     * Metodo responsagem por todas ligaçoes indisponivel CONGESTION 
     */
    private function GrfIndisponivel() {
        $this->setDataHora();
        
        //Retorna lligações atendidas das 08 as 10
        $select = new Select;
        $select->ExeSelect(self::Tabela, self::Campos, "WHERE $this->Das08as10 AND disposition = 'CONGESTION'");
        $Indisponivel1 = $this->Result = $select->getResult();        
        $this->Indisponivel['Ind8as10'] = (int) $Indisponivel1[0]['val'];  
        
        //Retorna lligações atendidas das 11 as 13
        $select->ExeSelect(self::Tabela, self::Campos, "WHERE $this->Das11as13 AND disposition = 'CONGESTION'");
        $Indisponivel2 = $this->Result = $select->getResult();        
        $this->Indisponivel['Ind11as13'] = (int) $Indisponivel2[0]['val'];  
        
        //Retorna lligações atendidas das 14 as 16
        $select->ExeSelect(self::Tabela, self::Campos, "WHERE $this->Das14as16 AND disposition = 'CONGESTION'");
        $Indisponivel3 = $this->Result = $select->getResult();        
        $this->Indisponivel['Ind14as16'] = (int) $Indisponivel3[0]['val'];  
        
        //Retorna lligações atendidas das 17 as 19
        $select->ExeSelect(self::Tabela, self::Campos, "WHERE $this->Das17as19 AND disposition = 'CONGESTION'");
        $Indisponivel4 = $this->Result = $select->getResult();
        $this->Indisponivel['Ind17as19'] = (int) $Indisponivel4[0]['val'];  
        
        //Retorna lligações atendidas das 20 as 22
        $select->ExeSelect(self::Tabela, self::Campos, "WHERE $this->Das20as22 AND disposition = 'CONGESTION'");        
        $Indisponivel5 = $this->Result = $select->getResult();        
        $this->Indisponivel['Ind20as22'] = (int) $Indisponivel5[0]['val']; 
        
        $this->Result = $this->Indisponivel;
        
    }
  
    /** 
     * Metodo responsagem por todas ligaçoes invalidas FAILED 
     */
    private function GrfInvalidas() {
        $this->setDataHora();
        
        //Retorna lligações atendidas das 08 as 10
        $select = new Select;
        $select->ExeSelect(self::Tabela, self::Campos, "WHERE $this->Das08as10 AND disposition = 'FAILED'");
        $Invalidas1 = $this->Result = $select->getResult();        
        $this->Invalidas['Inv8as10'] = (int) $Invalidas1[0]['val'];  
        
        //Retorna lligações atendidas das 11 as 13
        $select->ExeSelect(self::Tabela, self::Campos, "WHERE $this->Das11as13 AND disposition = 'FAILED'");
        $Invalidas2 = $this->Result = $select->getResult();        
        $this->Invalidas['Inv11as13'] = (int) $Invalidas2[0]['val'];  
        
        //Retorna lligações atendidas das 14 as 16
        $select->ExeSelect(self::Tabela, self::Campos, "WHERE $this->Das14as16 AND disposition = 'FAILED'");
        $Invalidas3 = $this->Result = $select->getResult();        
        $this->Invalidas['Inv14as16'] = (int) $Invalidas3[0]['val'];  
        
        //Retorna lligações atendidas das 17 as 19
        $select->ExeSelect(self::Tabela, self::Campos, "WHERE $this->Das17as19 AND disposition = 'FAILED'");
        $Invalidas4 = $this->Result = $select->getResult();
        $this->Invalidas['Inv17as19'] = (int) $Invalidas4[0]['val'];  
        
        //Retorna lligações atendidas das 20 as 22
        $select->ExeSelect(self::Tabela, self::Campos, "WHERE $this->Das20as22 AND disposition = 'FAILED'");        
        $Invalidas5 = $this->Result = $select->getResult();        
        $this->Invalidas['Inv20as22'] = (int) $Invalidas5[0]['val']; 
        
        $this->Result = $this->Invalidas;
        
    }

}
