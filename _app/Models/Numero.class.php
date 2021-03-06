<?php

/**
 * Numero.class [ MODEL ]
 * Classe responsavel por realizar cadastro, alteração e listagem de dados do Numero. 
 * @copyright (c) 15/05/2018, Kleber de Souza BRAZISTELECOM
 */
class Numero {

    private $Data;
    private $Numero_id;
    private $Erro;
    private $Result;
    private $Conta;
    private $Delimiter;
    private $Numero;
    private $DataNumero;
    private $Termos;
    private $NewStatus;
    private $OldStatus;
    private $Fildes;
    private $Place;
    private $DataId;

    //Nome da tabela no banco de dados.
    const Tabela = "numero";

    /**
     * Metodo inicial, responsavel por Inserir os dados.
     */
    public function ExeCreate(array $data) {
        $this->Data = $data;

        $this->setData();
//        $this->setNome();
//        if (!$this->Result):
//            $this->Result = TRUE;
//            $this->Erro = array("Opa, você tentou cadastrar um Numero que já esta cadastrado no sitema!", KL_ALERT);
//        else:
        $this->Create();
//        endif;
    }

    /**
     * Metodo inicial, responsavel por Inserir multiplos dados.
     */
    public function ExeCreateMult(array $data) {
        $this->Data = $data;

        $this->openCsv();
        $this->setDataMult();
        $this->CreateMult();
    }

    /**
     * Metodo responsavel por realizar alterações. 
     */
    public function ExeUpdate($numero_id, $data) {
        $this->Numero_id = (int) $numero_id;
        $this->Data = $data;

        $this->setData();
        $this->Update();
    }

    /**
     * Metodo responsavel por realizar alterações na pesquisa search     
     */
    public function ExeUpdateSearch($data) {

        if (empty($data['oldStatus'])):
            unset($data['oldStatus']);
        endif;

        $this->Data = $data;

        $this->setDataSearchUp();
        $this->UpdateSearch();
    }

    /** Class resposavel por apagar números */
    public function ExeDelete($numero_id) {
        $this->Numero_id = $numero_id;
        
        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE numero_id = :id", "id={$this->Numero_id}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover uma Numero que não existe no sistema!", KL_INFOR);
        else:
            $this->Delete(); 
        endif;
    }

    /** Class resposavel por apagar todos os números */
    public function ExeDeleteAll($data) {
        $this->Data = $data;

        if ($this->Data == 'del'):
            //unset($this->Data['acao']);

            $read = new Read;
            $read->ExeRead(self::Tabela);

            if (!$read->getResult()):
                $this->Result = false;
                $this->Erro = array("Erro, você tentou remover Numeros que não existe no sistema!", KL_INFOR);
            else:
                $this->DeleteAll();
            endif;

        endif;
    }

    /** Class resposavel por apagar todos os números pesquisados */
    public function ExeDeleteSearch($data) {
        $this->Data = $data;
        
        if ($this->Data['acao'] == 'delSearch'):
            unset($this->Data['acao']);

            $this->setDataSearchDel();
            $this->DeleteSearch();

        endif;
    }

    /** Retorna o resultado  */
    public function getResult() {
        return $this->Result;
    }

    /** Retorna o erro  */
    public function getErro() {
        return $this->Erro;
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */

    /** Abre o arquivo e manipula em array */
    private function openCsv() {
        //Abre o arquivo Csv
        $open = fopen($this->Data['arquivo']['tmp_name'], "r");
        //$linha = 0;
        while ($row = fgetcsv($open, 1000, "{$this->Data['delimiter']}")):
//            if ($linha++ == 0):
//                continue;
//            endif;
            //$numero[] = $row;
            $arr1 = (!empty($row[1]) ? $row[1] : NULL);
            $arr2 = (!empty($row[2]) ? $row[2] : NULL);
            $numero[] = [
                'numero_fone' => "'{$row[0]}'",
                'numero_nome' => "'{$arr1}'",
                'numero_cpf_cnpj' => "'{$arr2}'",
            ];
        endwhile;

        fclose($open);
        $this->Numero = $numero;
    }

    /** Prepara os dados create */
    private function setData() {
        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);
    }

    /** Prepara os dados create */
    private function setDataMult() {
        foreach ($this->Numero as $val):
            $val['numero_status'] = "'{$this->Data["numero_status"]}'";
            $val['agenda_id'] = $this->Data["agenda_id"];
            $this->DataNumero[] = $val;
        endforeach;

        //Montagem dos valores
        $place = "";
        foreach ($this->DataNumero as $val):
            $this->Fildes = implode(', ', array_keys($val));
            $place .= "(" . implode(", ", $val) . "),";
        endforeach;
        $this->Place = substr($place, 0, -1);
    }

    /** Prepara os dados create */
    private function setDataSearch() {

        if (!empty($this->Data['agenda_id']) && empty($this->Data['numero_status'])):
            $termo = "WHERE agenda_id = {$this->Data['agenda_id']}";
        elseif (!empty($this->Data['agenda_id']) && !empty($this->Data['numero_status'])):
            $termo = "WHERE agenda_id = {$this->Data['agenda_id']} AND numero_status = '{$this->Data['numero_status']}'";
        endif;

        //$this->Termos = $termo; 
        $read = new Read;
        $read->ExeRead(self::Tabela, $termo);

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover Numeros que não existe no sistema!", KL_INFOR);
        else:
            $this->Result = $read->getResult();
        endif;
    }

    /** Prepara os dados para update da pesquisa */
    private function setDataSearchUp() {
        if (!empty($this->Data['agenda_id']) && empty($this->Data['oldStatus'])):
            $termo = "WHERE agenda_id = {$this->Data['agenda_id']} ";
        elseif (!empty($this->Data['agenda_id']) && !empty($this->Data['numero_status']) && !empty($this->Data['oldStatus'])):
            $termo = "WHERE agenda_id = {$this->Data['agenda_id']} AND numero_status = '{$this->Data['oldStatus']}'";
            $this->OldStatus = $this->Data['oldStatus'];
            unset($this->Data['oldStatus']);
        endif;

        $read = new Read;
        $read->ExeRead(self::Tabela, $termo);

        if (!$read->getResult()):
            $this->Result = false;
            $this->Erro = array("Erro, você tentou remover Numeros que não existe no sistema!", KL_INFOR);
        else:
            $this->Result = $read->getResult();
        endif;
    }

    /** Prepara os dados para apagar */
    private function setDataSearchDel() {

        //Prepara o termo conforme a pesquisa
        if (!empty($this->Data['agenda_id']) && empty($this->Data['numero_status'])):
            $termo = "WHERE agenda_id = {$this->Data['agenda_id']}";
        elseif (!empty($this->Data['agenda_id']) && !empty($this->Data['numero_status'])):
            $termo = "WHERE agenda_id = {$this->Data['agenda_id']} AND numero_status = '{$this->Data['numero_status']}'";
        endif;
            
        $read = new Read;
        $read->ExeRead(self::Tabela, $termo);
        
        if (!empty($read->getResult())):
            foreach ($read->getResult() as $val):
                $DataId[] = $val['numero_id'];
            endforeach;
        endif;

        $this->Place = "";
        $this->Place .= implode(", ", $DataId);
        
        $this->Termos = "WHERE numero_id IN ({$this->Place})";
        
    }

    /** Verifica a existencia de alguma duplicação. */
    private function setNome() {
        $Where = (!empty($this->Numero_id) ? "numero_id != {$this->Numero_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} numero_cpf_cnpj = :c", "c={$this->Data['numero_cpf_cnpj']}");

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
            $this->Erro = array("<b>Sucesso:</b> O Numero {$this->Data['numero_fone']} do cliente {$this->Data['numero_nome']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a criação dos dados */
    private function CreateMult() {
        //foreach ($this->DataNumero as $data):

        $create = new Createcsv();
        $create->ExeCreate(self::Tabela, $this->Fildes, $this->Place);

        if ($create->getResult()):
            $this->Result = $create->getResult();
        endif;

        //endforeach;
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE numero_id = :id", "id=$this->Numero_id");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> O Numero {$this->Data['numero_fone']} do cliente {$this->Data['numero_nome']} foi alterado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a alteração de todos os números pesquisados */
    private function UpdateSearch() {
        //montagem dos ids dos numeros
        $num_id = "";
        
        foreach ($this->Result as $val):            
            $num_id .= $val['numero_id'] . ",";
        endforeach;
        
        //Retira a ultima virgula dos ids
        $num_id = substr($num_id, 0, -1);        
        
        // Array com o novo status
        $data['numero_status'] = $this->Data['numero_status'];        
        
        //Verifica a existencia dos termos
        if (!empty($this->OldStatus)):
            $termo1 = "WHERE numero_id IN ({$num_id}) AND numero_status = '{$this->OldStatus}' AND agenda_id = {$val['agenda_id']}";            
        else:
            $termo1 = "WHERE numero_id IN ({$num_id}) AND agenda_id = {$val['agenda_id']}";
        endif;
        
        //Instância da classe
        $update = new UpdateMult();
        $update->ExeUpdate(self::Tabela, $data, $termo1);

            if ($update->getResult()):
                $this->Result = $update->getResult();
            else:
                $this->Result = false;
                $this->Erro = array("Erro, Não foi possivel alterar a pesquisa no sistema!", KL_ERROR);
            endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE numero_id = :id", "id={$this->Numero_id}");

        if ($deletar->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, Numero foi excluido do sistema!", KL_ACCEPT);
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel excluir a Numero do sistema!", KL_ERROR);
        endif;
    }

    /** Execulta a exclusão de todos os números */
    private function DeleteAll() {
        $deletar = new DeleteAll();
        $deletar->ExeDeleteAll(self::Tabela);

        if ($deletar->getResult()):
            $this->Result = true;
            $this->Erro = array("Sucesso, Numeros excluido do sistema!", KL_ACCEPT);
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel excluir os Numero do sistema!", KL_ERROR);
        endif;
    }

    /** Execulta a exclusão de todos os números */
    private function DeleteSearch() {

        $deletar = new DeleteAll;
        $deletar->ExeDeleteSearch(self::Tabela, $this->Termos);

        if ($deletar->getResult()):
            $this->Result = true;
        //$this->Erro = array("Sucesso, Numeros excluido do sistema!", KL_ACCEPT);
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel excluir os Numeros da pesquisa!", KL_ERROR);
        endif;
    }

}

// close Clientes