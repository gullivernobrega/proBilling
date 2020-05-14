<?php

/**
 * Login.class [ MODELS ]
 * Responsável por autenticar, validar, e checar usuário do sistema de login
 * 
 * @copyright (c) 2016, Kleber de Souza KLSDESIGNER DESENVOLVIMENTO WEB
 */
class Login {

    private $Level;
    private $Login;
    private $Senha;
    private $Error;
    private $Result;

    /**
     * <b>Informar Level:</b> Informe o nível de acesso mínimo para a area a ser protegida.
     * @param INT $Level = Nivel mínimo para acesso
     */
    function __construct($Level) {
        $this->Level = (int) $Level;
    }

    /**
     * <b>Efetua Login:</b> Emvelopa um array atribuitivo com indices STRING user [email], STRING pass.
     * Ao passar esse array na Exelogin() os dados são verificados e o login é feito
     * 
     * @param ARRAY $UserData = user [email], pass 
     */
    public function ExeLogin(array $UserData) {
        $this->Login = (string) strip_tags(trim($UserData['login']));
        $this->Senha = (string) strip_tags(trim($UserData['senha']));

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
        if (empty($_SESSION['userlogin']) || $_SESSION['userlogin']['user_nivel'] < $this->Level):
            unset($_SESSION['userlogin']);
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
        if (!$this->Login || !$this->Senha):
            $this->Error = array('Informe seu Login e Senha Corretamente!', KL_INFOR);
            $this->Result = false;
        elseif (!$this->getUser()):
            $this->Error = array('Os Dados informados não são compativeis', KL_ALERT);
            $this->Result = false;
        elseif ($this->Result['user_nivel'] < $this->Level || $this->Result['user_status'] != 'S'):
            $this->Error = array("Desculpe {$this->Result['user_nome']}, você não tem permissão para acessar está área!", KL_ERROR);
            $this->Result = false;
        else:
            $this->Execute();
        endif;
    }

    // Verifica usuario e senha no banco de dados.
    private function getUser() {
        $this->Senha = md5($this->Senha);
       
        $read = new Read;
        $read->ExeRead("kl_users", "WHERE user_login = :n AND user_senha = :s ", "n={$this->Login}&s={$this->Senha}");
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
        
        $_SESSION['userlogin'] = $this->Result;
        $this->Error = array("Olá {$this->Result['user_nome']}, seja bem vindo(a). Aguarde Redirecionamento!", KL_ACCEPT);
        $this->Result = true;
    }

}
