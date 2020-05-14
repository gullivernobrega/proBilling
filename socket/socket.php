<?php

//error_reporting(E_ALL);

require_once 'classes/Conn.class.php';

/* Allow the script to hang around waiting for connections. */
set_time_limit(0);

/* Turn on implicit output flushing so we see what we're getting as it comes in. */
ob_implicit_flush();

$conn          = new Conn();
$numeroEnviado = '';

$address = '192.168.1.5';
$port    = 8100;
$cont    = 1;

// create a streaming socket, of type TCP/IP
$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

socket_set_option($sock, SOL_SOCKET, SO_REUSEADDR, 1);

socket_bind($sock, $address, $port);

socket_listen($sock);

$Query    = "TRUNCATE TABLE socket";
$consulta = $conn->Inserir($Query);

// create a list of all the clients that will be connected to us..
// add the listening socket to this list
$clients = array($sock);

while (true) {
    echo "\n\n----------------START WHILE -------------------\n";
    $Query    = "SELECT * FROM socket WHERE 1";
    $consulta = $conn->Consultar($Query);

    // create a copy, so $clients doesn't get modified by socket_select()
    $read = $clients;
//    $write = null;
    $write  = $clients;
    $except = null;

    $num_changed_sockets = socket_select($read, $write, $except, 0);

    // check if there is a client trying to connect
    if (in_array($sock, $read)) {

        $newsock = socket_accept($sock);
        echo 'newsock=>' . $newsock . "\n\n";
        $clients[] = $newsock;

        if (!empty($newsock)) {

            $Query = "INSERT INTO socket (sock_resource_id, sock_user) VALUES ('{$newsock}','')";
            $conn->Inserir($Query);

        }
        $key = array_search($sock, $read);
        unset($read[$key]);
    } else {
//        sleep(1);
    }
    if (count($clients) == 1) {
        continue;
    }
    echo 'cleinte';
    print_r($clients);

    // loop through all the clients that have data to read from
    foreach ($clients as $key => $read_sock) {
        if ($key == 0) {
            continue;
        }

        echo "start foreach $read_sock $key\n";

        socket_set_option($read_sock, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 1, "usec" => 0));
        $data = socket_read($read_sock, 4096, PHP_BINARY_READ);

        if (strlen($data) > 0) {
            echo "------------------------------------ RECEBEU INFORMACAO ------------------------------------\n";
            print_r($data);
            echo "\n----------------------------------------------------------------------------------------------\n";

            if (preg_match('/LOGON/', $data)) {
                echo "Operador entrou\n";
                $user = trim(preg_replace('/LOGON|\#|\n|\r/', '', $data));
                echo 'user=' . $user . "\n";
                $Query = "UPDATE socket SET sock_user = '{$user}' WHERE sock_resource_id = '$read_sock'";
                echo "$Query \n";
                $conn->Inserir($Query);
                $env  = 'Logado';
                $stat = socket_write($read_sock, $env);
                continue;
            } else if (preg_match('/LOGOFF/', $data)) {
                $Query = "DELETE FROM  socket WHERE sock_resource_id = '{$read_sock}'";
                $conn->Inserir($Query);
                echo $Query . "\n";
                unset($clients[$key]);
                continue;
            }
        }

        echo "continue after soket_read\n";

        //if (date('s') == 10 || date('s') == 20) {

        //$sendmsg = @socket_write($read_sock, '1');
        if (preg_match('/LOGOFF/', $data)) {
            $Query = "DELETE FROM  socket WHERE sock_resource_id = '{$read_sock}'";
            $conn->Inserir($Query);
            echo $Query . "\n";
            unset($clients[$key]);
            continue;
        }
        //}

        $Query    = "SELECT * FROM socket WHERE sock_resource_id = '{$read_sock}'";
        $consulta = $conn->Consultar($Query);
        print_r($consulta);
        if (!isset($consulta[0]['sock_resource_id'])) {
            continue;
        }

        $resource = explode('#', $consulta[0]['sock_resource_id']);

        $sockID = $resource[1];
        echo 'sockID => ' . $sockID . ' == ' . $resource[1] . "\n";

        if ($read_sock == $resource[1] && !empty($consulta[0]['sock_user'])) {

            //disc8
            // Verificando o status do agente se esta falando.
            $Query = "SELECT * FROM agents_status WHERE agente = '" . $consulta[0]['sock_user'] . "'";
            $var   = $conn->Consultar($Query);
            echo $Query . "\n";
            print_r($var);
            if (!count($var)) {
                echo "agente nao esta logado\n";
                continue;
            }
            if ($var[0]['status'] == 'INUSE') {

                if ($consulta[0]['last_number'] == $var[0]['numero']) {
                    continue;
                }

                $sendNumero = "C" . $var[0]['codigo'] . ";" . $var[0]['numero'] . "#";
                echo "Send $sendNumero  to socket $sockID\n";
//                sleep(1);

                $sendmsg = @socket_write($read_sock, $sendNumero);
                if ($sendmsg === false) {
                    echo $Query = "DELETE FROM  socket WHERE sock_resource_id = '{$read_sock}'";
                    $conn->Inserir($Query);
                } else {
                    $Query = "UPDATE socket SET last_number = '" . $var[0]['numero'] . "' WHERE sock_id = " . $consulta[0]['sock_id'];
                    echo "$Query \n";
                    $conn->Inserir($Query);
                    print_r($sendmsg);
//                    sleep(1);
                }

            }
        }

    } // end of reading foreach

}
// close the listening socket
socket_close($sock);
