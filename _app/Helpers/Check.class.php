<?php

/**
 * Check.class [ HELPER ]
 * Classe responsavel por manipular e validar dados do sistema!
 * 
 * @copyright (c) 2016, Kleber de Souza KLSDESIGNER DESENVOLVIMENTO WEB
 */
class Check {

    private static $Data;
    private static $Format;

    /**
     * <b>Email</b> Verifica a validação do email valido.
     *  
     * @param STRING $Email
     * @return boolean = Retorna uma flag com TRUE ou FALSE
     */
    public static function Email($Email) {
        self::$Data = (string) $Email;
        self::$Format = '/[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\.\-]+\.[a-z]{2,4}$/';

        if (preg_match(self::$Format, self::$Data)):
            return true;
        else:
            return false;
        endif;
    }

    /**
     * <b>Name</b> Formata um texto para url amigavel retirando espaços, maiuculos, acentos e caracteres especiais
     * 
     * @param STRING $Nome
     * @return STRING
     */
    public static function Name($Nome) {
        self::$Format = array();
        self::$Format['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
        self::$Format['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

        self::$Data = strtr(utf8_decode($Nome), utf8_decode(self::$Format['a']), self::$Format['b']);
        self::$Data = strip_tags(trim(self::$Data));
        self::$Data = str_replace(' ', '-', self::$Data);
        self::$Data = str_replace(array('-----', '----', '---', '--'), '-', self::$Data);

        return strtolower(utf8_encode(self::$Data));
    }
    
    public static function NameLinpo($Nome) {
//        self::$Data = ereg_replace("[^a-zA-Z0-9]", "", strtr($Nome, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));        
//        return strtolower(utf8_encode(self::$Data));
        self::$Format = array();
        self::$Format['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
        self::$Format['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

        self::$Data = strtr(utf8_decode($Nome), utf8_decode(self::$Format['a']), self::$Format['b']);
        self::$Data = strip_tags(trim(self::$Data));
        self::$Data = str_replace(' ', '', self::$Data);
        self::$Data = str_replace(array('-----', '----', '---', '--'), '-', self::$Data);

        return self::$Data;
    }

    // Formata data (YYYY-MM-DD) formato americano
    public static function Data($Data) {
        self::$Format = explode(' ', $Data);
        self::$Data = explode('/', self::$Format[0]);

        if (empty(self::$Format[1])):
            self::$Format[1] = date('H:i:s');
        endif;

        self::$Data = self::$Data[2] . '-' . self::$Data[1] . '-' . self::$Data[0] . ' ' . self::$Format[1];
        return self::$Data;
    }

     /**
     *  Formata data (DD/MM/YYYY) formato padrão brasileiro
     * @param STRING $Data
     * @return STRING
     */
    public static function DataBr($Data) {
        self::$Format = explode(' ', $Data);
        self::$Data = explode('-', self::$Format[0]);

        if (empty(self::$Format[1])):
            //self::$Format[1] = date('H:i:s');
            self::$Data = self::$Data[2] . '/' . self::$Data[1] . '/' . self::$Data[0];
        else:
            self::$Data = self::$Data[2] . '/' . self::$Data[1] . '/' . self::$Data[0] . ' ' . self::$Format[1];
        endif;

        return self::$Data;
    }

    // Limita o numero de caracteres de um texto.
    public static function Words($String, $Limite, $Pointer = null) {
        self::$Data = strip_tags(trim($String));
        self::$Format = (int) $Limite;

        $ArrWords = explode(' ', self::$Data);
        $NumWords = count($ArrWords);
        $NewWords = implode(' ', array_slice($ArrWords, 0, self::$Format));

        $Pointer = (empty($Pointer) ? '...' : ' ' . $Pointer);
        $Result = ( self::$Format < $NumWords ? $NewWords . $Pointer : $String);

        return $Result;
    }

    // Realiza uma busca de categorias
    public static function CatByName($CategoryName) {
        $read = new Read;
        $read->ExeRead("kl_cms", "WHERE cms_nome = :nome", "nome={$CategoryName}");
        if ($read->getRowCount()):
            return $read->getResult()[0]['cms_id'];
        else:
            echo "O CMS {$CategoryName} não foi encontrada!";
            die;
        endif;
    }

    // Checa usuarios online validos e apaga os com datas expirados
    public static function UserOnline() {
        $now = date('Y-m-d H:i:s');
        $deleteUserOnline = new Delete;
        $deleteUserOnline->ExeDelete('kl_views_online', "WHERE views_end < :now", "now={$now}");

        $readUserOnLine = new Read;
        $readUserOnLine->ExeRead('kl_views_online');

        return $readUserOnLine->getRowCount();
    }

    public static function Image($ImageUrl, $ImageDesc, $ImageW = null, $ImageH = null) {
        self::$Data = 'uploads/' . $ImageUrl;

        if (file_exists(self::$Data) && !is_dir(self::$Data)):
            $patch = HOME;
            $imagem = self::$Data;
            return "<img src=\"{$patch}/tim.php?src={$patch}/{$imagem}&w={$ImageW}&h={$ImageH}\" alt=\"{$ImageDesc}\" title=\"{$ImageDesc}\"/>";
        else:
            return false;
        endif;
    }

}
