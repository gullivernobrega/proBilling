<?php

include_once '/var/www/html/proBilling/_app/Config.inc.php';
//Data atual  
$dataHoje = date("Y-m-d");
//inicialização das variaveis

$contNorte = 0;
$contNordeste = 0;
$contCentroOeste = 0;
$contSudeste = 0;
$contSul = 0;

/**
 * Conecta com o banco do servidor 170: informar:
 * host, usuario, senha, e o banco. 
 * WHERE calldate like '{$dataHoje}%' AND disposition = 'ANSWERED'  ORDER BY calldate ASC 
 */
$campos = "dst";

  $read = new Select;
 //$read->ExeSelect("cdr", $campos ,"WHERE calldate >= '{$dataIni}' AND calldate <= '{$datafinal}' AND tipo <> '' ORDER BY calldate ASC");
  $read->ExeSelect("cdr", $campos ,"WHERE calldate like '{$dataHoje}%' AND dcontext = 'probilling' AND disposition = 'ANSWERED'");
  $resultado= $read->getResult();
    
  foreach ($resultado as $value) {
    extract($value);
    $valor = strlen($dst);
    
      if ($valor == 12) {
        //pega os 5 primeiros digitos do DST 
        $digito = substr($dst, 1, -10);
        if ($digito == 9):
            $contNorte++;
        elseif ($digito == 8 || $digito == 7):
            $contNordeste++;
        elseif ($digito == 6):
            $contCentroOeste++;
        elseif ($digito == 1 || $digito == 2 || $digito == 3):
            $contSudeste++;
        elseif ($digito == 4 || $digito == 5):
            $contSul++;
        endif;
    } elseif ($valor == 11) {

        $digito = substr($dst, 1, -9);
        if ($digito == 9):
            $contNorte++;
        elseif ($digito == 8 || $digito == 7):
            $contNordeste++;
        elseif ($digito == 6):
            $contCentroOeste++;
        elseif ($digito == 1 || $digito == 2 || $digito == 3):
            $contSudeste++;
        elseif ($digito == 4 || $digito == 5):
            $contSul++;
        endif;
}     
}

//`calldate`, `toNorte`, `toNordeste`, `toCentroOeste`, `toSudeste`, `toSul`
$arr['calldate'] = "{$dataHoje}";
$arr['toNorte'] = $contNorte;
$arr['toNordeste'] = $contNordeste;
$arr['toCentroOeste'] = $contCentroOeste;
$arr['toSudeste'] = $contSudeste;
$arr['toSul'] = $contSul;

$insert = new Create;
$insert->ExeCreate('cdr_regiao', $arr);

if (!$insert->getResult()):
    echo 'Inserção Falhou';
else:
    echo 'Inserção Concluida';
        
endif;


