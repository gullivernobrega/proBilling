<?php

/**
 * Usuario.class [ MODEL ]
 * Classe responsagem por realizar a manutenção de usuários do sistema
 * @copyright (c) 2016, Kleber de Souza BRAZISTELECOM
 */
class Config {

    /** Atributos da classe */
    private $Dados;
    private $config_id;
    private $Erro;
    Private $Resultado;
    private $Termos;

    /** Atributos para Recover */
    private $ddd;
    private $tts_provider;
    private $tts_id;
    private $tts_secret;
    

    /** Nome da tabela no bando de dados */
    const Tabela = 'config';

    /**
     * Metodo ExeCreate (cria)
     * Executa o Cadstro de usuario passando um array com dados 
     * @param array $Dados
     */
    public function ExeCreate(Array $dados) {
        $this->Dados = $dados;

        $this->setDataCreate();
        $this->UserCreate();
    }

    /**
     * Metodo ExeUpdate (Atualiza)
     * Atualiza o Cadstro de usuario passando um array com dados 
     * @param array $Dados
     */
    public function ExeUpdate($config_id, Array $dados) {
        $this->config_id = (int) $config_id;
        $this->Dados = $dados;
        
        $this->setData();
        $this->ConfigUpdate();
    }

    /**
     * Metodo ExeDelete
     * Apaga Usuario passando um id dos dados 
     * @param int $user_id
     */
    public function ExeDelete($user_id) {
        $this->config_id = (int) $user_id;

        $this->UserDelete();
    }

    /**
     * Metodo ExeRecover
     * Metodo para recuperar a senha de usuário; 
     * @param string $email
     */
    public function ExeRecover($email) {
        $this->Usuario_email = $email;        
        $this->UserRecover();
    }

    /**
     * <b>Verifica Usuario:</b> Executando o getResultado() é possivel verificar se foi ou não efetuado
     * o acesso com os dados.
     * @return BOOL $Var = true para login e false para erro
     */
    public function getResultado() {
        return $this->Resultado;
    }

    /**
     * <b>Obter Erro:</b> Retorna um array associativo com uma mensagem e um tipo de erro WS_.
     * @return ARRAY $Error = Array associativo com o erro. 
     */
    public function getError() {
        return $this->Erro;
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */

    /** Metodo para Prepara os dados para Update */
    private function setData() {
        $this->Dados = array_map('strip_tags', $this->Dados);
        $this->Dados = array_map('trim', $this->Dados);
        
        if (isset($this->Dados['config_ddd'])):
            $this->ddd = $this->Dados['config_ddd'];
            $this->tts_provider = $this->Dados['config_tts_provider'];
            $this->tts_id = $this->Dados['config_tts_id'];
            $this->tts_secret = $this->Dados['config_tts_secret'];
        endif;
        
    }

    /** Metodo para Preparar os dados para Create */
    private function setDataCreate() {
        $this->Dados = array_map('strip_tags', $this->Dados);
        $this->Dados = array_map('trim', $this->Dados);
        if (isset($this->Dados['user_senha'])):
            $senha = $this->Dados['user_senha'];
            $this->Dados['user_senha'] = md5($senha);
        endif;

        $this->Dados['user_nome'] = "{$this->Dados['user_nome']}";
        $this->Dados['user_email'] = "{$this->Dados['user_email']}";
        $this->Dados['user_ramal'] = "{$this->Dados['user_ramal']}";
        $this->Dados['user_login'] = "{$this->Dados['user_login']}";
        $this->Dados['user_senha'] = "{$this->Dados['user_senha']}";
        $this->Dados['user_nivel'] = "{$this->Dados['user_nivel']}";
        $this->Dados['user_status'] = "{$this->Dados['user_status']}";
        $this->Dados['user_registrado'] = "{$this->Dados['user_registrado']}";
        //$this->Dados['user_atualizacao'] = "NULL";        
        
    }

    /** Metodo para Verificar se os dados são nulos */
    private function checkDados() {
        if ($this->Dados['user_nome'] == "" || $this->Dados['user_email'] == "" || $this->Dados['user_login'] == ""):
            $this->Resultado = false;
        else:
            $this->Resultado = true;
        endif;
    }

    /** Metodo para Verificar Existencia de dados */
    private function checkExistencia() {
        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE user_email = :e", "e={$this->Dados['user_email']}");
        $res = $read->getRowCount();
        if ($res > 0):
            $this->Resultado = true;
        else:
            $this->Resultado = false;
        endif;
    }

    /** Metodo para Verificar se é um unico usuario e se o nivel de acesso e 3 */
    private function checkNivel() {
        $read = new Read();
        $read->ExeRead(self::Tabela, "WHERE user_id = :id", "id={$this->config_id}");
        //$conta = $read->getLinhas();
        $obj = $read->getResult();

        //$nivel = mysqli_fetch_object($obj);
        if ($this->Dados['user_nivel'] != 3): // < $nivel->user_nivel
            //$this->Erro = array("Este nivel de acesso não pode ser alterado!", KL_ERROR);
            $this->Resultado = false;
        else:
            $this->Resultado = true;
        endif;
    }

    /** Metodo para gerar senha aleatorio provisorio */
    private function GeraPass() {
        $tamanho = mt_rand(6, 10);
        $all_str = "abcdefghijlkmnopqrstuvxyzwABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $senha = "";
        for ($i = 0; $i <= $tamanho; $i++) {
            $senha .= $all_str[mt_rand(0, 61)];
        }
        $this->Senha = $senha;
    }

    /**
     * Metodo responssavel por Alterar os dados na tabela
     */
    private function ConfigUpdate() {
 
            $alterar = new Update();
            $alterar->ExeUpdate(self::Tabela, $this->Dados, "WHERE config_id = :id", "id={$this->config_id}");

            if ($alterar->getResult()):
                $this->Erro = array("Configurações alterada com sucesso no sistema.", KL_ACCEPT);
                $this->Resultado = $alterar->getResult();
            else:
                $this->Erro = array("<b>Erro</b> Não foi possivel realizar a alteração!", KL_ERROR);
                $this->Resultado = $alterar->getResult();
            endif;
        //endif;
    }

    /**
     * Metodo responssavel por crear os dados na tabela
     */
    private function UserCreate() {
        $this->checkDados();
        if (!$this->Resultado):
            $this->Erro = array("Existe campos em branco, Verifique!", KL_ERROR);
            $this->Resultado = false;
        else:            
            $create = new Create();
            $create->exeCreate(self::Tabela, $this->Dados);
            if ($create->getResult()):
                $this->Erro = array("<b>Cadastro </b>realizado com sucesso no sistema!", KL_ACCEPT);
                $this->Resultado = $create->getResult();
            else:
                $this->Erro = array("<b>Erro</b> Não foi possivel realizar o cadstro!", KL_ERROR);
                $this->Resultado = $create->getResult();
            endif;
        endif;
    }

    /**
     * Metodo responssavel por Apagar os dados na tabela
     */
    private function UserDelete() {
        $deletar = new Delete();
        $deletar->ExeDelete(self::Tabela, "WHERE user_id = {$this->config_id}");

        if ($deletar->getResult()):
            //$this->Erro = array(" O Imposto de codigo: {$this->Imposto_id} foi Deletado com sucesso no sistema!", KL_ACCEPT);
            $this->Resultado = $deletar->getResult();
        else:
            $this->Erro = array("<b>Erro</b> Não foi possivel Excluir o arquivo!", KL_ERROR);
            $this->Resultado = $deletar->getResult();
        endif;
    }

    /**
     * Metodo responssavel por Apagar os dados na tabela
     */
    private function UserRecover() {

        $read = new Read;
        $read->ExeRead(self::Tabela, "WHERE user_email = :e", "e={$this->Usuario_email}");
        $conta = $read->getRowCount();

        if ($conta > 0):
            /** Busca os dados compativeis com o email enviado */
            $result = $read->getResult();              
            extract($result[0]);
            $nome = $user_nome;            

            $this->GeraPass();
            /** Prepara a mensagem para o email */
            $mensagem = "";
            $mensagem .= "<b>Ola {$nome},</b>";
            $mensagem .= "<p>Você solicitou recentemente uma nova senha associada a esta conta.<br><br>";
            $mensagem .= "Esta é sua nova senha: <b>{$this->Senha}</b><br><br>";
            $mensagem .= "<i>Esta senha é provisória faça a alteração o mais rapido possivel.</i><br><br>";
            $mensagem .= "Se você não fez esta solicitação e acredita que sua conta foi comprometida,<br>";
            $mensagem .= "por favor entre em contato com o suporte.</p><br><br>";
            $mensagem .= "<p>- Suporte BRAZISTELECOM.</p>";

            /** Instacia a classe para envio de email */
            $recover = new Email;
            $recover->EnviaEmail("Alteração da Senha de usuário", $mensagem, REMETENTE, NOMEREMETENTE, $user_email, $nome);

            if ($recover->getResultado()):
                // condifica a senha com md5().
                $this->Senha = md5($this->Senha);
                $Dados['user_senha'] = "{$this->Senha}";

                /** Instancia a classe para alterar a senha no banco de dados */
                $update = new Update();
                $update->ExeUpdate(self::Tabela, $Dados, "WHERE user_id = :id AND user_email = :e", "id={$user_id}&e={$user_email}");
                if ($update->getResult()):
                    KLErro("Sua senha foi enviada para seu e-mail, Verifique!", KL_INFOR);
                    $this->Resultado = $update->getResult();
                else:
                    KLErro("<b>Erro,</b> Não foi possivel alterar a sua senha, entre em contato com o suporte!", KL_ERROR);
                    $this->Resultado = $update->getResult();
                endif;

            else:
                $this->Erro = array("<b>Erro</b> ao enviar a nova senha!", KL_ERROR);
                $this->Resultado = false;
            endif;

        else:
            $this->Erro = array("<b>Erro</b> não foi possivel uma nova senha!", KL_ERROR);
            $this->Resultado = false;
        endif;
    }

}
