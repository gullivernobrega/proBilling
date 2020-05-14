<?php

/**
 * Negociacao.class [ MODEL ]
 * Classe responsavel por realizar cadastro, alteração e listagem de dados das Negociaçoes do clientes. 
 * @copyright (c) 08/08/2016, Kleber de Souza BRAZISTELECOM
 */
class Negociacao {

    private $Data;
    private $DataTxt;
    private $Negociacao_id;
    private $NomePdf;
    private $Erro;
    private $Result;

    //Nome da tabela no banco de dados.
    const Tabela = "kl_negociacao_cliente";

    /**
     * Metodo responsavel por criar as páginas cms do site   
     */
    public function ExeCreate(array $data) {
        $this->Data = $data;

        $this->setData();
        $this->setNome();
        if ($this->Result):
            $this->Result = $this->Result;
            $this->Erro = array("Opa, você tentou cadastrar um cliente que já esta cadastrado no sitema!", KL_ERROR);
        else:
            $this->Create();
        endif;
    }

    /**
     * Metodo responsavel por criar cliente com arquivo csv    
     */
    public function ExeCreateCsv(array $data) {
        $this->Data = $data;
        $this->setDataCsv();
    }

    /**
     * Metodo responsagem por realizar alterações nas categorias e subcategorias     
     */
    public function ExeUpdate($negociacao_id, $data) {
        $this->Negociacao_id = $negociacao_id;
        $this->Data = $data;

        $this->setData();
        //$this->setNome();        
        $this->Update();
    }

    /** Class resposavel por apagar itens da pagina Cms */
    public function ExeDelete($negociacao_id) {
        $this->Negociacao_id = (int) $negociacao_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE negociacao_id = :id", "id={$this->Negociacao_id}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover um cliente que não existe no sistema!", KL_INFOR);
        else:
            //Ler e apagar dados da tabela simulação
            $readS = new Read;
            $readS->ExeRead("kl_simulacao", "WHERE negociacao_id = :id", "id={$this->Negociacao_id}");
            if ($readS->getRowCount()):
                $delS = new Delete;
                $delS->ExeDelete("kl_simulacao", "WHERE negociacao_id = :id", "id={$this->Negociacao_id}");
            endif;
            //Ler e apagar dados da tabela simulação
            $readC = new Read;
            $readC->ExeRead("kl_contrato", "WHERE negociacao_id = :id", "id={$this->Negociacao_id}");
            if ($readC->getRowCount()):
                $delC = new Delete;
                $delC->ExeDelete("kl_contrato", "WHERE negociacao_id = :id", "id={$this->Negociacao_id}");
            endif;

            $this->Delete();
        endif;
    }

    /** Class resposavel por apagar todos os itens do negociação cliente */
//    public function ExeDeleteAll() {
//        $this->DeleteAll();
//    }

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
        $cpfCnpjNumero = $chequeValor->limpaCPF_CNPJ($this->Data['negociacao_cpf_cnpj']);
        //$cpf = str_replace(".", "-", $this->Data['negociacao_cpf']);
        //$cpfNumero = str_replace("-", "", $cpf);

        $valorVencido = $chequeValor->MoedaUs($this->Data['negociacao_valor_vencido']);
        $valorAvencer = $chequeValor->MoedaUs($this->Data['negociacao_valor_vencer']);
        $valorTotal = $chequeValor->MoedaUs($this->Data['negociacao_valor_total']);

        unset($this->Data['negociacao_cpf_cnpj'], $this->Data['negociacao_valor_vencido'], $this->Data['negociacao_valor_vencer'], $this->Data['negociacao_valor_total']);

        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

        // repassa os dados        
        $this->Data['negociacao_cpf_cnpj'] = $cpfCnpjNumero;
        $this->Data['negociacao_valor_vencido'] = $valorVencido;
        $this->Data['negociacao_valor_vencer'] = $valorAvencer;
        $this->Data['negociacao_valor_total'] = $valorTotal;
    }

    /** Prepara os dados createCsv */
    private function setDataCsv() {
        //Importar o arquivo da planilha csv          
        if (($handle = fopen($_FILES['cli_csv']['tmp_name'], "r")) !== FALSE):
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                for ($c = 0; $c < $num; $c++) {
                    //Cria um array de dados extraido do arquivo csv;                    
                    list(
                    $this->DataCsv['cli_nome'],
                    $this->DataCsv['cli_email'],
                    $this->DataCsv['cli_cpf'],
                    $this->DataCsv['cli_chave'],
                    $this->DataCsv['cli_num_contrato'],
                    $this->DataCsv['cli_num_boleto'],
                    $this->DataCsv['cli_valor_boleto'],
                    $this->DataCsv['cli_data_vencimento'],
                    $this->DataCsv['cli_pdf'],
                    $this->DataCsv['cli_status']) = explode(",", str_replace("\"", "", $data[$c]));

                    // Execulta o CreateCsv para amazenar no banco
                    $this->CreateCsv();
                }
            }
            fclose($handle);
        endif;
    }

    /** Verifica a existencia de alguma Rede social. */
    private function setNome() {
        $Where = (!empty($this->Negociacao_id) ? "negociacao_id != {$this->Negociacao_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} negociacao_cpf_cnpj = :c", "c={$this->Data['negociacao_cpf_cnpj']}");

        if ($readName->getResult()):
            //$this->Data['cli_nome'] = $this->Data['cli_nome'] . '-' . $readName->getRowCount();
            $this->Result = false;
        //$this->Erro = array("Opa, você tentou cadastrar um cliente que já esta cadastrado no sitema!", KL_ERROR);
        //header("Location: painel.php?exe=clientes/lista&erro=$this->Erro");
        endif;
    }

    /** Execulta a criação dos dados */
    private function Create() {
        $create = new Create;
        $create->ExeCreate(self::Tabela, $this->Data);

        if ($create->getResult()):
            $this->Result = $create->getResult();
            $this->Erro = array("<b>Sucesso:</b> O cliente {$this->Data['negociacao_nome']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a criação dos dados */
    private function CreateCsv() {
        $create = new Create;
        $create->ExeCreate(self::Tabela, $this->DataCsv);

        if ($create->getResult()):
            $this->Result = $create->getResult();
            $this->Erro = array("<b>Sucesso:</b> O cliente {$this->DataCsv['cli_nome']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE negociacao_id = :id", "id=$this->Negociacao_id");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> O cliente negociação {$this->Data['negociacao_nome']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE negociacao_id = :id", "id={$this->Negociacao_id}");

        if ($deletar->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, cliente negociação foi excluido do sistema!", KL_ACCEPT);
        //header("Location: painel.php?exe=paginas/lista");
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel excluir o cliente do sistema!", KL_ERROR);
        endif;
    }
    
    /** Execulta a exclusão de todos os da tabela negociacao_cliente */
//    private function DeleteAll() {       
//        
//        $deletarAll = new DeleteAll();
//        $deletarAll->ExeDeleteAll(self::Tabela);
//
//        if ($deletarAll->getResult()):
//            $this->Result = true;
//            $this->Erro = array("Sucesso, todo os dados da tabela negociacao_cliente foi excluido do sistema!", KL_ACCEPT);
//        //header("Location: painel.php?exe=paginas/lista");
//        else:
//            $this->Result = false;
//            $this->Erro = array("Erro, Não foi possivel excluir os cliente do sistema!", KL_ERROR);
//        endif;
//    }

}

// close Clientes