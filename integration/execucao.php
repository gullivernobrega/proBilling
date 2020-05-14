<?php

$exe = shell_exec("ps ax | grep agents-status.php | grep -v grep| wc -l");

if ($exe == 0) {
   shell_exec("php /var/www/html/proBilling/integration/agents-status.php");
}

$exe2 = shell_exec("ps ax | grep socket.php | grep -v grep| wc -l");

if ($exe2 == 0) {
   shell_exec("php /var/www/html/proBilling/socket/socket.php");
}

$exe3 = shell_exec("ps ax | grep Discador.php | grep -v grep| wc -l");

if ($exe3 == 0) {
   shell_exec("php /var/www/html/proBilling/agi/Discador.php");
}

$exe4 = shell_exec("ps ax | grep control_pause.php | grep -v grep| wc -l");

if ($exe4 == 0) {
   shell_exec("php /var/www/html/proBilling/integration/control_pause.php");
}

$exe4 = shell_exec("ps ax | grep control-sms.php | grep -v grep| wc -l");

if ($exe4 == 0) {
   shell_exec("php /var/www/html/proBilling/integration/control-sms.php");
}

$today = date("H:i");

echo $today;

if ($today == date("05:01") ){
   shell_exec("php /var/www/html/proBilling/integration/update/update.php");

}


