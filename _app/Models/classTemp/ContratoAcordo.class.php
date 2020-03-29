<?php

/**
 * ContratoAcordo.class [ MODEL ]
 * Classe responsavel por realizar cadastro, e listagem de dados dos contrato acordo. 
 * @copyright (c) 12/04/2017, Kleber de Souza BRAZISTELECOM
 */
class ContratoAcordo {

    private $Data;
    private $Acordo_id;  
    private $Negociacao_id;
    private $ContratoAcordo_id;
    private $Erro;
    private $Result;

    //Nome da tabela no banco de dados.
    const Tabela = "kl_contrato_acordo";

    /**
     * Metodo responsavel por criar as páginas cms do site   
     */
    public function ExeCreate(array $data) {
        //$this->Data = $data;
        $this->Negociacao_id = $data['negociacao_id'];
        $this->Acordo_id = $data['acordo_id'];

        $this->setContrato();
        // $this->setData();
        // $this->setNome();
//        if (!$this->Result):
//            $this->Result = TRUE;
//            $this->Erro = array("Opa, você tentou cadastrar um Contrato que já esta cadastrado no sitema!", KL_ALERT);
//        else:
//            $this->Create();
//        endif;
    }

    function getResult() {
        return $this->Result;
    }

    function getErro() {
        return $this->Erro;
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
    /* Busca e Prepara os dados do contrato de acordo com o Id recebido */
    private function setContrato() {
        $readContrato = new Read;
        $readContrato->ExeRead("kl_contrato", "WHERE negociacao_id = :N", "N={$this->Negociacao_id}");

        foreach ($readContrato->getResult() as $contrato):
            extract($contrato);
            //`contrato_acordo_id`, `negociacao_acordo_id`, `contrato_acordo_numero`, `contrato_acordo_codigo`, `contrato_acordo_referencia`, 
            //`contrato_acordo_valor_debito`, `contrato_acordo_valor_vencimento`, `contrato_acordo_valor_total`, `acordo_id`
            $this->Data['negociacao_acordo_id'] = $negociacao_id;
            $this->Data['contrato_acordo_numero'] = $contrato_numero;
            $this->Data['contrato_acordo_codigo'] = $contrato_codigo;
            $this->Data['contrato_acordo_referencia'] = $contrato_referencia;
            $this->Data['contrato_acordo_valor_debito'] = $contrato_valor_debito;
            $this->Data['contrato_acordo_valor_vencimento'] = $contrato_valor_vencimento;
            $this->Data['contrato_acordo_valor_total'] = $contrato_valor_total;

            $this->setData();
            //$this->setNome();
            $this->Create();
            
        endforeach;        

    }

    /** Prepara os dados create */
    private function setData() {
        
        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

        // repassa os dados        
        $this->Data['acordo_id'] = $this->Acordo_id;
        
    }

    /** Verifica a existencia de alguma Rede social. */
    private function setNome() {
        $Where = (!empty($this->Contrato_acordo_id) ? "contrato_acordo_id != {$this->Contrato_acordo_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} contrato_acordo_numero = :c", "c={$this->Data['contrato_acordo_numero']}");

        if ($readName->getResult()):
            $this->Result = FALSE;
        else:
            $this->Result = TRUE;
        endif;
    }

    /** Execulta a criação dos dados */
    private function Create() {
        $create = new Create;
        $create->ExeCreate(self::Tabela, $this->Data);

        if ($create->getResult()):
            $this->Result = $create->getResult();
            header("Location: resultado.php?code={$this->Negociacao_id}");       
        endif;
    }

}
