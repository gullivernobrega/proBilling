<?php
spl_autoload_register(function ($class_name) {
    require 'classes/' . $class_name . '.class.php';
});

$conn = new Conn();
$Query = "SELECT * FROM ura WHERE ura_nome = 'Gulliver-Nova'";
$uraOp = $conn->Consultar($Query);
$uraOp = $uraOp[0];

var_dump($uraOp);




