<?php
require_once './classes/Conn.class.php';
$conn = new Conn();
$Query = "SELECT * FROM cdr WHERE calldate >= '2018/06/25 08:00:00' AND calldate <= '2018/06/25 10:00:00' AND src >= '5000' AND src <= '5099' OR src = '0005' AND tipo <> '' ORDER BY calldate  LIMIT 10 ";
$conn->Consultar($Query);

var_dump($conn);



//$num = "06290521878";
//$numsub = substr($num, 1);
//echo "$numsub";
//echo "<hr>";
//
//$validaMovel = '/^[1-9]{2}[9][0-9]{8}$/';
//$validaFixo = '/^[1-9]{2}[2-6][0-9]{7}$/';
////40521878
////$validamovel = "/^[0]\d{2}(9|)[6789]\d{3}\d{4}$/";
////#^\d{2}(9|)[6789]\d{3}\d{4}$#
////
//////"^[0-9]{11}$";
////
//if (preg_match($validaMovel, $numsub)){
//    echo "Numero movel: $num";
//    
//}elseif (preg_match($validaFixo, $numsub)) {
//        echo 'Numero fixo:' .$num;    
//    
//} else {
//echo "Numero inexistente.";    
//}


        
        



