<?php

/**
 * Email.class [ HELPER ]
 * Classe responsável por enviar e-mail autenticados com PHPMailer
 * @copyright (c) 16/08/2016, Kleber de Souza BRAZISTELECOM
 */
class Email {

    // Atributos
    private $Assunto;
    private $Mensagem;
    private $Remetente;
    private $NomeRemetente;
    private $Destino;
    private $NomeDestino;
    private $Reply;
    private $ReplyName;
    private $Anexo;
    private $Resultado;

    /** METODO PARA ENVIO DE E-MAIL  */
    public function EnviaEmail($assunto, $mensagem, $remetente, $nomeRemetente, $destino, $nomeDestino, $anexo = NULL, $reply = NULL, $replyName = NULL) {

        $this->Assunto = $assunto;
        $this->Mensagem = $mensagem;
        $this->Remetente = $remetente;
        $this->NomeRemetente = $nomeRemetente;
        $this->Destino = $destino;
        $this->NomeDestino = $nomeDestino;
        $this->Reply = $reply;
        $this->ReplyName = $replyName;
        $this->Anexo = $anexo;

        $this->SendEmail();
    }

    /** Retorna o resultado */
    function getResultado() {
        return $this->Resultado;
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
    // Conecta com o phpMailer e envia a mensagem
    private function SendEmail() {

        /** Inclui a classe do phpMailer */
        if (realpath("_app/Library/phpmailer/class.phpmailer.php")):
            require_once("_app/Library/phpmailer/class.phpmailer.php");
        else:
            require_once("../_app/Library/phpmailer/class.phpmailer.php");
        endif;


        $mail = new PHPMailer; // Inicia a classe
        //$mail->SMTPDebug = 3; // Ativar saída de depuração verboso
        $mail->isSMTP(); // Definir mailer para usar SMTP
        $mail->SMTPAuth = true; // Activar a autenticação SMTP
        $mail->isHTML(true); // Definir formato de e-mail para HTML

        $mail->Host = MAILHOST; // Servidor de envio
        $mail->Port = MAILPORT; // Porta de envio SMTP
        $mail->Username = MAILUSER; // EMAIL do servidor
        $mail->Password = MAILPASS; // Senha do email servidor

        $mail->From = $this->Remetente;
        $mail->FromName = utf8_decode($this->NomeRemetente);

        //$mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
        //$mail->setFrom('from@example.com', 'Mailer');
        //$mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
        //$mail->addAddress('ellen@example.com');               // Name is optional
        if ($this->Reply != NULL) {
            $mail->addReplyTo(utf8_decode($this->Reply), utf8_decode($this->ReplyName));
        }

        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');
        if ($this->Anexo != NULL) {
            $mail->addAttachment($this->Anexo['tmp_name'], $this->Anexo['name']); // Add attachments
        }
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        $mail->Subject = utf8_decode($this->Assunto); // Assunto
        $mail->Body = utf8_decode($this->Mensagem); // Mensagem
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        $mail->addAddress(utf8_decode($this->Destino), utf8_decode($this->NomeDestino)); // Email e nome do destino

        if ($mail->send()) {
            $this->Resultado = TRUE;
        } else {
            $this->Resultado = FALSE;
        }
    }

}
