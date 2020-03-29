<?php
error_reporting(E_ALL);
spl_autoload_register(function ($class_name) {
    require '/var/www/html/proBilling/integration/classes/'.$class_name.'.class.php';
    
});

// Bloco utilizado para atualizar sistema.
function update() {
    shell_exec("mv -f /var/www/html/proBilling/integration/update/up/extensions.conf /etc/asterisk/");
    return 'Sucesso';
}

/*
 * Inicio de Bloco.
 */

try {
    $situ = update();
        
} catch (Exception $e) {
    echo 'Erro na atualização';
}
