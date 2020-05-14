<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Teste paginação</title>


        <!--css-->
        <link href="../_app/Library/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/jquery.datetimepicker.min.css" rel="stylesheet">
        <link href="css/animate.css" rel="stylesheet">
        <link href="css/jPages.css" rel="stylesheet">
        <link href="css/geralTorpedo.css" rel="stylesheet">

    </head>
    <body>
        <?php
        require_once'./classes/Read.class.php';
        
        $buscaTeste =  new Read;
        $buscaTeste->exeRead("kl_users", "WHERE user_status = 'S'");
        if(!empty($buscaTeste->getResult())):
            var_dump($buscaTeste->getResult());
        else:
            echo'Não foi possivel a leitura!!!';
        endif;
        

        $busca = new Read;
        $busca->exeRead("ramalsip");
        ?>
        <div class="container mgTop">

            <!-- Future navigation panel -->
            <!--<div class="holder"></div>-->

            <!--tabela de listagem-->
            <table class="table table-responsive table-hover hover-color"> 
                <thead> 
                    <tr>   
                        <th>Numero</th>                                                        
                        <th>Callerid</th>                                             
                        <th>host</th>                             
                        <th>Mold</th>                             
                        <th>directmedia</th>       
                        <th>nat</th> 
                        <th>codec 1</th> 
                        <th>codec 2</th> 
                        <th>codec 3</th> 
                        <th>qualifily</th> 
                    </tr> 
                </thead> 
                <tbody id="paginacao">
                    <?php
                    if ($busca->getResult()):
                        $resultado = $busca->getResult();

                        foreach ($resultado as $ramal):
                            extract($ramal);
                            ?>

                            <tr>
                                <td scope="row"><?php echo $sip_numero; ?></td> 
                                <td scope="row"><?php echo $sip_callerid; ?></td> 
                                <td scope="row"><?php echo $sip_host; ?></td>
                                <td><?php echo $sip_dtmf_mold; ?></td> 
                                <td><?php echo $sip_directmedia; ?></td> 
                                <td><?php echo $sip_nat; ?></td> 
                                <td><?php echo $sip_codec1; ?></td> 
                                <td><?php echo $sip_codec2; ?></td> 
                                <td><?php echo $sip_codec3; ?></td> 
                                <td><?php echo $sip_qualifily; ?></td>                             
                            </tr>

                            <?php
                        endforeach;
                    else:
                        echo "Erro, Não foi possivel realizar a leitura!";
                    endif;
                    ?>
                </tbody> 
            </table><!--fim tabela-->

            <!--PAGINAÇÃO-->
            <div class="well corWell text-center">                                     
                <div class="holder"></div>
            </div>

        </div><!-- fecha container -->

        <!--JS-->
        <script src="../_cdn/jquery.js"></script>
        <script src="../_cdn/MaskedInput.js"></script>
        <script src="js/jquery.datetimepicker.full.min.js"></script>
        <script src="js/jPages.min.js"></script>
        <script src="js/paginacao.js"></script>
        <script src="js/geralDataTimePicker.js"></script>

    </body>
</html>
