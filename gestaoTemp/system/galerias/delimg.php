<?php

// realiza a alteração
$galeria_id = filter_input(INPUT_GET, "galeria_id", FILTER_VALIDATE_INT);

if (!empty($galeria_id)):
    
    $delImg = new Galeria();
    $delImg->ExeDeleteImg($galeria_id);

    if ($delImg->getResult()):
        header("Location: painel.php?exe=galerias/lista");
    else:
        $erro = $delImg->getErro();
        KLErro($erro[0], $erro[1]);
    endif;

endif;
