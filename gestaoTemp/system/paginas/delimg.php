<?php

// realiza a alteração
$cms_id = filter_input(INPUT_GET, "cms_id", FILTER_VALIDATE_INT);

if (!empty($cms_id)):
    
    $update = new Paginas();
    $update->ExeDeleteImg($cms_id);

    if ($update->getResult()):
        header("Location: painel.php?exe=paginas/lista");
    else:
        $erro = $update->getErro();
        KLErro($erro[0], $erro[1]);
    endif;

endif;
