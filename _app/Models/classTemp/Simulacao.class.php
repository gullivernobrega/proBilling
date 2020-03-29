<?php

/**
 * Simulacao.class [ MODEL ]
 * Classe responsavel por realizar cadastro, alteração e listagem de dados dos contrato. 
 * @copyright (c) 09/02/2017, Kleber de Souza BRAZISTELECOM
 */
class Simulacao {

    private $Data;
    private $Simulacao_id;
    private $Negociacao_id;
    private $Erro;
    private $Result;

    //Nome da tabela no banco de dados.
    const Tabela = "kl_simulacao";

    /**
     * Metodo responsavel por criar as páginas cms do site   
     */
    public function ExeCreate(array $data) {
        $this->Data = $data;
        
        $this->setData();
        $this->setNome();
        $this->Create();
    }

    /**
     * Metodo responsagem por realizar alterações nas categorias e subcategorias     
     */
    public function ExeUpdate($simulacao_id, $data) {
        $this->Simulacao_id = $simulacao_id;
        $this->Data = $data;

        $this->setData();
        //$this->setNome();
        $this->Update();
    }

    /** Class resposavel por apagar itens da pagina Cms */
    public function ExeDelete($simulacao_id) {
        $this->Simulacao_id = (int) $simulacao_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE simulacao_id = :id", "id={$this->Simulacao_id}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover uma simulação que não existe no sistema!", KL_INFOR);
        else:
            $this->Delete();
        endif;
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

    /** Prepara os dados create */
    private function setData() {
          
        $chequeValor = new Check();
        $valorEntrada = $chequeValor->MoedaUs($this->Data['simulacao_valor_entrada']);
        $valorParcela = $chequeValor->MoedaUs($this->Data['simulacao_valor_parcela']);
        $valorDesconto = $chequeValor->MoedaUs($this->Data['simulacao_valor_desconto']);
        $valorTotal = $chequeValor->MoedaUs($this->Data['simulacao_valor_total']);        
        
        unset($this->Data['simulacao_valor_entrada'],$this->Data['simulacao_valor_parcela'],$this->Data['simulacao_valor_Desconto'],$this->Data['simulacao_valor_total']);

        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

        // repassa os dados        
        $this->Data['simulacao_valor_entrada'] = $valorEntrada;
        $this->Data['simulacao_valor_parcela'] = $valorParcela;
        $this->Data['simulacao_valor_desconto'] = $valorDesconto;
        $this->Data['simulacao_valor_total'] = $valorTotal;
    }

    /** Verifica a existencia de alguma Rede social. */
    private function setNome() {
        $Where = (!empty($this->Simulacao_id) ? "simulacao_id != {$this->Simulacao_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} simulacao_parcela = :s", "s={$this->Data['simulacao_parcela']}");

        if ($readName->getResult()):
            $this->Result = false;
            $this->Erro = array("Opa, você tentou cadastrar uma Simulação que já esta cadastrado no sitema!", KL_ERROR);
        endif;
    }

    /** Execulta a criação dos dados */
    private function Create() {
        $create = new Create;
        $create->ExeCreate(self::Tabela, $this->Data);

        if ($create->getResult()):
            $this->Result = $create->getResult();
        //$this->Erro = array("<b>Sucesso:</b> O contrato {$this->Data['contrato_numero']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE simulacao_id = :id", "id=$this->Simulacao_id");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> A simulação {$this->Data['contrato_numero']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE simulacao_id = :id", "id={$this->Simulacao_id}");

        if ($deletar->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, Simulçaão excluida do sistema!", KL_ACCEPT);
        //header("Location: painel.php?exe=paginas/lista");
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel excluir a simulação do sistema!", KL_ERROR);
        endif;
    }
}
