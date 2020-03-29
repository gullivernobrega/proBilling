<?php
include "phpagi.php";
include "phpagi-asmanager.php";
include "./funcoes/getcalls.php";
//http://200.175.139.180:8082/bramo/?timeout=20

//print_r($_GET['timeout']);
//$timeout = isset($_GET['timeout']) ? $_GET['timeout'] * 500 :  10000;
$timesetion = 6;
$timeout = isset($timesetion) ? $timesetion * 500 :  10000;
?>
<!DOCTYPE html> 

<html lang="pt_br">
    <head>
        <meta charset="UTF-8">
	<title>Ligações Ativas </title>
	<link rel="stylesheet" href="css/geral.css" />
		<link rel="icon" href="img/favicon.ico" />
  
        <script type="text/javascript">
            setTimeout(function () { location.reload(1); }, <?php echo $timeout?>);
        </script>
    </head>
    <body>
        <div class="container">
            <div class="logo"><img src="img/logo.png"> </div>

            <div class="conteudo"> 
		<h1>Ligações Ativas </h1>
                
		<table border=1 width='100%' class="tabless">
                    <tr class="trc">
                    
		       
        		<th>Ramal</th>
        		<th>Nome do Cliente</th>
        		<th>CPF/CNPJ</th>
        		<th>Número</th>
        		<th>Tempo</th>
        		

			
		
		     </tr>
	<?php
       $variable = getCalls();
       $ct = 0;
        ?>
        <?php foreach ($variable as $key => $value) : 
//	$canal = explode ("/",$value['canal']);
	?>
            <tr class="trc1">

                        <td><?php echo $value['ramal']; ?></td>
                        <td><?php echo $value['nomedocliente']; ?></td>
                        <td><?php echo $value['cpf_cnpj']; ?></td>
                        <td><?php echo $value['numero']; ?></td>
                        <td><?php echo $value['duracao']; ?></td>
			

                        </tr>
					

					<!-- Condição ternaria -->
					<?php $ar = array($value['status']); print_r(rsort($a)); ?>
					
                    <?php endforeach; ?>
					<p class="tot">
					Total de ligacoes: <strong><?php echo count($variable);?></strong><br>
					Ligaçôes Ativas: <strong><?php echo $ct;?></strong>
					</p>
                </table>

            </div>
            <div class="rodape">
                <h6>© Brazistelecom 2016/2016  - Todos os direitos reservados</h4>
            </div>
        </div>
    </body>
</html>
