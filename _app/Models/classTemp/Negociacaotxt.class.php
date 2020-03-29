<?php

/**
 * Negociacaotxt.class [ MODEL ]
 * Classe responsavel por realizar cadastro, alteração atraves de arquivo de importação txt para Negociaçoes dos clientes.
 * @copyright (c) 31/03/2017, Kleber de Souza BRAZISTELECOM
 */
class Negociacaotxt {

    private $Data;
    private $DataCliente;
    private $DataSimulacao;
    private $DataContrato;
    private $Cli;
    private $Simula;
    private $Contrato;
    private $cont;
    private $contSimula;
    private $contContrato;
    private $Negociacao_id;
    private $Erro;
    private $Result;
    // Array do tamanho dos campos cliente
    private $tamanho = array(
        "tipo" => "1",
        "codigo" => "15",
        "ag" => "10",
        "ct" => "20",
        "nome" => "50",
        "cpf_cnpj" => "14",
        "tip" => "1",
        "end" => "80",
        "bairro" => "30",
        "cidade" => "30",
        "uf" => "2",
        "cep" => "9",
        "email1" => "100",
        "email2" => "100",
        "f1" => "14",
        "ft1" => "1",
        "f2" => "14",
        "ft2" => "1",
        "f3" => "14",
        "ft3" => "1",
        "f4" => "14",
        "ft4" => "1",
        "f5" => "14",
        "ft5" => "1",
        "f6" => "14",
        "ft6" => "1",
        "vencido" => "20",
        "vencer" => "20",
        "total" => "20"
    );
    // Array do tamanho dos campos opções de simulação
    private $tamanhoSimu = array(
        "tipo_pag" => "1",
        "codigo_pag" => "15",
        "parcela" => "3",
        "entrada" => "20",
        "valor_parcela" => "20",
        "desconto" => "20",
        "total_pag" => "20",
        "data_venc" => "10"
    );
    // Array do tamanho dos campos opções de contrato
    private $tamanhoContrato = array(
        "tipo_contrato" => "1",
        "codigo_contrato" => "15",
        "op" => "30",
        "c_produto" => "10",
        "tipo_op" => "40",
        "val_vencido" => "20",
        "val_vencer" => "20",
        "tot_divida" => "20"
    );

    //Nome da tabela no banco de dados.
    const TabelaCliente = "kl_negociacao_cliente";
    const TabelaSimulacao = "kl_simulacao";
    const TabelaContrato = "kl_contrato";

    /**
     * Metodo responsavel por criar cliente com arquivo csv    
     */
    public function ExeCreate(array $data) {
        $this->Data = $data;

        $this->openTxt();
        $this->setCli();
        $this->setSimula();
        $this->setContrato();
    }

    /** Class resposavel por apagar todos os itens do negociação cliente */
    public function ExeDeleteAll() {
        $this->DeleteAll();
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

    /** Abre o arquivo txt e faz a leitura ate o fim do arquivo separando em partes distintas */
    private function openTxt() {
        //Lê o arquivo ate o fim da linha
        foreach ($this->Data as $linha):
            // Pega o primeiro elemento de cada linha
            $num = substr($linha, 0, 1);
            // Separa o cliente
            if ($num == 1):
                $this->Cli[] = $linha;
                $this->cont = count($this->Cli);
            endif;
            // Separa opções de pagamento (simulação)
            if ($num == 2):
                $this->Simula[] = $linha;
                $this->contSimula = count($this->Simula);
            endif;
            // Separa as operações envolvidas (contrato)
            if ($num == 3):
                $this->Contrato[] = $linha;
                $this->contContrato = count($this->Contrato);
            endif;
        endforeach;
    }

    /** Prepara os dados do arquivo aberto para clientes */
    private function setCli() {

        // Loop da linha da tabela cliente			
        for ($i = 0; $i < $this->cont; $i++):
            //Loop com tamanho dos campos 		
            foreach ($this->tamanho as $ky => $tm):
                //negociacao_id -1
                if ($ky == "codigo"):
                    $data['negociacao_id'] = substr($this->Cli[$i], 1, $tm);
                endif;
                //negociacao_agencia -16
                if ($ky == "ag"):
                    $data['negociacao_agencia'] = substr($this->Cli[$i], 16, $tm);
                endif;
                //negociacao_conta -26
                if ($ky == "ct"):
                    $data['negociacao_conta'] = substr($this->Cli[$i], 26, $tm);
                endif;
                //negociacao_nome -46
                if ($ky == "nome"):
                    $data['negociacao_nome'] = substr($this->Cli[$i], 46, $tm);
                endif;
                //negociacao_cpf_cnpj -96 
                if ($ky == "cpf_cnpj"):
                    $data['negociacao_cpf_cnpj'] = substr($this->Cli[$i], 96, $tm);
                endif;
                //negociacao_tipo -110
                if ($ky == "tip"):
                    $data['negociacao_tipo'] = substr($this->Cli[$i], 110, $tm);
                endif;
                //negociacao_endereco - 111
                if ($ky == "end"):
                    $data['negociacao_endereco'] = substr($this->Cli[$i], 111, $tm);
                endif;
                //negociacao_bairro -191
                if ($ky == "bairro"):
                    $data['negociacao_bairro'] = substr($this->Cli[$i], 191, $tm);
                endif;
                //`negociacao_cidade` - 221
                if ($ky == "cidade"):
                    $data['negociacao_cidade'] = substr($this->Cli[$i], 221, $tm);
                endif;
                //`negociacao_estado` - 251
                if ($ky == "uf"):
                    $data['negociacao_estado'] = substr($this->Cli[$i], 251, $tm);
                endif;
                //`negociacao_cep` - 253
                if ($ky == "cep"):
                    $data['negociacao_cep'] = substr($this->Cli[$i], 253, $tm);
                endif;
                //`negociacao_email` - 262
                if ($ky == "email1"):
                    $data['negociacao_email'] = substr($this->Cli[$i], 262, $tm);
                endif;
                //`negociacao_email_1` - 362
                if ($ky == "email2"):
                    $data['negociacao_email_1'] = substr($this->Cli[$i], 362, $tm);
                endif;
                //`negociacao_fone` - 462
                if ($ky == "f1"):
                    $data['negociacao_fone'] = substr($this->Cli[$i], 462, $tm);
                endif;
                //`negociacao_fone_tipo` - 476
                if ($ky == "ft1"):
                    $data['negociacao_fone_tipo'] = substr($this->Cli[$i], 476, $tm);
                endif;
                //`negociacao_fone1` -477
                if ($ky == "f2"):
                    $data['negociacao_fone1'] = substr($this->Cli[$i], 477, $tm);
                endif;
                //`negociacao_fone1_tipo` - 491
                if ($ky == "ft2"):
                    $data['negociacao_fone1_tipo'] = substr($this->Cli[$i], 491, $tm);
                endif;
                //`negociacao_fone2` -492
                if ($ky == "f3"):
                    $data['negociacao_fone2'] = substr($this->Cli[$i], 492, $tm);
                endif;
                //`negociacao_fone2_tipo` -506
                if ($ky == "ft3"):
                    $data['negociacao_fone2_tipo'] = substr($this->Cli[$i], 506, $tm);
                endif;
                //`negociacao_fone3` - 507
                if ($ky == "f4"):
                    $data['negociacao_fone3'] = substr($this->Cli[$i], 507, $tm);
                endif;
                //`negociacao_fone3_tipo` - 521
                if ($ky == "ft4"):
                    $data['negociacao_fone3_tipo'] = substr($this->Cli[$i], 521, $tm);
                endif;
                //`negociacao_fone4` - 522
                if ($ky == "f5"):
                    $data['negociacao_fone4'] = substr($this->Cli[$i], 522, $tm);
                endif;
                //`negociacao_fone4_tipo` - 536
                if ($ky == "ft5"):
                    $data['negociacao_fone4_tipo'] = substr($this->Cli[$i], 536, $tm);
                endif;
                //`negociacao_fone5` - 537
                if ($ky == "f6"):
                    $data['negociacao_fone5'] = substr($this->Cli[$i], 537, $tm);
                endif;
                //`negociacao_fone5_tipo` - 551
                if ($ky == "ft6"):
                    $data['negociacao_fone5_tipo'] = substr($this->Cli[$i], 551, $tm);
                endif;
                //`negociacao_valor_vencido` - 552
                if ($ky == "vencido"):
                    $data['negociacao_valor_vencido'] = substr($this->Cli[$i], 552, $tm);
                endif;
                //`negociacao_valor_vencer` - 572
                if ($ky == "vencer"):
                    $data['negociacao_valor_vencer'] = substr($this->Cli[$i], 572, $tm);
                endif;
                //`negociacao_valor_total` - 592
                if ($ky == "total"):
                    $data['negociacao_valor_total'] = substr($this->Cli[$i], 592, $tm);
                endif;
            endforeach;

            //Armazena o array
            $this->DataCliente = $data;
            //formata os dados
            $this->setDataCli();
            //Insere os dados
            $this->CreateCli();

        endfor;
    }

    /** Prepara os dados do arquivo aberto para simulação */
    private function setSimula() {

        // Loop da linha da tabela simulação			
        for ($s = 0; $s < $this->contSimula; $s++):

            //Loop com tamanho dos campos 		
            foreach ($this->tamanhoSimu as $kys => $tms):

                //negociacao_id -1
                if ($kys == "codigo_pag"):
                    $dataS['negociacao_id'] = substr($this->Simula[$s], 1, $tms);
                endif;
                //simulacao_parcela -16
                if ($kys == "parcela"):
                    $dataS['simulacao_parcela'] = substr($this->Simula[$s], 16, $tms);
                endif;
                //simulacao_valor_entrada - 18
                if ($kys == "entrada"):
                    $dataS['simulacao_valor_entrada'] = substr($this->Simula[$s], 18, $tms);
                endif;
                //simulacao_valor_parcela - 39
                if ($kys == "valor_parcela"):
                    $dataS['simulacao_valor_parcela'] = substr($this->Simula[$s], 39, $tms);
                endif;
                //simulacao_valor_desconto - 59
                if ($kys == "desconto"):
                    $dataS['simulacao_valor_desconto'] = substr($this->Simula[$s], 59, $tms);
                endif;
                //simulacao_valor_total - 79
                if ($kys == "total_pag"):
                    $dataS['simulacao_valor_total'] = substr($this->Simula[$s], 79, $tms);
                endif;
                //simulacao_vencimento - 99
                if ($kys == "data_venc"):
                    $dataS['simulacao_vencimento'] = substr($this->Simula[$s], 99, $tms);
                endif;

            endforeach;

            //Armazena o array com as simulações
            $this->DataSimulacao = $dataS;
            //formata os dados
            $this->setDataSimula();
            //Insere os dados
            $this->CreateSimula();

        endfor;
    }

    /** Prepara os dados do arquivo aberto para contrato */
    private function setContrato() {
        // Loop da linha da tabela simulação			
        for ($c = 0; $c < $this->contContrato; $c++):
            //Loop com tamanho dos campos 		
            foreach ($this->tamanhoContrato as $kyc => $tmc):
                //negociacao_id - 1
                if ($kyc == "codigo_contrato"):
                    $dataC['negociacao_id'] = substr($this->Contrato[$c], 1, $tmc);
                endif;
                //contrato_numero -16
                if ($kyc == "op"):
                    $dataC['contrato_numero'] = substr($this->Contrato[$c], 16, $tmc);
                endif;
                //contrato_codigo - 46
                if ($kyc == "c_produto"):
                    $dataC['contrato_codigo'] = substr($this->Contrato[$c], 46, $tmc);
                endif;
                //contrato_referencia - 56
                if ($kyc == "tipo_op"):
                    $dataC['contrato_referencia'] = substr($this->Contrato[$c], 56, $tmc);
                endif;
                //contrato_valor_debito - 96
                if ($kyc == "val_vencido"):
                    $dataC['contrato_valor_debito'] = substr($this->Contrato[$c], 96, $tmc);
                endif;
                //contrato_valor_vencimento - 116
                if ($kyc == "val_vencer"):
                    $dataC['contrato_valor_vencimento'] = substr($this->Contrato[$c], 116, $tmc);
                endif;
                //contrato_valor_total - 136
                if ($kyc == "tot_divida"):
                    $dataC['contrato_valor_total'] = substr($this->Contrato[$c], 136, $tmc);
                    var_dump($dataC['contrato_valor_total']);
                endif;
            endforeach;
            //Armazena o array com os contratos
            $this->DataContrato = $dataC;
            //formata os dados
            $this->setDataContrato();
            //Insere os dados
            $this->CreateContrato();

        endfor;
    }

    /** Prepara os dados para create */
    private function setDataCli() {
        $this->DataCliente = array_map('strip_tags', $this->DataCliente);
        $this->DataCliente = array_map('trim', $this->DataCliente);

        $chequeValor = new Check();
        $valorVencido = $chequeValor->MoedaUs($this->DataCliente['negociacao_valor_vencido']);
        $valorVencer = $chequeValor->MoedaUs($this->DataCliente['negociacao_valor_vencer']);
        $valorTotal = $chequeValor->MoedaUs($this->DataCliente['negociacao_valor_total']);

        unset($this->DataCliente['negociacao_valor_vencido'], $this->DataCliente['negociacao_valor_vencer'], $this->DataCliente['negociacao_valor_total']);

        // repassa os dados  
        $this->DataCliente['negociacao_valor_vencido'] = $valorVencido;
        $this->DataCliente['negociacao_valor_vencer'] = $valorVencer;
        $this->DataCliente['negociacao_valor_total'] = $valorTotal;
    }

    /** Prepara os dados Simulação */
    private function setDataSimula() {
        $this->DataSimulacao = array_map('strip_tags', $this->DataSimulacao);
        $this->DataSimulacao = array_map('trim', $this->DataSimulacao);

        $chequeValor = new Check();
        $valorEntrada = $chequeValor->MoedaUs($this->DataSimulacao['simulacao_valor_entrada']);
        $valorParcela = $chequeValor->MoedaUs($this->DataSimulacao['simulacao_valor_parcela']);
        $valorDesconto = $chequeValor->MoedaUs($this->DataSimulacao['simulacao_valor_desconto']);
        $valorTotal = $chequeValor->MoedaUs($this->DataSimulacao['simulacao_valor_total']);
        $vencimento = $chequeValor->DataUsa($this->DataSimulacao['simulacao_vencimento']);

        unset($this->DataSimulacao['simulacao_valor_entrada'], $this->DataSimulacao['simulacao_valor_parcela'], $this->DataSimulacao['simulacao_valor_desconto'], $this->DataSimulacao['simulacao_valor_total'], $this->DataSimulacao['simulacao_vencimento']);

        //Repassa os dados
        $this->DataSimulacao['simulacao_valor_entrada'] = $valorEntrada;
        $this->DataSimulacao['simulacao_valor_parcela'] = $valorParcela;
        $this->DataSimulacao['simulacao_valor_desconto'] = $valorDesconto;
        $this->DataSimulacao['simulacao_valor_total'] = $valorTotal;
        $this->DataSimulacao['simulacao_vencimento'] = $vencimento;
    }

    /** Prepara os dados contrato */
    private function setDataContrato() {
        $this->DataContrato = array_map('strip_tags', $this->DataContrato);
        $this->DataContrato = array_map('trim', $this->DataContrato);

        $chequeValor = new Check();
        $valorDebito = $chequeValor->MoedaUs($this->DataContrato['contrato_valor_debito']);
        $valorVencimento = $chequeValor->MoedaUs($this->DataContrato['contrato_valor_vencimento']);
        $valorTotal = $chequeValor->MoedaUs($this->DataContrato['contrato_valor_total']);


        unset($this->DataContrato['contrato_valor_debito'], $this->DataContrato['contrato_valor_vencimento'], $this->DataContrato['contrato_valor_total']);

        //Repassa os dados        
        $this->DataContrato['contrato_valor_debito'] = $valorDebito;
        $this->DataContrato['contrato_valor_vencimento'] = $valorVencimento;
        $this->DataContrato['contrato_valor_total'] = $valorTotal;
    }

    /** Verifica a existencia de alguma Rede social. */
//    private function setNome() {
//        $Where = (!empty($this->Negociacao_id) ? "negociacao_id != {$this->Negociacao_id} AND" : '');
//
//        $readName = new Read;
//        $readName->ExeRead(self::Tabela, "WHERE {$Where} negociacao_cpf_cnpj = :c", "c={$this->Data['negociacao_cpf_cnpj']}");
//
//        if ($readName->getResult()):
//            //$this->Data['cli_nome'] = $this->Data['cli_nome'] . '-' . $readName->getRowCount();
//            $this->Result = false;
//        //$this->Erro = array("Opa, você tentou cadastrar um cliente que já esta cadastrado no sitema!", KL_ERROR);
//        //header("Location: painel.php?exe=clientes/lista&erro=$this->Erro");
//        endif;
//    }

    /** Execulta a criação dos dados */
    private function CreateCli() {
        $create = new Create;
        $create->ExeCreate(self::TabelaCliente, $this->DataCliente);

        if ($create->getResult()):
            $this->Result = $create->getResult();
        //$this->Erro = array("<b>Sucesso:</b> O cliente {$this->DataCliente['negociacao_nome']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a criação dos dados simulação */
    private function CreateSimula() {
        $createS = new Create;
        $createS->ExeCreate(self::TabelaSimulacao, $this->DataSimulacao);

        if ($createS->getResult()):
            $this->Result = $createS->getResult();
        //$this->Erro = array("<b>Sucesso:</b> A simulação {$this->DataSimulacao['simulacao_id']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a criação dos dados contrato */
    private function CreateContrato() {
        $createC = new Create;
        $createC->ExeCreate(self::TabelaContrato, $this->DataContrato);

        if ($createC->getResult()):
            $this->Result = $createC->getResult();
        //$this->Erro = array("<b>Sucesso:</b> O contrato {$this->DataContrato['contrato_numero']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }    
        
    /** Execulta a exclusão de todos os da tabela negociacao_cliente */
    private function DeleteAll() {       
        
        $deletarAll = new DeleteAll();
        $deletarAll->ExeDeleteAll(self::TabelaCliente);

        if ($deletarAll->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, todo os dados da tabela negociacao_cliente foi excluido do sistema!", KL_ACCEPT);
        //header("Location: painel.php?exe=paginas/lista");
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel excluir os cliente do sistema!", KL_ERROR);
        endif;
    }

}
