<?php

/**
 * excellsms.class [ HELPER ]
 * Realia um relatorio em excel
 * @copyright (c) 16/07/2019, Kleber de Souza BRAZISTELECOM
 */
class Excellsms {

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
        $read = new Read;
        $read->ExeRead($this->Tabela, $this->Termos, $this->Places);
        $this->Contar = $read->getRowCount();
        $this->Result = $read->getResult();

        if ($this->Contar > 0):
            
            //Inicialização das variaveis
            $inserido = 0;
            $enviado = 0;
            $entregue = 0;
            $falha = 0;
            $semSaldo = 0;
            $naoEntregue = 0;
            $expirado = 0;
            $deletado = 0;
            $rejeitado = 0;
            $naoEntregavel = 0;

            // MONTA A TABELA COM O CABEÇALHO COM OS PARAMETROS DA $TH
            $xls = "";
            $xls .= "<meta http-equiv='content-type' content='text/html;charset=utf-8' />";
            $xls .= "<h3>RELATÓRIO DE SMS ENVIADOS</h3>";
            $xls .= "<table border='1'>";
            $xls .= "<tr>";

            foreach ($this->Th as $v) {
                $xls .= "<th>{$v}</th>";
            }
            $xls .= "</tr>";
            $xls .= "</table>";

            // EXECULTA LOOP COM OS DADOS DO RELATÓRIO  
            foreach ($this->Result as $rel):

                $sms_id = $rel['sms_id'];
                $sms_data = $rel['sms_date'];
                $sms_date_atualizacao = $rel['sms_date_atualizacao'];
                $sms_lote = $rel['sms_lote'];
                $sms_campanha = $rel['sms_campanha'];
                $sms_operadora = $rel['sms_operadora'];
                $sms_numero = $rel['sms_numero'];
                $sms_mensagem = $rel['sms_msg'];

                // Status de SMS
                if ($rel['sms_status'] == 'ACCEPTED'):
                    $status = "Inserido";
                    $inserido = $inserido + 1;
                elseif ($rel['sms_status'] == 'PAYREQUIRED'):
                    $status = "Sem Saldo";
                    $semSaldo = $semSaldo + 1;
                elseif ($rel['sms_status'] == 'SENT'):
                    $status = "Enviado";
                    $enviado = $enviado + 1;
                elseif ($rel['sms_status'] == 'DELIVERED'):
                    $status = "Entregue";
                    $entregue = $entregue + 1;
                elseif ($rel['sms_status'] == 'FAILED'):
                    $status = "Falha";
                    $falha = $falha + 1;
                elseif ($rel['sms_status'] == 'UNKNOWN'):
                    $status = "Não Entregue";
                    $naoEntregue = $naoEntregue + 1;
                elseif ($rel['sms_status'] == 'EXPIRED'):
                    $status = "Expirado";
                    $expirado = $expirado + 1;
                elseif ($rel['sms_status'] == 'DELETED'):
                    $status = "Deletado";
                    $deletado = $deletado + 1;
                elseif ($rel['sms_status'] == 'REJECTED'):
                    $status = "Rejeitado";
                    $rejeitado = $rejeitado + 1;
                elseif ($rel['sms_status'] == 'UNDELIVERABLE'):
                    $status = "Não Entregável";
                    $naoEntregavel = $naoEntregavel + 1;
                else:
                    $status = "INVALIDO";
                endif;

                $xls .= "<table border='1'>";
                $xls .= "<tr>";
                $xls .= "<td>$sms_id</td>";
                $xls .= "<td>$sms_data</td>";
                $xls .= "<td>$sms_date_atualizacao</td>";
                $xls .= "<td>$sms_lote</td>";
                $xls .= "<td>$sms_campanha</td>";
                $xls .= "<td>$sms_operadora</td>";
                $xls .= "<td>$sms_numero</td>";
                $xls .= "<td>$sms_mensagem</td>";
                $xls .= "<td>$status</td>";
                $xls .= "</tr>";
                $xls .= "</table>"; 
                
            endforeach;
            
            //Prepara tabela para Resumo de SMS
            //Valor total de SMS
            $valor = number_format(($enviado + $entregue + $naoEntregavel) * self::Valor, 3, ",", ".");            
            $falhado = $semSaldo + $falha + $naoEntregue + $expirado + $deletado + $rejeitado;
            
            $xls .= "<h3>RESUMO DE SMS</h3>";
            $xls .= "<table border='1'>";
            
            $xls .= "<tr>";
            $xls .= "<th>DESCRIÇÃO</th>";
            $xls .= "<th>TOTAL</th>";
            $xls .= "</tr>";
            
            $xls .= "<tr>";
            $xls .= "<td>Total SMS</td>";
            $xls .= "<td>$this->Contar</td>";
            $xls .= "</tr>";
            
            $xls .= "<tr>";
            $xls .= "<td>Entregue</td>";
            $xls .= "<td>$entregue</td>";
            $xls .= "</tr>";
            
            $xls .= "<tr>";
            $xls .= "<td>Eviado</td>";
            $xls .= "<td>$enviado</td>";
            $xls .= "</tr>";
            
            $xls .= "<tr>";
            $xls .= "<td>Inserido</td>";
            $xls .= "<td>$inserido</td>";
            $xls .= "</tr>";
            
            $xls .= "<tr>";
            $xls .= "<td>Não Entregável</td>";
            $xls .= "<td>$naoEntregavel</td>";
            $xls .= "</tr>";
            
            $xls .= "<tr>";
            $xls .= "<td>Falhado</td>";
            $xls .= "<td>$falhado</td>";
            $xls .= "</tr>";
            
            $xls .= "<tr>";
            $xls .= "<td>Valor</td>";
            $xls .= "<td>$valor</td>";
            $xls .= "</tr>";            
            
            $xls .= "</table>";            
            
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
