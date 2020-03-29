<?php

/**
 * ExcellSmsRest.class [ HELPER ]
 * Realia um relatorio de resposta sms em excel
 * @copyright (c) 16/07/2019, Kleber de Souza BRAZISTELECOM
 */
class ExcellSmsRest {

    private $Tabela;
    private $Termos;
    private $Places;
    private $Result;
    private $Contar;
    private $Th;
    private $Arquivo;

    /** 
     * Metodo responsavel por realizar relatório de resposta sms em Excell
     * 
     * @param type $tabela do banco de dados
     * @param array $th - paramentros para tabela
     * @param type $arquivo - nome do arquivo
     * @param type $termos - termos da pesquisa
     * @param type $parces - Complemento dos termos
     */
    public function ExeExcell($tabela, array $th, $arquivo, $termos = null, $parces = null) {
        $this->Tabela = (string) $tabela;
        $this->Termos = (string) $termos;
        $this->Places = (string) $parces;
        $this->Th = $th;
        $this->Arquivo = $arquivo . ".xls";
        
        $this->getSintax();
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
    private function getSintax() {
        $read = new Read;
        $read->ExeRead($this->Tabela, $this->Termos, $this->Places);
        $this->Contar = $read->getRowCount();
        $this->Result = $read->getResult();   
        
        if($this->Contar > 0):
                    
        // MONTA A TABELA COM O CABEÇALHO COM OS PARAMETROS DA $TH
        $xls = "";
        $xls .= "<meta http-equiv='content-type' content='text/html;charset=utf-8' />";
        $xls .= "<h3>RELATÓRIO SMS DE RESPOSTAS</h3>";
        $xls .= "<table border='1'>";
        $xls .= "<tr>";

        foreach ($this->Th as $v) {
            $xls .= "<th>{$v}</th>";
        }
        $xls .= "</tr>";
        $xls .= "</table>";

        // EXECULTA LOOP COM OS DADOS DO RELATÓRIO  
        foreach ($this->Result as $rel):

            $id = $rel['sms_cus_id'] . "/" . $rel['sms_acc_id'];            
            $data = $rel['data_recebimento'];                                    
            $numero = $rel['origem'];            
            //$sms_status = ($rel['sms_status'] == 'ACCEPTED' ? "Enviado" : ($rel['sms_status'] == 'UNKNOWN' ? "Pendente" :($rel['sms_status'] == 'PAYREQUIRED' ? "Bloqueado" : "INVALIDO")));
            $mensagem = $rel['resposta'];

            $xls .= "<table border='1'>";
            $xls .= "<tr>";
            $xls .= "<td>$id</td>";
            $xls .= "<td>$data</td>";
            $xls .= "<td>$numero</td>"; 
            $xls .= "<td>$mensagem</td>";
            $xls .= "</tr>";
            $xls .= "</table>";
            // $i++;
        endforeach;

        // Configurações header para forçar o download        
        header("Content-Encoding: UTF-8");        
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: application/x-msexcel");
        header("Content-Disposition: attachment; filename={$this->Arquivo}");
        header("Content-Description: PHP Generated Data");

        // Envia o conteúdo do arquivo  
        echo $xls;
        exit;
        
        else:
            echo 'Não existe dados para listagem xls!';
        endif;
    }

}
