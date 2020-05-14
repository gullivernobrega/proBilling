<?php
if (function_exists('pcntl_signal')) {
    pcntl_signal(SIGHUP, SIG_IGN);
}
require_once 'classes/phpagi.php';
require_once 'classes/Cdr.class.php';
require_once 'classes/Conn.class.php';

$conn = new Conn();
$Query = "SELECT queue_tipo FROM queues WHERE queue_name = 'Gulliver'";
$dados = $conn->Consultar($Query);
$queueTipo = $dados[0]['queue_tipo'];
var_dump($queueTipo);
exit();        

      







