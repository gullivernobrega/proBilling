<?php

/**
 * LoginAgent.class [ MODELS ]
 * Responsável por autenticar, validar, e checar usuário Agentes do sistema agents.
 * 
 * @copyright (c) 2018, Kleber de Souza KLSDESIGNER DESENVOLVIMENTO WEB
 */
class LoginAgent {

    private $Level;
    private $Login;
    private $Senha;
    private $Ramal;
    private $Error;
    private $Result;
    
    //Nome da tabela no banco de dados.
    const Tabela = "agents";

    /**
     * <b>Informar Level:</b> Informe o nível de acesso mínimo para a area a ser protegida.
     * @param INT $Level = Nivel mínimo para acesso
     */
    function __construct() {
    }

    /**
     * <b>Efetua Login:</b> Emvelopa um array atribuitivo com indices STRING user [agent_user], STRING pass.
     * Ao passar esse array na Exelogin() os dados são verificados e o login é feito
     * 
     * @param ARRAY $UserData = user [email], pass 
     */
    public function ExeLogin(array $UserData) {
        $this->Login = (string) strip_tags(trim($UserData['agent_user']));
        $this->Senha = (string) strip_tags(trim($UserData['agent_pass']));
        $this->Ramal = (string) strip_tags(trim($UserData['ramal']));

        $this->setLogin();
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
     * <b>Checar Login:</b> Executa o metodo para verificar a sessão USERLOGIN e verifica o acesso
     * para proteger telas restritas.
     * @return BOLEAN $login = Retorna true ou mata a sessão e retorna false!
     */
    public function CheckLogin() {
        if (empty($_SESSION['agentlogin']) || empty($_SESSION['agentlogin']['agent_name'])):
            unset($_SESSION['agentlogin']);
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
    private function setLogin() {
        if (!$this->Login || !$this->Senha || !$this->Ramal):
            $this->Error = array('Informe seu Usuário, Senha e Ramal Corretamente!', KL_INFOR);
            $this->Result = false;
        elseif (!$this->getUser()):
            $this->Error = array('Os Dados informados não são compativeis', KL_ALERT);
            $this->Result = false;
        else:            
            $this->Execute();
        endif;
    }

    // Verifica usuario e senha no banco de dados.
    private function getUser() {
        $this->Senha = md5($this->Senha);
       
        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE agent_user = :a AND agent_pass = :s ", "a={$this->Login}&s={$this->Senha}");
        if ($read->getResult()):
            $arr = $read->getResult();                          
            $varSession['agent_id'] = $arr[0]['agent_id'];  
            $varSession['agent_user'] = $arr[0]['agent_user'];  
            $varSession['agent_name'] = $arr[0]['agent_name'];  
//            $varSession['agent_pause'] = $arr[0]['agent_pause'];  
            $varSession['agent_ramal'] = $this->Ramal;  
            $this->Result = $varSession;            
//            $this->Result = $arr[0];            
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
        $_SESSION['agentlogin'] = $this->Result;        
        $this->Error = array("Olá {$this->Result['agent_name']}, seja bem vindo(a). Aguarde Redirecionamento!", KL_ACCEPT);
        $this->Result = true;
    }

}
