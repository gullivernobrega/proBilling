<?php
// IP do servidor ####################
define("IP", $_SERVER['SERVER_ADDR']);

// CONFIGURAÇÕES DO SITE ######################
define('HOST', 'localhost');
define('USER', 'root');
define('PASS', 'proBilling');
define('DBSA', 'probilling');

// CONFIGURAÇÃO PARA EMAIL AUTENTICADO ######
define('MAILHOST', 'mail.brazistelecom.com.br');
define('MAILPORT', '26');
define('MAILUSER', 'desenvolvimento@brazistelecom.com.br');
define('MAILPASS', 'braziste123'); 

// DEFINE O REMETENTE #######################
define("REMETENTE", "suporte@brazistelecom.com.br");
define("NOMEREMETENTE", "BRAZISTELECOM");

// DEFINE O E-MAIL DE USUARIO DESTINO #######
define("EMAILDESTINO", "suporte@brazistelecom.com.br");
define("NOMEDESTINO", "ProBilling");

// DEFINE A INDENTIDADE DO SITE #############
define('SITENAME', 'ProBilling');
define('SITEDESC', 'Operadora Voip');// Descrição do site
define('SITEVERSION', 'v. 1.0');// Versão do site


// DEFINE A BASE DO SITE ####################
define('BASE', 'http://localhost/proBilling');
//define('THEME', 'novotema'); //parta onde fica o site
define('DIR', dirname(__FILE__));

//define('INCLUDE_PATH', BASE . '/themes/' . THEME);
//define('REQUIRE_PATH', 'themes/' . THEME);

// AUTO LOAD DE CLASSES ##################### 
function __autoload($Class) {
    // cDir responsavel pelos diretorios das classes;
    $cDir = array('Conn', 'Helpers', 'Models', 'vendor');
    $iDir = null;
    foreach ($cDir as $dirName) {        
        if (! $iDir && file_exists(DIR . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $Class . ".class.php") && !is_dir($dirName)) {
            include_once (DIR . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $Class . ".class.php");            
            $iDir = true;            
        }        
    }    
    if (! $iDir) {
        trigger_error("Não foi possivel incluir {$Class}.class.php", E_USER_ERROR);
        die;
    }
}

// TRATAMENTO DE ERROS ######################
// CSS constantes :: Mensagens de Erro
define('KL_ACCEPT', 'alert-success');
define('KL_INFOR', 'alert-info');
define('KL_ALERT', 'alert-warning');
define('KL_ERROR', 'alert-danger');

// KLErro :: Exibe erros Lançados :: Front
function KLErro($ErrMsg, $ErrNo, $ErrDie = null) {
    $CssClass = ($ErrNo == E_USER_NOTICE ? KL_INFOR : ($ErrNo == E_USER_WARNING ? KL_ALERT : ($ErrNo == E_USER_ERROR ? KL_ERROR : $ErrNo)));
    echo "<p class=\"trigger alert {$CssClass}\">{$ErrMsg}<span class=\"ajax_close\"> </span></p>";
    if ($ErrDie) {
        die;
    }
}
// PHPErro :: personaliza o gatilho do PHP
function PHPErro($ErrNo, $ErrMsg, $ErrFile, $ErrLine) {
    $CssClass = ($ErrNo == E_USER_NOTICE ? KL_INFOR : ($ErrNo == E_USER_WARNING ? KL_ALERT : ($ErrNo == E_USER_ERROR ? KL_ERROR : $ErrNo)));
    echo "<p class=\"trigger alert {$CssClass}\">";
    echo "<b>Erro na Linha: {$ErrLine} ::</b> {$ErrMsg}<br>";
    echo "<small>{$ErrFile}</small>";
    echo "<span class=\"ajax_close\"> </span></p>";
    if ($ErrNo == E_USER_ERROR) {
        die;
    }
}
set_error_handler('PHPErro');
