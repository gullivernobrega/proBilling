<?php
/**
 * by Kleber - Brazistelecom
 */
class Conexao {

    //Atribudos da classe
    private static $Host;
    private static $User;
    private static $Pass;
    private static $Bd;
    private static $Pdo;
    private static $Cpdo;

    //Construtor da classe
    public function __construct() {

        self::$Host = "localhost";
        self::$User = "root";
        self::$Pass = ""; //Brazis3122#
        self::$Bd = "probilling";
        
    }
    
    //Retorno da conexão
    public static function getConn() {
        
        return self::Conn();
        
    }

    /** METODOS PRIVADOS */
    
    // Conexão com o banco
    private static function Conn() {

        if (is_null(self::$Pdo)):

            try {

                self::$Pdo = new PDO("mysql:host=" . self::$Host . "; dbname=" . self::$Bd . ";charset=utf8", self::$User, self::$Pass);
                self::$Pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                
            } catch (PDOException $ex) {
                
                echo "Erro na Connexão " . $ex->getMessage();
                
            }

        endif;

        return self::$Pdo;
                   
    }

}
