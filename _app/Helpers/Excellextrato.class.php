<?php

/**
 * excellextrato.class [ HELPER ]
 * Realia um relatorio em excel
 * @copyright (c) 27/03/2018, Kleber de Souza BRAZISTELECOM
 */
class Excellextrato {

    private $Tabela;
    private $Termos;
    private $Places;
    private $Result;
    private $Contar;
    private $Th;
    private $Arquivo;

    // EXECULTA O RELATORIO EXCELL
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
        $xls .= "<h3>RELATÓRIO DE LIGAÇÕES POR PERÍODO</h3>";
        $xls .= "<table border='1'>";
        $xls .= "<tr>";

        foreach ($this->Th as $v) {
            $xls .= "<th>{$v}</th>";
        }
        $xls .= "</tr>";
        $xls .= "</table>";

        // EXECULTA LOOP COM OS DADOS DO RELATÓRIO  
        foreach ($this->Result as $rel):
//            extract($rel);
            $calldate = $rel['calldate'];
            $src = $rel['src'];
            $dst = $rel['dst'];
            $tipo = $rel['tipo'];
            $tronco = $rel['tronco'];
            $billsec = gmdate("H:i:s", $rel['billsec']);
            $status = ($rel['disposition'] == 'ANSWERED' ? "Atendida" : ($rel['disposition'] == 'CANCEL' ? "Cancelada" : ($rel['disposition'] == 'BUSY' ? "Ocupado" : ($rel['disposition'] == 'NO ANSWER' ? "Não atendida" : ($rel['disposition'] == 'FAILED' ? "Falha" : null)))));

            $xls .= "<table border='1'>";
            $xls .= "<tr>";
            $xls .= "<td>$calldate</td>";
            $xls .= "<td>$src</td>";
            $xls .= "<td>$dst</td>";
            $xls .= "<td>$tipo</td>";
            $xls .= "<td>$tronco</td>";
            $xls .= "<td>$billsec</td>";
            $xls .= "<td>$status</td>";
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
