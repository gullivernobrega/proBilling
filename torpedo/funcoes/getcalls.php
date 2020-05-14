<?php
include '/phpagi-asmanager.php';






function getCalls() {
    $asmanager = new AGI_AsteriskManager;
    $conectaServidor = $conectaServidor = $asmanager->connect('localhost', 'proBilling', 'proBilling');
    //$configFile = '/etc/asterisk/res_config_mysql.conf';
    //$array = parse_ini_file($configFile);        

    define('MYSQL_HOST', 'localhost');
    define('MYSQL_USER', 'root');
    define('MYSQL_PASSWORD', 'proBilling');
    define('MYSQL_DB_NAME', 'probilling');


    try {
        $conn = new PDO('mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DB_NAME, MYSQL_USER, MYSQL_PASSWORD);
    } catch (PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }



	$sql = "CREATE TABLE IF NOT EXISTS `activecalls_torpedo` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `ramal` VARCHAR( 20 ) NULL DEFAULT NULL,
		  `nomedocliente` varchar(50) DEFAULT NULL,
		  `cpf_cnpj` varchar(14) DEFAULT NULL,
		  `numero` varchar(20) DEFAULT NULL,
		  `duracao` varchar(16) NOT NULL,
		   PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	";
	try {
		$conn->exec($sql);
	} catch (Exception $e) {
		print_r($e);
	}
	$sql2 = 'TRUNCATE activecalls_torpedo';
	try {
		$conn->exec($sql2);
	} catch (Exception $e) {
		print_r($e);
	}

	$server = $asmanager->Command( "core show channels concise" );
	$arr = explode( "\n", $server["data"] );
	$sql =array();
	if ( $arr[0] != "" ) {


		foreach ( $arr as $temp ) {
			
			$linha = explode( "!", $temp );

			if ( !isset( $linha[1] ) )
				continue;

			$canal = $linha[0];
			//$canal = explode("/",$canal);
			//var_dump($canal = $linha[0]);
			//$canal = $canal[0];
			$tronco = isset( $linha[6] ) ? $linha[6] : 1;
			//var_dump(explode( "/", $tronco ));
			$tronco = explode( "/", $tronco );
			$tronco = isset( $tronco[1] ) ? $tronco[1] : 0;
			$username = explode("SIP/", $canal);			
                        $username = explode("-", isset($username[1]));                        
			$username = $username[0];
		//	echo '<pre>';


			if (preg_match("/Outgoing Line/", $linha[6]) ) {
				continue;
			}

			if ( !$canal )
				continue;

			$result = $asmanager->Command( "core show channel $canal" );

			$arr2 = explode( "\n", $result["data"] );
			
			foreach ( $arr2 as $temp2 ) {
				


                //pegando o callerid
                if (strstr($temp2, 'Context: ')) {
                    $arr3 = explode("Context: ", $temp2);
                    $contexto = trim(rtrim($arr3[1]));
                    
                }

                //Pegando o Ramal;
                if (strstr($temp2, 'Connected Line ID: ')) {
                    $arr3 = explode("Connected Line ID: ", $temp2);
                    $ramal = trim(rtrim($arr3[1]));
                }

                //Pegando o Nome do Cliente;
                if (strstr($temp2, 'NOME=')) {
                    $arr3 = explode("NOME=", $temp2);
                    $nome = trim(rtrim($arr3[1]));
                }
                //pegando CPF
                if (strstr($temp2, 'CPF=')) {
                    $arr3 = explode("CPF=", $temp2);
                    $cpf = trim(rtrim($arr3[1]));
                }

                //pegando numero recebido
                if (strstr($temp2, 'CALLED=')) {
                    $arr3 = explode("CALLED=", $temp2);
                    $numero = trim(rtrim($arr3[1]));
                }

                if (strstr($temp2, 'billsec')) {
                    $arr3 = explode("billsec=", $temp2);
                    $seconds = trim(rtrim($arr3[1]));

                    $hours = floor($seconds / 3600);
                    $seconds -= $hours * 3600;
                    $minutes = floor($seconds / 60);
                    $seconds -= $minutes * 60;

                    $hours = $hours < 10 ? '0' . $hours : $hours;
                    $minutes = $minutes < 10 ? '0' . $minutes : $minutes;
                    $seconds = $seconds < 10 ? '0' . $seconds : $seconds;

                    $tempo = "$hours:$minutes:$seconds";

				}
                                                
                       
			}


			$status = explode( " ", $status );
			
                        if ($contexto == 'torpedo'){
                        $sql[] = "('$ramal',  '$nome',   '$cpf',  '$numero',   '$tempo')";    
                        
                        }
                        
						
		}

		
		
		$sql2 = 'INSERT INTO activecalls_torpedo (`ramal`, `nomedocliente`, `cpf_cnpj`, `numero`, `duracao`) VALUES ' . implode(',', $sql) . ';';
		try {
			$conn->exec($sql2);
		} catch (Exception $e) {
			print_r($e);
		}
		
		$sql = "SELECT * FROM activecalls_torpedo ORDER BY duracao DESC ";
		//$sql= "SELECT * FROM kl_callonline ORDER BY dst ASC ";
		$result = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
                
                return $result;


	}
}
