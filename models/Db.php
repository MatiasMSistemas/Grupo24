<?php
/*To create a new instance, if it`s necessary, we need to use getInstance
*/

Class DB{

    private static $host = '127.0.0.1';
    private static $user = 'grupo24';
    private static $password = 'OTg0NTY5NDIzZGNj';
    private static $nameDB = 'grupo24';

    private static $_instance;

    /*construct is private to prevent the creation of the object by new*/
    private function __construct(){}

    /*Avoids cloning the object*/
    public function __clone(){}

    /*Avoid the use of unserialize*/
    public function __wakeup(){}
/*funcion para no tener mas de una conexion a la bbdd por usuario*/      
    public static function getInstance(){
        if (is_null(self::$_instance)){
            try{
                self::$_instance = new PDO("mysql:dbname=" . SELF::$nameDB . ";host=" . SELF::$host, SELF::$user, SELF::$password);
            }
            catch(PDOException $e){
                print "Error: ". $e->getMessage()."";
            }
        }
        return self::$_instance;
    }
}
?>