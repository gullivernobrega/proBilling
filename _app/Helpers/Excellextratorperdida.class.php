<?php

/**
 * excellextratorperdida.class [ HELPER ]
 * Realia um relatorio em excel para extrato de recebidas perdidas
 * @copyright (c) 22/08/2018, Kleber de Souza BRAZISTELECOM
 */
class Excellextratorperdida {

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
        $xls .= "<h3>RELATÓRIO DE RECEBIDAS ATENDIDAS</h3>";
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
            $status = ($rel['disposition'] == 'ANSWERED' ? "Atendida" : ($rel['disposition'] == 'CANCEL' ? "Cancelada" : ($rel['disposition'] == 'BUSY' ? "Ocupado" : ($rel['disposition'] == 'NO ANSWER' ? "Não atendida" : ($rel['disposition'] == 'FAILED' ? "Falha" : ($disposition == 'CHANUNAVAIL' ? "Indisponível" : null))))));

            $xls .= "<table border='1'>";
            $xls .= "<tr>";
            $xls .= "<td>$calldate</td>";
            $xls .= "<td>$src</td>";
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
