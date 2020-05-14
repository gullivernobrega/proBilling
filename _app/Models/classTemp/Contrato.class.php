<?php

/**
 * Contrato.class [ MODEL ]
 * Classe responsavel por realizar cadastro, alteração e listagem de dados dos contrato. 
 * @copyright (c) 09/02/2017, Kleber de Souza BRAZISTELECOM
 */
class Contrato {

    private $Data;
    private $Contrato_id;
    private $Negociacao_id;
    private $Erro;
    private $Result;

    //Nome da tabela no banco de dados.
    const Tabela = "kl_contrato";

    /**
     * Metodo responsavel por criar as páginas cms do site   
     */
    public function ExeCreate(array $data) {
        $this->Data = $data;

        $this->setData();
        $this->setNome();
        
        if (!$this->Result):
            $this->Result = TRUE;
            $this->Erro = array("Opa, você tentou cadastrar um Contrato que já esta cadastrado no sitema!", KL_ALERT);
        else:
            $this->Create();
        endif; 
    }

    /**
     * Metodo responsagem por realizar alterações nas categorias e subcategorias     
     */
    public function ExeUpdate($contrato_id, $data) {
        $this->Contrato_id = $contrato_id;
        $this->Data = $data;

        $this->setData();
        //$this->setNome();
        $this->Update();
    }

    /** Class resposavel por apagar itens da pagina Cms */
    public function ExeDelete($contrato_id) {
        $this->Contrato_id = (int) $contrato_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE contrato_id = :id", "id={$this->Contrato_id}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover um contrato que não existe no sistema!", KL_INFOR);
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
        $valorDebito = $chequeValor->MoedaUs($this->Data['contrato_valor_debito']);
        $valorAvencer = $chequeValor->MoedaUs($this->Data['contrato_valor_vencimento']);
        $valorTotal = $chequeValor->MoedaUs($this->Data['contrato_valor_total']);

        unset($this->Data['contrato_valor_debito'], $this->Data['contrato_valor_vencimento'], $this->Data['contrato_valor_total']);

        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

        // repassa os dados        
        $this->Data['contrato_valor_debito'] = $valorDebito;
        $this->Data['contrato_valor_vencimento'] = $valorAvencer;
        $this->Data['contrato_valor_total'] = $valorTotal;
    }

    /** Verifica a existencia de alguma Rede social. */
    private function setNome() {
        $Where = (!empty($this->Contrato_id) ? "contrato_id != {$this->Contrato_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} contrato_numero = :c", "c={$this->Data['contrato_numero']}");

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
        //$this->Erro = array("<b>Sucesso:</b> O contrato {$this->Data['contrato_numero']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE contrato_id = :id", "id=$this->Contrato_id");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> O contrato {$this->Data['contrato_numero']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE contrato_id = :id", "id={$this->Contrato_id}");

        if ($deletar->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, contrato foi excluido do sistema!", KL_ACCEPT);
        //header("Location: painel.php?exe=paginas/lista");
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel excluir o contrato do sistema!", KL_ERROR);
        endif;
    }

}

// close Clientes