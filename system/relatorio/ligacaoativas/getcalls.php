<?php

include '_app/Helpers/phpagi-asmanager.php';

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


    $sql = "CREATE TABLE IF NOT EXISTS `activecalls` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `canal` VARCHAR( 80 ) NULL DEFAULT NULL,
		  `username` varchar(50) DEFAULT NULL,
		  `tronco` varchar(50) DEFAULT NULL,
		  `ndiscado` varchar(25) DEFAULT '0',
		  `codec` varchar(5) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
		  `status` varchar(16) NOT NULL,
		  `duration` varchar(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
		  `clid` varchar(20) NOT NULL,
		  `src` varchar(20) NOT NULL,
		  `dst` varchar(20) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	";
    try {
        $conn->exec($sql);
    } catch (Exception $e) {
        print_r($e);
    }
    $sql2 = 'TRUNCATE activecalls';
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
			$tronco = isset( $linha[6] ) ? $linha[6] : 1;
			$tronco = explode( "/", $tronco );
			$tronco = isset( $tronco[1] ) ? $tronco[1] : 0;
			$username = explode("SIP/", $canal);			
                        $username = explode("-", isset($username[1]));                        
			$username = $username[0];
		


			if (preg_match("/Outgoing Line/", $linha[6]) ) {
				continue;
			}

			if ( !$canal )
				continue;

			$result = $asmanager->Command( "core show channel $canal" );
			
			$arr2 = explode( "\n", $result["data"] );
                        
                        
                        
                        foreach ( $arr2 as $temp2 ) {


                            //pegando o callerid
                                    $ndiscado = 0;
				//print_r($temp2);
				if ( strstr( $temp2, 'Caller ID Name' ) ) {
					$arr3 = explode( "Caller ID Name:", $temp2 );
					$callerid = trim( rtrim( $arr3[1] ) );
                                        
                                        
				}
				//pegando numero discado
				if ( strstr( $temp2, 'dnid=' ) ) {
					$arr3 = explode( "dnid=", $temp2 );
					$ndiscado = trim( rtrim( $arr3[1] ) );
                                        
				}

				//pegando numero discado
				if ( strstr( $temp2, 'clid=' ) ) {
					$arr3 = explode( "clid=", $temp2 );
					$clid = trim( rtrim( $arr3[1] ) );
                                                                                
				}
				//pegando numero discado
				if ( strstr( $temp2, 'src=' ) ) {
					$arr3 = explode( "src=", $temp2 );
					$src = trim( rtrim( $arr3[1] ) );
                                        
                                        
				}
				//pegando numero discado
				if ( strstr( $temp2, 'dst=' ) ) {
					$arr3 = explode( "dst=", $temp2 );
					$dst = trim( rtrim( $arr3[1] ) );
				
                                       
                                }

				//pega codec
				if ( strstr( $temp2, 'NativeFormat' ) ) {
					$arr3 = explode( "NativeFormat:", $temp2 );
					$arr3 = explode( "(", $arr3[0] );
					$codec = preg_replace( "/\)/", "", $arr3[1] );
				
                                        
                                }
				//pega status
				if ( strstr( $temp2, 'State:' ) ) {
					$arr3 = explode( "State:", $temp2 );
					$status = trim( rtrim( $arr3[1] ) );
					
				}

				if ( strstr( $temp2, 'billsec' ) ) {
					$arr3 = explode( "billsec=", $temp2 );
					$seconds = trim( rtrim( $arr3[1] ) );

					$hours = floor( $seconds / 3600 );
					$seconds -= $hours * 3600;
					$minutes = floor( $seconds / 60 );
					$seconds -= $minutes * 60;

					$hours = $hours < 10 ? '0'.$hours: $hours;
					$minutes = $minutes < 10 ? '0'.$minutes: $minutes;
					$seconds = $seconds < 10 ? '0'.$seconds: $seconds;

					$cdr = "$hours:$minutes:$seconds";

				}
			}
                        
                        if($status == 'Up (6)' || $status == 'Ring (4)'){
                          $status = explode( " ", $status );  
                        }
                          
			
			
			$sql[] = "('$canal',  '$username',   '$tronco',  '$ndiscado',   '$codec',  '".$status[0]."', '$cdr', '$clid', '$src', '$dst')";
						
		}
		
		$sql2 = 'INSERT INTO activecalls (canal, `username`, `tronco`, `ndiscado`, `codec`, `status`, `duration`, `clid`, `src`, `dst`) VALUES '.implode( ',', $sql ).';';
                
		//echo $sql2;
		try {
			$conn->exec($sql2);
		} catch (Exception $e) {
			print_r($e);
		}

		$sql = "SELECT * FROM activecalls ORDER BY status DESC, duration DESC ";
		$result = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

		return $result;
                $fecha = $conn->NULL;
                

	}
}
function hangupCalls($canal) {
    $asmanager = new AGI_AsteriskManager;
    $conectaServidor = $asmanager->connect('localhost', 'proBilling', 'proBilling');
    $hcall = $asmanager->Command("hangup request $canal");
    if ($hcall['Response'] == "Follows"):
        header("Location: painel.php?exe=relatorio/ligacaoativas/lista");
    endif;
}
function escutarCalls($dst) {
    $asmanager = new AGI_AsteriskManager;
    $conectaServidor = $asmanager->connect('localhost', 'proBilling', 'proBilling');
    $hcall = $asmanager->Command("originate SIP/5055 extension *99$dst@probilling");
    if ($hcall['Response'] == "Follows"):
        header("Location: painel.php?exe=relatorio/ligacaoativas/lista");
    endif;
}
