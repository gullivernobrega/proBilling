<?php

/**
 * excel.class [ HELPER ]
 * Realia um relatorio em excel
 * @copyright (c) 22/06/2016, Kleber de Souza BRAZISTELECOM
 */

class Excell {
        
    private $Tabela;
    private $Termos;
    private $Places;
    private $Result;
    private $Contar;    
    private $Th;
    private $Arquivo;
    private $xls;    
    
    /**
     * ExeExell - Execulta o Relatório
     * 
     * @param string $tabela - Nome da tabela no banco de dados 
     * @param array $th - paramentros para cabeçalho do relatório
     * @param string $arquivo - Nome do arquivo por padrão é: relatorio
     * @param string $termos Exemplo: "WHERE ID = (INT) ID AND ..."
     * @param string $parces
     */
    public function ExeExcell($tabela, array $th, $arquivo = "relatorio", $termos = null, $parces = null) {
        $this->Tabela = (string) $tabela;
        $this->Termos = (string) $termos;
        $this->Places = (string) $parces;
        $this->Th = $th;
        $this->Arquivo = $arquivo . ".csv";
        
        $this->getSintax();
        $this->headerExcell();
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
    private function getSintax() {
                
        // MONTA A TABELA COM O CABEÇALHO COM OS PARAMETROS DA $TH
        $this->xls = "";
        $this->xls .= "<h3>RELATÓRIO DE IMPOSTOS</h3>";
        $this->xls .= "<table>";
        $this->xls .= "<tr>";
        foreach ($this->Th as $v) {
            $this->xls .= "<th>{$v}</th>";
        }
        $this->xls .= "</tr>";
        $this->xls .= "</table>";        
       
        $read = new Read;
        $read->ExeRead($this->Tabela, $this->Termos, $this->Places);
        $this->Contar = $read->getLinhas();
        $this->Result = $read->getDados();
        // EXECULTA LOOP COM OS DADOS DO RELATÓRIO
        //foreach ($this->Result as $ret)
        while ($ret = mysqli_fetch_array($this->Result)) {
            $imposto_id = $ret['imposto_id'];
            $imposto_Nome = $ret['imposto_nome'];
            $imposto_valor = $ret['imposto_valor'];
            $imposto_data_cad = $ret['imposto_data_cad'];
            $imposto_data_venc = $ret['imposto_status'] == "S" ? '<font color=green>Sim</font>' : '<font color=red>Não</font>';
            $imposto_data_pag = $ret['imposto_data_pag'];
            $imposto_obs = $ret['imposto_obs'];
            $imposto_guia_pdf = $ret['imposto_guia_pdf'];
            $imposto_comp_pag = $ret['imposto_comp_pag'];
            $xls .= "<table>";
            $xls .= "<tr>";
            $xls .= "<td>$imposto_id</td>";
            $xls .= "<td>$imposto_Nome</td>";
            $xls .= "<td>$imposto_valor</td>";
            $xls .= "<td>$imposto_data_cad</td>";
            $xls .= "<td>$imposto_data_venc</td>";
            $xls .= "<td>$imposto_data_pag</td>";
            $xls .= "<td>$imposto_obs</td>";
            $xls .= "<td>$imposto_guia_pdf</td>";
            $xls .= "<td>$imposto_comp_pag</td>";
            $xls .= "</tr>";
            $xls .= "</table>";
            // $i++;
        }        

    }
    
    /** Preparação do Ambiente Excell  */
    private function headerExcell() {

        header("Content-Encoding: UTF-8");
        //header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Expires: 0");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        //header("Cache-Control: no-cache, must-revalidate");
        header("Cache-control: private, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: text/x-csv");
        //header("Content-type: application/xls");
        header("Content-Disposition: attachment; filename={$this->Arquivo}");
        header("Content-Description: PHP Generated Data");

        // Envia o conteúdo do arquivo  
        echo $xls;
        exit;
        
    }
    

}
