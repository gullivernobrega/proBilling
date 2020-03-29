<?php

/**
 * Pager.class [ HELPER ]
 * Realização a gestão e a paginação de resultados do sistema!
 * @copyright (c) 2015, Kleber de Souza KLSDESIGNER DESENVOLVIMENTO WEB
 */
class Pager {

    /** DEFINE O PAGER */
    private $Page;
    private $Limit;
    private $Offset;

    /** REALIZA A LEITURA */
    private $Tabela;
    private $Termos;
    private $Places;

    /** DEFINE O PAGINATOR */
    private $Rows;
    private $Link;
    private $MaxLinks;
    private $First;
    private $Last;
    private $Campos;

    /** RENDERIZA O PAGINAR */
    private $Paginator;
    
    //** Armazena a busca do CDR
    private $cdrCall;




    // Comportamento inicial
    function __construct($Link, $First = null, $Last = null, $MaxLinks = null) {
        $this->Link = (string) $Link;
        $this->First = ( (string) $First ? $First : 'Primeira Página' );
        $this->Last = ( (string) $Last ? $Last : 'Útima Página' );
        $this->MaxLinks = ( (int) $MaxLinks ? $MaxLinks : 5);
    }

    // Define o page, o limit e o offset
    public function ExePager($Page, $Limit, $Campos = null) {
        $this->Page = ( (int) $Page ? $Page : 1 );
        $this->Limit = (int) $Limit;
        $this->Offset = ($this->Page * $this->Limit) - $this->Limit;

        $this->Campos = (string) $Campos;
    }

    public function ReturnPage() {
        if ($this->Page > 1):
            $nPage = $this->Page - 1;
            header("Location: {$this->Link}{$nPage}");
        endif;
    }

    function getPage() {
        return $this->Page;
    }

    function getLimit() {
        return $this->Limit;
    }

    function getOffset() {
        return $this->Offset;
    }

    public function ExePaginator($Tabela, $Campos = null, $Termos = null, $ParseString = null) {
        $this->Tabela = (string) $Tabela;
        $this->Termos = (string) $Termos;
        $this->Places = (string) $ParseString;
        $this->Campos = (string) $Campos;

        $this->getSystax();
    }

    public function getPaginator() {
        return $this->Paginator;
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
    private function getSystax() {

        //$campos = "calldate, src, dst, tipo, billsec, disposition, userfield";
        if (empty($this->Campos)):

            $read = new Read;
            $read->ExeRead($this->Tabela, $this->Termos, $this->Places);
        //$this->Rows = $read->getRowCount();

        else:

            $read = new Select;
            $read->ExeSelect($this->Tabela, $this->Campos, $this->Termos, $this->Places);
            $this->cdrCall = $read->getResult();
//            var_dump($read->getResult());
        endif;

        $this->Rows = $read->getRowCount();

        if ($this->Rows > $this->Limit):


            $Paginas = ceil($this->Rows / $this->Limit);
            $MaxLinks = $this->MaxLinks;

            $this->Paginator = "<ul class=\"paginator\">";
            $this->Paginator .= "<li><a title=\"{$this->First}\" href=\"{$this->Link}1\">{$this->First}</a></li>";

            for ($iPag = $this->Page - $MaxLinks; $iPag <= $this->Page - 1; $iPag ++):
                if ($iPag >= 1):
                    $this->Paginator .= "<li><a title=\"Pagina {$iPag}\" href=\"{$this->Link}{$iPag}\">{$iPag}</a></li>";
                endif;
            endfor;

            $this->Paginator .= "<li><span class=\"active\">{$this->Page}</span></li>";

            for ($dPag = $this->Page + 1; $dPag <= $this->Page + $MaxLinks; $dPag ++):
                if ($dPag <= $Paginas):
                    $this->Paginator .= "<li><a title=\"Pagina {$dPag}\" href=\"{$this->Link}{$dPag}\">{$dPag}</a></li>";
                endif;
            endfor;


            $this->Paginator .= "<li><a title=\"{$this->Last}\" href=\"{$this->Link}{$Paginas}\">{$this->Last}</a></li>";
            $this->Paginator .= "</ul>";

        endif;
    }

    public function getCDRcall() {
        //Declarando Array
        $resultCDRcall = array(0, 0, 0, 0, 0, 0, 0);

        //Pegando Total de chamadas.
        $resultCDRcall[0] = count($this->cdrCall);

        foreach ($this->cdrCall as $callCDR) {
            //Tempo total de chamadas.
            $resultCDRcall[1] += $callCDR['billsec'];

            //Chamadas Atendidas ou congestionadas ou ocupadas ou canceladas

            if ($callCDR['disposition'] == 'ANSWERED') {
                $resultCDRcall[2] += 1;
            } elseif ($callCDR['disposition'] == 'NO ANSWER') {
                $resultCDRcall[3] += 1;
            } elseif ($callCDR['disposition'] == 'BUSY') {
                $resultCDRcall[4] += 1;
            } elseif ($callCDR['disposition'] == 'CONGESTION') {
                $resultCDRcall[5] += 1;
            } elseif ($callCDR['disposition'] == 'FAILED') {
                $resultCDRcall[6] += 1;
            }
        }
    
        return $resultCDRcall;
            }

}
