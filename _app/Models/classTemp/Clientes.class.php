<?php

/**
 * Clientes.class [ MODEL ]
 * Classe responsavel por realizar cadastro, alteração e listagem de dados dos clientes. 
 * @copyright (c) 08/08/2016, Kleber de Souza BRAZISTELECOM
 */
class Clientes {

    private $Data;
    private $DataCsv;
    private $Cli_id;
    private $NomePdf;
    private $Erro;
    private $Result;

    //Nome da tabela no banco de dados.
    const Tabela = "kl_cliente";

    /**
     * Metodo responsavel por criar as páginas cms do site   
     */
    public function ExeCreate(array $data) {
        $this->Data = $data;

        $this->setData();
        $this->setNome();

        if ($this->Data['cli_pdf']):
            $upPdf = new Upload('../themes/americoadvogados/images');
            $this->NomePdf = Check::Name($this->Data['cli_nome']) . "-" . $this->Data['cli_data_vencimento'] . "-" . $this->Data['cli_num_boleto'] . "-" . date("His");
            $upPdf->File($this->Data['cli_pdf'], $this->NomePdf, "/boletos");
        endif;

        if (isset($upPdf) && $upPdf->getResult()):
            $this->Data['cli_pdf'] = $upPdf->getResult();
            $this->Create();
        else:
            $this->Data['cli_pdf'] = null;
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
    public function ExeUpdate($cli_id, $data) {
        $this->Cli_id = $cli_id;
        $this->Data = $data;

        $this->setData();
        //$this->setNome();

        /** Verifica se existe campos em branco */
        /* if (in_array('', $this->Data)):
          $this->Result = false;
          $this->Erro = array("<b>Erro ao Atualizar:</b> Para atualizar a categoria {$this->Data['categoria_nome']}, preencha todos os campos!", KL_ALERT);
          else: */

        if ($this->Data['cli_pdf']):
            $upPdf = new Upload('../themes/americoadvogados/images');
            $this->NomePdf = Check::Name($this->Data['cli_nome']) . "-" . $this->Data['cli_data_vencimento'] . "-" . $this->Data['cli_num_boleto'] . "-" . date("His");
            $upPdf->File($this->Data['cli_pdf'], $this->NomePdf, "/boletos");
        endif;

        if (isset($upPdf) && $upPdf->getResult()):
            $this->Data['cli_pdf'] = $upPdf->getResult();
            $this->Update();
        else:
            //$this->Data['social_image'] = null;
            unset($this->Data['cli_pdf']);
            $this->Update();
        endif;
        //endif;
    }

    /** Class resposavel por apagar itens da pagina Cms */
    public function ExeDelete($cli_id) {
        $this->Cli_id = (int) $cli_id;

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE cli_id = :id", "id={$this->Cli_id}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover um cliente que não existe no sistema!", KL_INFOR);
        else:
            $ObjPdf = $read->getResult();
            extract($ObjPdf[0]);

            $dir = "../" . REQUIRE_PATH . "/images";

            if (!empty($cli_pdf) && file_exists($dir . $cli_pdf)):
                unlink($dir . $cli_pdf);
                $this->Delete();
            else:
                $this->Delete();
            endif;
        endif;
    }

    /** Class resposavel por apagar todos os itens do cliente boleto */
    public function ExeDeleteAll() {

        $readAll = new Read;
        $readAll->ExeRead(self::Tabela);

        if ($readAll->getRowCount() > 0):
            //Diretorio dos boletos
            $dir = "../" . REQUIRE_PATH . "/images";

            foreach ($readAll->getResult() as $ObjPdf):
                extract($ObjPdf);
                
                if (!empty($cli_pdf) && file_exists($dir . $cli_pdf)):
                    unlink($dir . $cli_pdf);                    
                    $this->DeleteAll();
                else:
                    $this->DeleteAll();
                endif;
            endforeach;
        else:
            $this->Result = false;
            $this->Erro = array("Erro, não exidte clientes boletos cadastrado!", KL_INFOR);
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
        $cli_pdf = $this->Data['cli_pdf'];
        //$valor = $this->Data['cli_valor_boleto'];

        $chequeValor = new Check();
        $valor = $chequeValor->Moeda($this->Data['cli_valor_boleto']);

        unset($this->Data['cli_valor_boleto'], $this->Data['cli_pdf']);

        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

        // repassa os dados        
        $this->Data['cli_pdf'] = $cli_pdf;
        $this->Data['cli_valor_boleto'] = $valor;
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
        $Where = (!empty($this->Cli_id) ? "cli_id != {$this->Cli_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} cli_cpf = :c", "c={$this->Data['cli_cpf']}");

        if ($readName->getResult()):
            //$this->Data['cli_nome'] = $this->Data['cli_nome'] . '-' . $readName->getRowCount();
            $this->Result = false;
            $this->Erro = array("Opa, você tentou cadastrar um cliente que já esta cadastrado no sitema!", KL_ERROR);
        //header("Location: painel.php?exe=clientes/lista&erro=$this->Erro");
        endif;
    }

    /** Execulta a criação dos dados */
    private function Create() {
        $create = new Create;
        $create->ExeCreate(self::Tabela, $this->Data);

        if ($create->getResult()):
            $this->Result = $create->getResult();
            $this->Erro = array("<b>Sucesso:</b> O cliente {$this->Data['cli_nome']} foi cadastrado no sietema!", KL_ACCEPT);
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
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE cli_id = :id", "id=$this->Cli_id");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> O cliente {$this->Data['cli_nome']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE cli_id = :id", "id={$this->Cli_id}");

        if ($deletar->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, cliente foi excluido do sistema!", KL_ACCEPT);
        //header("Location: painel.php?exe=paginas/lista");
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel excluir o cliente do sistema!", KL_ERROR);
        endif;
    }

    /** Execulta a exclusão de todos os da tabela cliente */
    private function DeleteAll() {

        $deletarTudo = new DeleteAll();
        $deletarTudo->ExeDeleteAll(self::Tabela);

        if ($deletarTudo->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, todo os dados da tabela cliente boleto foi excluido do sistema!", KL_ACCEPT);
        //header("Location: painel.php?exe=paginas/lista");
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel excluir os clientes boletos do sistema!", KL_ERROR);
        endif;
    }

}// close Clientes
