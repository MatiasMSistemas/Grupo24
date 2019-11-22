<?php
include_once directorio . "models/Sitio.php";
include_once directorio . "models/Db.php";

class SitioResource {

    private $db;
    static private $instance;
    
    private function __construct() {
        $this->db = DB::getInstance();
    }
    
    /*Avoids cloning the object*/
    public function __clone(){}

    /*Avoid the use of unserialize*/
    public function __wakeup(){}

    static public function getInstance() {
        if (is_null(self::$instance)){
            self::$instance = new SitioResource();
        }
        return self::$instance;
    }
   public function get(){
     $sql="SELECT * FROM sitio
           WHERE id = 1";
     $qr= $this->db->prepare($sql);
     $qr->execute(); 
     $sitio = new Sitio($qr->fetch(PDO::FETCH_ASSOC));
     return $sitio;
   }
   public function save($s){
    if(isset($s)){
            $sql = "UPDATE sitio SET titulo = :titulo, descripcion = :descripcion, mail = :mail, cantidadElementos= :cantidadElementos 
            WHERE id = :id";
            $qr = $this->db->prepare($sql);
            $id = $s->getIdSitio();
            $titulo = $s->getTitulo();
            $descripcion = $s->getDescripcion();
            $mail = $s->getMail();
            $cantidadElementos = $s->getCantidadElementos();
            $qr->bindParam(":id", $id, PDO::PARAM_INT);
            $qr->bindParam(":titulo", $titulo, PDO::PARAM_STR);
            $qr->bindParam(":descripcion", $descripcion, PDO::PARAM_STR);
            $qr->bindParam(":mail", $mail, PDO::PARAM_STR);
            $qr->bindParam(":cantidadElementos", $cantidadElementos, PDO::PARAM_INT);
            $qr->execute();
            return true;
        }    
    else{
        return false;
    }
   }
  public function cambiarEstado(){
            $sql="SELECT habilitado FROM sitio WHERE id =1";
            $qr = $this->db->prepare($sql);
            $qr->execute();
            $habilitado= $qr->fetch(PDO::FETCH_ASSOC);
          if ($habilitado['habilitado'] == 0) {
             $sql = "UPDATE sitio SET  habilitado= 1 WHERE id = 1";
             $qr = $this->db->prepare($sql);
             $qr->execute();
           } 
           else{
            $sql = "UPDATE sitio SET  habilitado= 0 WHERE id = 1";
            $qr = $this->db->prepare($sql);
            $qr->execute();
           }
           return true;
  }  

}    