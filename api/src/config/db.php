<?php
class db{
    private $host='127.0.0.1';
    private $usuario='grupo24';
    private $password='OTg0NTY5NDIzZGNj';
    private $base='grupo24';

    //conectar a la bd
    public function conectar(){
        $conexion_mysql="mysql:host=$this->host;dbname=$this->base";
        $conexionBD= new PDO($conexion_mysql, $this->usuario, $this->password);
        $conexionBD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //ESTA LINEA ARREGLA LA CODIFICACION A UTF 8
        $conexionBD -> exec("set names utf8");

        return $conexionBD;
    }
}
?>