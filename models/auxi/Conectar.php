<?php 
require_once('config.php');
class Conectar {
    private $db;
    private static $instancia;
    private function __construct() {
        $dsn = 'mysql:host='.HOST.';dbname='.DB.';charset=utf8' ;
        try { 
            $this->db = new PDO( $dsn, USUARIO,CONTRASEÑA );
        } catch ( PDOException $e) {
            die( "¡Error!: " . $e->getMessage() . "<br/>");
        }
    }

    public static function coneccion(){
        { if (!isset(self::$instancia)) {
            self::$instancia = new Conectar();
            }
            return self::$instancia->db;
            }
    }

    public function __clone() { 
        trigger_error('La clonación de este objeto no está permitida', E_USER_ERROR); 
    }

  
  
    public function __destruct() {
        $this->db=null;
    }
}
?>