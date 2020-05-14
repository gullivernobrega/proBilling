<?php
session_start();

unset($_SESSION['RAMAL']);
//session_destroy();

header("location: index.php");
