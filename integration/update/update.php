<?php

spl_autoload_register(function ($class_name) {
    require '/var/www/html/proBilling/integration/classes/' . $class_name . '.class.php';
});

/*
 * Chamando Classe para pegar MAC da maquina
 */
$mac = new mac();
$mac->mac();
$macResult = $mac->resultMac();
$macAll = '';
// Colocando todos MAC em uma STRING apenas.
foreach ($macResult as $value) {
    $macAll = "$macAll" . "$value;";
}
$macAll = substr($macAll, 0, -1);


/*
 * Pegando versão atual do sistema
 */
$version = new consultaVersion();
$version->version();
$vrSistema = $version->result();


/*
 * URL para pegar versão atual do proBilling.
 */
$parametros = "Versao={$vrSistema['versao']}&Data={$vrSistema['data']}&mac={$vrSistema['mac']}";
$url = file_get_contents("http://probilling.com.br/update/get_version.php?" . $parametros);
$vrAtual = json_decode($url, 1);

/*
 * Bloco para efetuar atualização.
 */
if ($vrAtual['Status'] == 'Autorizado') {
    // Baixando o arquivo para update.
    $url = "http://probilling.com.br/update/up.tar.gz";
    $nome = "up.tar.gz";
    $pasta = "/var/www/html/proBilling/integration/update/$nome";
    file_put_contents($pasta, file_get_contents($url));
    
    //Executando processo para atualização.
    
    shell_exec("tar -C /var/www/html/proBilling/integration/update/ -xzvf /var/www/html/proBilling/integration/update/up.tar.gz");
    shell_exec("rm -rf /var/www/html/proBilling/integration/update/up.tar.gz");
    
    include '/var/www/html/proBilling/integration/update/up/update_system.php';
    
    if ($situ == 'Sucesso') {
        shell_exec("rm -rf /var/www/html/proBilling/integration/up/");
        //Salvar no banco update. $vrAtual['Versao']
        $conn = new Conn();
        $today = date("Y-m-d");
        $Query = "UPDATE `update` SET data='$today', versao='{$vrAtual['Versao']}', mac='$macAll' WHERE 1";
        $conn->Inserir($Query);
        echo 'Atualização realizada com sucesso';
    } else {
        shell_exec("rm -rf up/");
        echo 'Atualização falhou!';
    }
} else {
    echo 'Atualizacao desnecessária';
}



