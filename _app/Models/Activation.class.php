<?php

/**
 * Login.class [ MODELS ]
 * Responsável por autenticar, validar, e checar usuário do sistema de login
 * 
 * @copyright (c) 2016, Kleber de Souza KLSDESIGNER DESENVOLVIMENTO WEB
 */
class Activation {

    private $name;
    private $number;
    private $email;
    private $Error;
    private $Result;
    private $Data;
    private $at_id;

    /**
     * <b>Informar Level:</b> Informe o nível de acesso mínimo para a area a ser protegida.
     * @param INT $Level = Nivel mínimo para acesso
     */
//    function __construct($Level) {
//        $this->Level = (int) $Level;
//    }
    //Nome da tabela no banco de dados.
    const Tabela = "activation";

    /**
     * <b>Efetua Login:</b> Emvelopa um array atribuitivo com indices STRING user [email], STRING pass.
     * Ao passar esse array na Exelogin() os dados são verificados e o login é feito
     * 
     * @param ARRAY $UserData = user [email], pass 
     */
    public function CheckActivation() {
        $read = new Read;
        $read->ExeRead("activation");

        if ($read->getResult()) {
            $arr = $read->getResult();
            $this->Result = $arr[0];
            if ($this->Result["status"] == 1) {
                return true;
            } elseif (!$this->Result["status"]) {
                return 'ativação';
            }
        } else {
            return false;
        }
    }

    /**
     * Codigo para geração de chave para ativação de sistema.
     * @return string
     */
    public function keyGen() {
        $chars = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $serial = '';
        $max = count($chars) - 1;
        for ($i = 0; $i < 20; $i++) {
            $serial .= (!($i % 5) && $i ? '-' : '') . $chars[rand(0, $max)];
        }
        $serial = base64_encode($serial);
        return $serial;
    }
    
    /**
     * Metodo responsagem por realizar alterações na ativação     
     */
    public function ExeUpdate($at_id, $data) {
        $this->at_id = (int) $at_id;
        $this->Data = $data;
        
        $this->setData();
        $this->Update();
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
    public function ExeCreate(array $data) {

        $this->Data = $data;

        $this->ActivationsendBack();
        $this->ActivationsendFront();
        $this->setData();
        $this->Create();
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */

    /** Prepara os dados create */
    private function setData() {

        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);
        
    }

    /** Execulta a criação dos dados */
    private function Create() {
        $create = new Create;
        $create->ExeCreate(self::Tabela, $this->Data);

        if ($create->getResult()):
            $this->Result = $create->getResult();
            $this->Erro = array("<b>Sucesso:</b> Ativação feita com sucesso {$this->Data['name']} foi cadastrado no sistema!", KL_ACCEPT);
        endif;
    }
    
    /** Execulta a alteração dos dados */
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tabela, $this->Data, "WHERE id = :id", "id=$this->at_id");

        if ($update->getResult()):
            $this->Result = $update->getResult();
            $this->Erro = array("<b>Sucesso:</b> Ativação realizada. foi alterado no sistema!", KL_ACCEPT);
        endif;
    }
    

    private function ActivationsendBack() {

        $mensagem = "";
        $mensagem .= "<b>Nova instalação em nome de:  {$this->Data['name']},</b>";
        $mensagem .= "<b>Telefone de usuario:  {$this->Data['phone']},</b>";
        $mensagem .= "<b>E-mail de usuario:  {$this->Data['email']},</b>";
        $mensagem .= "<b>MAC de usuario:  {$this->Data['mac']},</b>";
        $mensagem .= "<b>licensa para uso:  {$this->Data['licensa']},</b>";
        $mensagem .= "<p>- Suporte BRAZISTELECOM.</p>";

        /** Instancia a classe para envio de email */
        $user_email = 'gulliver@brazistelecom.com.br';
        $nome = 'Gulliver';

        $sendEmail = new Email;
        $sendEmail->EnviaEmail("Nova instalação de sistema", $mensagem, REMETENTE, NOMEREMETENTE, $user_email, $nome);

        if ($sendEmail->getResultado()):

            KLErro("Cadastro Realizado com Sucesso, estamos redirecionando você para pagina de login!", KL_INFOR);
            $this->Resultado = $sendEmail->getResultado();


        else:
            $this->Erro = array("<b>Erro</b> não foi possivel realizar cadastro, verifique os dados informados.!", KL_ERROR);
            $this->Resultado = false;
        endif;
    }

    private function ActivationsendFront() {

        $mensagem = "";
        $mensagem .= "<h3>Obrigado por utilizar o sistema proBilling, segue abaixo o codigo key para ativação</h3>";
        $mensagem .= "<br>";
        $mensagem .= "<br>";
        $licensa = base64_decode($this->Data['licensa']);
        $mensagem .= "<b><h3>Ativação:  $licensa</h3></b>";
        $mensagem .= "<p>- Ativação proBilling.</p>";

        /** Instacia a classe para envio de email */
        $user_email = $this->Data['email'];
        $nome = $this->Data['name'];

        $sendEmail = new Email;
        $sendEmail->EnviaEmail("ProBilling Key", $mensagem, REMETENTE, NOMEREMETENTE, $user_email, $nome);

        if ($sendEmail->getResultado()):

            KLErro("Cadastro Realizado com Sucesso, estamos redirecionando você para pagina de login!", KL_INFOR);
            $this->Resultado = $sendEmail->getResultado();


        else:
            $this->Erro = array("<b>Erro</b> não foi possivel realizar cadastro, verifique os dados informados.!", KL_ERROR);
            $this->Resultado = false;
        endif;
    }

}
