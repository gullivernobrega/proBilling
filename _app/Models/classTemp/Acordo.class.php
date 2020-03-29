<?php

/**
 * Acordo.class [ MODEL ]
 * Classe responsavel por realizar cadastro, alteração e listagem de dados Acordados pelo cliente. 
 * @copyright (c) 09/03/2017, Kleber de Souza BRAZISTELECOM
 */
class Acordo {

    private $Data;
    private $DataContrato;
    private $Acordo_id;
    private $Negociacao_id;
    private $Simulacao_id;
    private $Erro;
    private $Result;

    //Nome da tabela no banco de dados.
    const Tabela = "kl_acordo";

    /**
     * Metodo responsavel por criar os acordos do cliente   
     */
    public function ExeCreate(array $data) {
        $this->Negociacao_id = $data['negociacao_id'];
        $this->Simulacao_id = $data['simulacao_id'];
        //$this->Data = $data;
        $this->dataCli();
        $this->dataSimula();
        $this->setData();
        $this->setNome();
        if ($this->Result):
            //<a href=\"resultado.php\">VERIFIQUE AQUI!</a>
            $this->Result = false;
            KLErro("<b>Opa</b>, você já posssui um acordo cadastrado no sitema, aguarde seu boleto ou entre em contato conosco!", KL_ERROR);
        else:
            $this->Create();
        endif;
    }

    /**
     * Metodo responsagem por realizar alterações dos acordos     
     */
    public function ExeUpdate($acordo_id, $data) {
        $this->Acordo_id = $acordo_id;
        $this->Data = $data;

        $this->setData();
        //$this->setNome();
        $this->Update();
    }

    /** Class resposavel por apagar itens da pagina Cms */
    public function ExeDelete($acordo_id) {
        $this->Acordo_id = (int) $acordo_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE acordo_id = :id", "id={$this->Acordo_id}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover um acordo que não existe no sistema!", KL_INFOR);
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
    /* Busca e Prepara os dados do cliente de acordo com o Id recebido */
    private function dataCli() {
        $readCli = new Read;
        $readCli->ExeRead("kl_negociacao_cliente", "WHERE negociacao_id = :idCli", "idCli={$this->Negociacao_id}");
        $cli = $readCli->getResult();
        extract($cli[0]);

        //`acordo_code_cli`, `acordo_agencia`, `acordo_conta`, `acordo_nome`, `acordo_cpf_cnpj`, `acordo_tipo`,
        $this->Data['acordo_code_cli'] = $negociacao_id;
        $this->Data['acordo_agencia'] = $negociacao_agencia;
        $this->Data['acordo_conta'] = $negociacao_conta;
        $this->Data['acordo_nome'] = $negociacao_nome;
        $this->Data['acordo_cpf_cnpj'] = $negociacao_cpf_cnpj;
        $this->Data['acordo_tipo'] = $negociacao_tipo;
    }

    /* Busca e Prepara os dados da simulação de acordo com o Id recebido */
    private function dataSimula() {
        $readSimula = new Read;
        $readSimula->ExeRead("kl_simulacao", "WHERE simulacao_id = :idS AND negociacao_id = :idN", "idS={$this->Simulacao_id}&idN={$this->Negociacao_id}");
        $simulacao = $readSimula->getResult();
        extract($simulacao[0]);

        //`acordo_code_simulacao`, `acordo_parcela`, `acordo_entrada`, `acordo_valor_parcela`, 
        //`acordo_desconto`, `acordo_total`, `acordo_vencimento`,
        $this->Data['acordo_code_simulacao'] = $simulacao_id;
        $this->Data['acordo_parcela'] = $simulacao_parcela;
        $this->Data['acordo_entrada'] = $simulacao_valor_entrada;
        $this->Data['acordo_valor_parcela'] = $simulacao_valor_parcela;
        $this->Data['acordo_desconto'] = $simulacao_valor_desconto;
        $this->Data['acordo_total'] = $simulacao_valor_total;
        $this->Data['acordo_vencimento'] = $simulacao_vencimento;
    }

    /** Prepara os dados para create */
    private function setData() {
        //pego o ip do cliente
        $ip = getenv("REMOTE_ADDR");

        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

        $this->Data['acordo_ip'] = $ip;
    }

    /** Verifica a existencia de Acordo ja cadastrado. */
    private function setNome() {
        $Where = (!empty($this->Acordo_id) ? "acordo_id != {$this->Acordo_id} AND" : '');

        $readAcordo = new Read;
        $readAcordo->ExeRead(self::Tabela, "WHERE {$Where} acordo_code_cli = :cd", "cd={$this->Negociacao_id}");

        if ($readAcordo->getResult()):
            $this->Result = true;
        //$this->Erro = array("Opa, você tentou cadastrar um Acordo já cadastrado no sitema!", KL_ERROR);
        endif;
    }

    /** Execulta a criação dos dados */
    private function Create() {
        $create = new Create;
        $create->ExeCreate(self::Tabela, $this->Data);

        if ($create->getResult()):
            $this->Result = $create->getResult();
            //header("Location: resultado.php?id={$this->Simulacao_id}&code={$this->Negociacao_id}");
        else:
            $this->Result = false;
            $this->Erro = array("<b>Erro:</b> O acordo não foi cadastrado no sistema, Verifique! ou entre em contato", KL_ERROR);
        endif;
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE acordo_id = :id", "id=$this->Acordo_id");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> A simulação {$this->Data['contrato_numero']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE acordo_id = :id", "id={$this->Acordo_id}");

        if ($deletar->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, Acordo excluido exluido do sistema!", KL_ACCEPT);
        //header("Location: painel.php?exe=paginas/lista");
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel excluir o acordo do sistema!", KL_ERROR);
        endif;
    }

}
