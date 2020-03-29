<?php

/**
 * Chave.class [ MODELS ]
 * Responsável por autenticar, validar, e checar clientes do sistema de autonegociação
 * 
 * @copyright (c) 2017, Kleber de Souza BRAZISTELECOM
 */
class Chave {

    private $Level;
    private $CpfCnpj;
    
    /**A Chave Corresponte ao numero da agencia do cliente  */
    private $Chave;
    private $Error;
    private $Result;

    /**
     * <b>Informar Level:</b> Informe o nível de acesso mínimo para a area a ser protegida.
     * @param INT $Level = Nivel mínimo para acesso
     */
//    function __construct($Level) {
//        $this->Level = (int) $Level;
//    }

    /**
     * <b>Efetua Login:</b> Emvelopa um array atribuitivo com indices STRING user [email], STRING pass.
     * Ao passar esse array na Exelogin() os dados são verificados e o login é feito
     * 
     * @param ARRAY $UserData = user [email], pass 
     */
    public function ExeChave(array $Data) {
        
        $this->CpfCnpj = (string) strip_tags(trim($Data['negociacao_cpf_cnpj']));
        $this->Chave = (int) strip_tags(trim($Data['negociacao_id']));

        $this->setChave();
    }

    /**
     * <b>Verifica Login:</b> Executando o getResult() é possivel verificar se foi ou não efetuado
     * o acesso com os dados.
     * @return BOOL $Var = true para login e false para erro
     */
    public function getResult() {
        return $this->Result;
    }

    /**
     * <b>Obter Erro:</b> Retorna um array associativo com uma mensagem e um tipo de erro WS_.
     * @return ARRAY $Error = Array associativo com o erro. 
     */
    public function getError() {
        return $this->Error;
    }

    /**
     * <b>Checar Login:</b> Executa o metodo para verificar a sessão CLIENTE e verifica o acesso
     * para proteger telas restritas.
     * @return BOLEAN $login = Retorna true ou mata a sessão e retorna false!
     */
    public function CheckChave() {
        if (empty($_SESSION['cliente'])): //|| $_SESSION['cliente']['negociacao_cpf_cnpj'] < $this->Level
            unset($_SESSION['cliente']);
            return false;
        else:
            return true;
        endif;
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
    // Valida os dados e armazena os erros caso existam. Executa o login!
    private function setChave() {
        if (!$this->CpfCnpj || !$this->Chave):
            $this->Error = array('Informe seu Cpf ou Cnpj e a Chave de acesso Corretamente!', KL_INFOR);
            $this->Result = false;
        elseif (!$this->getCli()):
            $this->Error = array('Os Dados informados não são compativeis', KL_ALERT);
            $this->Result = false;        
        else:
            $this->Execute();            
        endif;
    }

    // Verifica Cliente e senha no banco de dados.
    private function getCli() {
        //$this->Senha = md5($this->Senha);       
        $read = new Read;
        $read->ExeRead("kl_negociacao_cliente", "WHERE negociacao_cpf_cnpj = :cpf AND negociacao_id = :chave", "cpf={$this->CpfCnpj}&chave={$this->Chave}");
        if ($read->getResult()):
            $arr = $read->getResult();            
            $this->Result = $arr[0];            
            return true;
        else:
            return false;
        endif;
    }

    // Executa o login armazenando a sessão.
    private function Execute() {
        if (!session_id()):
            session_start();
        endif;
        
        $_SESSION['cliente'] = $this->Result;
        $this->Error = array("Olá {$this->Result['cli_nome']}, seja bem vindo(a). Aguarde Redirecionamento!", KL_ACCEPT);
        $this->Result = true;
    }

}
