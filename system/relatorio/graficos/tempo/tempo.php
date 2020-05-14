<?php

//include_once '../../../../_app/Config.inc.php';
include_once '/var/www/html/proBilling/_app/Config.inc.php';

/** Data atual */
$dataHoje = date("Y-m-d");

/** inicialização das variaveis atendidas */
$contAtendidas1 = 0;
$contAtendidas2 = 0;
$contAtendidas3 = 0;
$contAtendidas4 = 0;
$contAtendidas5 = 0;
$contAtendidas6 = 0;
$contAtendidas7 = 0;
$contAtendidas8 = 0;
$contAtendidas9 = 0;
$contAtendidas10 = 0;
$contAtendidas11 = 0;
$contAtendidas12 = 0;
$contAtendidas13 = 0;
$contAtendidas14 = 0;
$contAtendidas15 = 0;
$contAtendidas16 = 0;

/** inicialização das variaveis não atendidas */
$contCanceladas1 = 0;
$contCanceladas2 = 0;
$contCanceladas3 = 0;
$contCanceladas4 = 0;
$contCanceladas5 = 0;
$contCanceladas6 = 0;
$contCanceladas7 = 0;
$contCanceladas8 = 0;
$contCanceladas9 = 0;
$contCanceladas10 = 0;
$contCanceladas11 = 0;
$contCanceladas12 = 0;
$contCanceladas13 = 0;
$contCanceladas14 = 0;
$contCanceladas15 = 0;
$contCanceladas16 = 0;

/**
 * Conecta com o banco do servidor 170: informar:
 * host, usuario, senha, e o banco. 
 * WHERE calldate like '{$dataHoje}%' AND disposition = 'ANSWERED'  ORDER BY calldate ASC 
 */
$campos = "calldate, dst, disposition";

  $read = new Select;
 //$read->ExeSelect("cdr", $campos ,"WHERE calldate >= '{$dataIni}' AND calldate <= '{$datafinal}' AND tipo <> '' ORDER BY calldate ASC");
  $read->ExeSelect("cdr", $campos ,"WHERE calldate like '{$dataHoje}%' AND dcontext = 'probilling' AND disposition != 'FAILED'");
  $resultado= $read->getResult();

  foreach ($resultado as $value) {
      extract($value);
    $dataServer = substr($calldate, 0, 10);
    //$data = substr($row170["calldate"], 0, 10);
    $hora = substr($calldate, 11, 8);
    if ($disposition == "ANSWERED") {
        if ($hora >= '06:00:00' && $hora <= '08:00:00') {
            $contAtendidas1++;
        } elseif ($hora >= '08:00:00' && $hora <= '09:00:00') {
            $contAtendidas2++;
        } elseif ($hora >= '09:00:00' && $hora <= '10:00:00') {
            $contAtendidas3++;
        } elseif ($hora >= '10:00:00' && $hora <= '11:00:00') {
            $contAtendidas4++;
        } elseif ($hora >= '11:00:00' && $hora <= '12:00:00') {
            $contAtendidas5++;
        } elseif ($hora >= '12:00:00' && $hora <= '13:00:00') {
            $contAtendidas6++;
        } elseif ($hora >= '13:00:00' && $hora <= '14:00:00') {
            $contAtendidas7++;
        } elseif ($hora >= '14:00:00' && $hora <= '15:00:00') {
            $contAtendidas8++;
        } elseif ($hora >= '15:00:00' && $hora <= '16:00:00') {
            $contAtendidas9++;
        } elseif ($hora >= '16:00:00' && $hora <= '17:00:00') {
            $contAtendidas10++;
        } elseif ($hora >= '17:00:00' && $hora <= '18:00:00') {
            $contAtendidas11++;
        } elseif ($hora >= '18:00:00' && $hora <= '19:00:00') {
            $contAtendidas12++;
        } elseif ($hora >= '19:00:00' && $hora <= '20:00:00') {
            $contAtendidas13++;
        } elseif ($hora >= '20:00:00' && $hora <= '21:00:00') {
            $contAtendidas14++;
        } elseif ($hora >= '21:00:00' && $hora <= '22:00:00') {
            $contAtendidas15++;
        } elseif ($hora >= '22:00:00' && $hora <= '23:00:00') {
            $contAtendidas16++;
        }
    } else {
        if ($hora >= '06:00:00' && $hora <= '08:00:00') {
            $contCanceladas1++;
        } elseif ($hora >= '08:00:00' && $hora <= '09:00:00') {
            $contCanceladas2++;
        } elseif ($hora >= '09:00:00' && $hora <= '10:00:00') {
            $contCanceladas3++;
        } elseif ($hora >= '10:00:00' && $hora <= '11:00:00') {
            $contCanceladas4++;
        } elseif ($hora >= '11:00:00' && $hora <= '12:00:00') {
            $contCanceladas5++;
        } elseif ($hora >= '12:00:00' && $hora <= '13:00:00') {
            $contCanceladas6++;
        } elseif ($hora >= '13:00:00' && $hora <= '14:00:00') {
            $contCanceladas7++;
        } elseif ($hora >= '14:00:00' && $hora <= '15:00:00') {
            $contCanceladas8++;
        } elseif ($hora >= '15:00:00' && $hora <= '16:00:00') {
            $contCanceladas9++;
        } elseif ($hora >= '16:00:00' && $hora <= '17:00:00') {
            $contCanceladas10++;
        } elseif ($hora >= '17:00:00' && $hora <= '18:00:00') {
            $contCanceladas11++;
        } elseif ($hora >= '18:00:00' && $hora <= '19:00:00') {
            $contCanceladas12++;
        } elseif ($hora >= '19:00:00' && $hora <= '20:00:00') {
            $contCanceladas13++;
        } elseif ($hora >= '20:00:00' && $hora <= '21:00:00') {
            $contCanceladas14++;
        } elseif ($hora >= '21:00:00' && $hora <= '22:00:00') {
            $contCanceladas15++;
        } elseif ($hora >= '22:00:00' && $hora <= '23:00:00') {
            $contCanceladas16++;
        }
    }
      
      
//      extract($value);
      
      
    
}
$contAtendidas = array(
    '1' => "$contAtendidas1",
    '2' => "$contAtendidas2",
    '3' => "$contAtendidas3",
    '4' => "$contAtendidas4",
    '5' => "$contAtendidas5",
    '6' => "$contAtendidas6",
    '7' => "$contAtendidas7",
    '8' => "$contAtendidas8",
    '9' => "$contAtendidas9",
    '10' => "$contAtendidas10",
    '11' => "$contAtendidas11",
    '12' => "$contAtendidas12",
    '13' => "$contAtendidas13",
    '14' => "$contAtendidas14",
    '15' => "$contAtendidas15",
    '16' => "$contAtendidas16"
);

$contCanceladas = array(
    '17' => "$contCanceladas1",
    '18' => "$contCanceladas2",
    '19' => "$contCanceladas3",
    '20' => "$contCanceladas4",
    '21' => "$contCanceladas5",
    '22' => "$contCanceladas6",
    '23' => "$contCanceladas7",
    '24' => "$contCanceladas8",
    '25' => "$contCanceladas9",
    '26' => "$contCanceladas10",
    '27' => "$contCanceladas11",
    '28' => "$contCanceladas12",
    '29' => "$contCanceladas13",
    '30' => "$contCanceladas14",
    '31' => "$contCanceladas15",
    '32' => "$contCanceladas16"

);

for ($i = 1; $i < 33; $i++) {
    $arr[$i]['calldate'] = "{$dataHoje}";
    $arr[$i]['callparametro'] = $i;
    if ($i < 17) {
        $arr[$i]['calltotal'] = $contAtendidas[$i];
    } elseif ($i > 16) {
        $arr[$i]['calltotal'] = $contCanceladas[$i];
    }
}
$i2 = 1;
$insert = new Create;

while ($i2 <= 32){
 $insert->ExeCreate('cdr_tempo', $arr[$i2]);  
 $i2++;   
}

exit();
