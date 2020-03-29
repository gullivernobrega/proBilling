<?php

/**
 * ExcellDashboardSms.class [ HELPER ]
 * Realiza um relatório em excel
 * @copyright (c) 16/07/2019, Kleber de Souza BRAZISTELECOM
 */
class ExcellDashboardSms {

    private $Tabela;
    private $Termos;
    private $Places;
    private $Result;
    private $Contar;
    private $Th;
    private $Arquivo;

    const Valor = 0.032;

    /**
     * Metodo responsavel por realizar relatório em Excell
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

        //$Data = "";
        // Listagem dos Lotes e os itens +++++++++++++++++++++++++++++++++++++//
        $campoDistinto = "DISTINCT sms_lote";

        $objLote = new Select;
        $objLote->ExeSelect("cdr_sms", $campoDistinto, "{$this->Termos} ORDER BY sms_date ASC");
        $this->Contar = $objLote->getRowCount();
        $resultLote = $objLote->getResult();

        if ($this->Contar > 0):
            foreach ($resultLote as $lt):

                $camposs = "sms_date, sms_lote, sms_campanha, sms_status, count(*) as total";
                $objByLote = new Select;
                $objByLote->ExeSelect("cdr_sms", $camposs, "{$this->Termos} AND sms_lote = '{$lt['sms_lote']}' group by sms_status ORDER BY sms_date ASC");
                $totais = $objByLote->getRowCount();
                $sms[] = $objByLote->getResult();

            endforeach;
        endif;

        foreach ($sms as $dados):
            $lote = NULL;
            foreach ($dados as $dado):

                if ($dado['sms_lote'] != $lote):

                    $arrayDados[$dado['sms_lote']] = array('sms_date' => $dado['sms_date'], 'sms_lote' => $dado['sms_lote'], 'sms_campanha' => $dado['sms_campanha'], 'ACCEPTED' => '0', 'UNDELIVERABLE' => '0', 'SENT' => '0', 'DELIVERED' => '0', 'UNKNOWN' => '0');
                    $arrayDados[$dado['sms_lote']][$dado['sms_status']] = $dado['total'];
                    $lote = $dado['sms_lote'];

                elseif ($dado['sms_lote'] == $lote):

                    $arrayDados[$dado['sms_lote']][$dado['sms_status']] = $dado['total'];

                endif;

            endforeach;
        endforeach;

        if ($this->Contar > 0):

            // MONTA A TABELA COM O CABEÇALHO COM OS PARAMETROS DA $TH
            $xls = "";
            $xls .= "<meta http-equiv='content-type' content='text/html;charset=utf-8' />";
            $xls .= "<h3>RELATÓRIO GERAL DE SMS</h3>";
            $xls .= "<table border='1'>";
            $xls .= "<tr>";

            foreach ($this->Th as $v) {
                $xls .= "<th>{$v}</th>";
            }
            $xls .= "</tr>";
            $xls .= "</table>";

            // EXECULTA LOOP COM OS DADOS DO RELATÓRIO  
            foreach ($arrayDados as $key => $value):

                $sms_lote = (!empty($key)) ? $key : "0";
                $sms_campanha = (!empty($value['sms_campanha'])) ? $value['sms_campanha'] : "0";
                $sms_date = (!empty($value['sms_date'])) ? $value['sms_date'] : "0";
                $Inserido = (!empty($value['ACCEPTED'])) ? $value['ACCEPTED'] : "0";
                $NaoEntregavel = (!empty($value['UNDELIVERABLE'])) ? $value['UNDELIVERABLE'] : "0";
                $Enviado = (!empty($value['SENT'])) ? $value['SENT'] : "0";
                $Entregue = (!empty($value['DELIVERED'])) ? $value['DELIVERED'] : "0";
                $NaoEntregue = (!empty($value['UNKNOWN'])) ? $value['UNKNOWN'] : "0";
                $Total = $Inserido + $NaoEntregavel + $Enviado + $Entregue + $NaoEntregue;                

                $xls .= "<table border='1'>";
                $xls .= "<tr>";
                $xls .= "<td>$sms_lote</td>";
                $xls .= "<td>$sms_campanha</td>";
                $xls .= "<td>$sms_date</td>";
                $xls .= "<td>$Inserido</td>";
                $xls .= "<td>$NaoEntregavel</td>";
                $xls .= "<td>$Enviado";
                $xls .= "<td>$Entregue";
                $xls .= "<td>$NaoEntregue</td>";
                $xls .= "<td>$Total</td>";
                $xls .= "</tr>";
                $xls .= "</table>";

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
