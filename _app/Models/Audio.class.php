<?php

/**
 * Audio.class [ MODEL ]
 * Classe responsagem por realizar, listagem, cadastro, alteração e exclusão de audio. 
 * @copyright (c) 27/04/2018, Kleber de Souza BRAZISTELECOM
 */
class Audio {

    //Atributos da classe
    private $Data;
    private $Audio_id;
    private $Audio_name;
    Private $Result;
    private $Erro;

    //Nome da tabela no banco de dados.
    const Tabela = "audio";
    const Arquivo = "arquivos/";

    /** Metodo resposavel por realizar o cadastro de audio  */
    public function ExeCreate($data) {

        $this->Data = $data;

        $this->setData();
        $this->setNome();
        if (!$this->Result && file_exists(self::Arquivo . $this->Data['audio_nome'] . '.gsm')):
            echo KLErro("Ops, você tentou cadastrar um Audio que já existe no sitema!", KL_ALERT);
        else:

            if (!empty($this->Data['audio_arquivo'])):
                $upload = new Upload(self::Arquivo);
                $upload->Media($this->Data['audio_arquivo'], $this->Data['audio_nome']);
            endif;

            if (isset($upload) && $upload->getResult()):
                $this->Data['audio_arquivo'] = $upload->getResult();
                $this->Create();
            else:
                echo "Não foi possivel o upload!";
            endif;

        endif;
    }

    /** Metodo resposavel por realizar o update dos audios  */
    public function ExeUpdate($audio_id, $data) {
        $this->Audio_id = (int) $audio_id;
        $this->Data = $data;

        $this->Update();
    }

    /** Metodo resposavel para realizar a exclusão de audio */
    public function ExeDelete($audio_id) {
        $this->Audio_id = (int) $audio_id;

        $this->DelArquivo();
        if ($this->Result):
            $this->Delete();
        else:
            $this->Erro = array("Erro ao tentar excluir o arquivo, Verifique!", KL_INFOR);
        endif;
    }

    /** Metodo resposavel por retornar os resultados  */
    public function getResult() {
        return $this->Result;
    }

    /** Metodo resposavel por retornar o erro ocorrido  */
    public function getErro() {
        return $this->Erro;
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */

    /** Prepara os dados create */
    private function setData() {

        $arquivo = $this->Data['audio_arquivo'];
        $status = $this->Data['audio_status'];
        unset($this->Data['audio_arquivo'], $this->Data['audio_status']);

        //Prepara o nome do arquivo
        $var = explode(".", $arquivo['name']);
        $nomeArquivo = Check::Name($var[0]);

        //Remonta o array Data
        $this->Data['audio_nome'] = $nomeArquivo;
        $this->Data['audio_arquivo'] = $arquivo;
        $this->Data['audio_status'] = $status;

        //$this->Data = array_map('strip_tags', $this->Data);
        //$this->Data = array_map('trim', $this->Data);
        
    }

    /** Verifica a existencia de alguma categoria. */
    private function setNome() {
        $Where = (!empty($this->Audio_id) ? "audio_id != {$this->Audio_id} AND" : '');

        $readName = new Read;
        $readName->ExeRead(self::Tabela, "WHERE {$Where} audio_nome = :c", "c={$this->Data['audio_nome']}");
        //$this->Result = $readName->getResult();
        if ($readName->getResult()):
            //$this->Data['galeria_nome'] = $this->Data['galeria_nome'] . '-' . $readName->getRowCount();
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
            $this->Erro = array("<b>Sucesso:</b> O Audio {$this->Data['audio_nome']} foi cadastrado no sietema!", KL_ACCEPT);
        endif;
    }

    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE audio_id = :id", "id={$this->Audio_id}");

        if ($update->getResult()):
            header("Location: painel.php?exe=gerenciamento/audios/lista");
        else:
            $this->Result = $update->getResult();
            $this->Erro = array("Erro, Não foi possivel alterar o status do audio!", KL_ERROR);
        endif;
    }

    /** Execulta a exclusão do arquivo do diretório */
    private function DelArquivo() {
        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE audio_id = :id", "id={$this->Audio_id}");
        $obj = $read->getResult();
        extract($obj[0]);
        if (!empty($audio_arquivo)):
            $arquivo = self::Arquivo . $audio_arquivo;
            if (file_exists($arquivo)):
                if (unlink($arquivo)):
                    $this->Result = TRUE;
                else:
                    $this->Result = FALSE;
                endif;
            endif;
        endif;
    }

    /** Execulta a exclusão dos dados */
    private function Delete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE audio_id = :id", "id={$this->Audio_id}");

        if ($deletar->getResult()): 
            $this->Result = $this->getResult();
        else:
            $this->Result = false;
            $this->Erro = array("Erro, Não foi possivel apagar o arquivo do sistema!", KL_ERROR);
        endif;
    }

}
