<?php

/**
 * Rotas.class [ MODEL ]
 * Classe responsavel por realizar cadastro, alteração das Rotas. 
 * @copyright (c) 19/03/2018, Kleber de Souza BRAZISTELECOM
 */
class Rotas {

    private $Data;
    private $DataRota;
    private $Erro;
    private $Result;

    //Nome da tabela no banco de dados.
    const Tabela = "rotas";

    /**
     * Metodo inicial, responsavel por executar os dados para ramal iax   
     */
    public function ExeCreate(array $data) {
        $this->Data = $data;

        if ($this->Data):
            //Limpa a tabela para os novos dados
            $deleta = new DeleteAll();
            $deleta->ExeDeleteAll(self::Tabela);

            if ($deleta->getResult()):

                $this->setData();
                $this->Create();

            endif;

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
    
    /** Prepara os dados da rota master */
    private function setData() {

        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);
        
        $arr1 = explode("-", $this->Data['rota_tronco_fixo_m']);
        $this->DataRota['rota_tronco_fixo_m'] = $arr1[0];
        $this->DataRota['rota_tronco_tipo_fixo_m'] = $arr1[1];
        $arr2 = explode("-", $this->Data['rota_tronco_movel_m']);
        $this->DataRota['rota_tronco_movel_m'] = $arr2[0];
        $this->DataRota['rota_tronco_tipo_movel_m'] = $arr2[1];
        $arr3 = explode("-", $this->Data['rota_tronco_inter_m']);
        $this->DataRota['rota_tronco_inter_m'] = $arr3[0];
        $this->DataRota['rota_tronco_tipo_inter_m'] = $arr3[1];

        $arr4 = explode("-", $this->Data['rota_tronco_fixo_b1']);
        $this->DataRota['rota_tronco_fixo_b1'] = (!empty($arr4[0]) ? $arr4[0] : null);
        $this->DataRota['rota_tronco_tipo_fixo_b1'] = (!empty($arr4[1]) ? $arr4[1] : null);
        $arr5 = explode("-", $this->Data['rota_tronco_movel_b1']);
        $this->DataRota['rota_tronco_movel_b1'] = (!empty($arr5[0]) ? $arr5[0] : null);
        $this->DataRota['rota_tronco_tipo_movel_b1'] = (!empty($arr5[1]) ? $arr5[1] : null);
        $arr6 = explode("-", $this->Data['rota_tronco_inter_b1']);
        $this->DataRota['rota_tronco_inter_b1'] = (!empty($arr6[0]) ? $arr6[0] : null);
        $this->DataRota['rota_tronco_tipo_inter_b1'] = (!empty($arr6[1]) ? $arr6[1] : null);

        $arr7 = explode("-", $this->Data['rota_tronco_fixo_b2']);
        $this->DataRota['rota_tronco_fixo_b2'] = (!empty($arr7[0]) ? $arr7[0] : null);
        $this->DataRota['rota_tronco_tipo_fixo_b2'] = (!empty($arr7[1]) ? $arr7[1] : null);
        $arr8 = explode("-", $this->Data['rota_tronco_movel_b2']);
        $this->DataRota['rota_tronco_movel_b2'] = (!empty($arr8[0]) ? $arr8[0] : null);
        $this->DataRota['rota_tronco_tipo_movel_b2'] = (!empty($arr8[1]) ? $arr8[1] : null);
        $arr9 = explode("-", $this->Data['rota_tronco_inter_b2']);
        $this->DataRota['rota_tronco_inter_b2'] = (!empty($arr9[0]) ? $arr9[0] : null);
        $this->DataRota['rota_tronco_tipo_inter_b2'] = (!empty($arr9[1]) ? $arr9[1] : null);
    }

    /** Execulta a criação dos dados */
    private function Create() {
        $create = new Create;
        $create->ExeCreate(self::Tabela, $this->DataRota);

        if ($create->getResult()):
            $this->Result = $create->getResult();
            $this->Erro = array("<b>Sucesso:</b> Rota cadastrada no sietema!", KL_ACCEPT);
        endif;
    }

}

// close Clientes