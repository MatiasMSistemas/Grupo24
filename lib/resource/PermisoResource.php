<?php

 include_once directorio . "models/Permiso.php";
 include_once directorio . "models/Db.php";
 include_once directorio . "lib/resource/RolResource.php";

class PermisoResource {

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
            self::$instance = new PermisoResource();
        }
        return self::$instance;
    }

    public function all($ini , $pageSize ) {
        if (isset($ini) && isset($pageSize)) {
         $sql = "SELECT p.id,
                       p.nombre
                FROM permiso AS p
                WHERE p.borrado = 0
                LIMIT :init, :size";
        
         $qr = $this->db->prepare($sql);
         $ini = (int)$ini;
         $pageSize = (int)$pageSize;
         $qr->bindParam(":init", $ini, PDO::PARAM_INT);
         $qr->bindParam(":size", $pageSize, PDO::PARAM_INT);
         $qr->execute();
         $result = array();
         while ($p = $qr->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Permiso($p);
         }
         return $result;  
        }
        
    }

    public function get($id) {

        if (isset($id)) {
          $sql = "SELECT p.id,
                       p.nombre
                FROM permiso AS p
                WHERE p.id = :id and p.borrado = 0";

          $qr = $this->db->prepare($sql);
          $qr->bindParam(":id", $id, PDO::PARAM_INT);
          $qr->execute();
          $row = $qr->fetch(PDO::FETCH_ASSOC);        
          if (!empty($row)) {
             $permiso = new permiso($row);
             return $permiso;
          }
          else {
            return null;
          }
        }
        else{
            return null;
        }
    }

    public function save($permiso) {
   
        if (isset($permiso)) {
            $sql = "UPDATE permiso SET nombre = :nombre
                        WHERE id = :id";

            $qr = $this->db->prepare($sql);
            $id = $permiso->getIdPermiso();
            $nombre = $permiso->getnombre();

            $qr->bindParam(":id", $id, PDO::PARAM_INT);
            $qr->bindParam(":nombre", $nombre, PDO::PARAM_STR);

            $qr->execute();
            return true;
            }
         else{    
            return false;
        }
    }

    public function delete($id) {

        if (isset($id)) {
            $roles =  array();
            $sql ="SELECT rol_id FROM rol_tiene_permiso WHERE permiso_id = :idPermiso";
            $qr = $this->db->prepare($sql);
            $qr->bindParam(":idPermiso", $id, PDO::PARAM_INT);
            $qr->execute();
            while ($r = $qr->fetch(PDO::FETCH_ASSOC)) {
               RolResource::getInstance()->quitarPermiso($r['rol_id'],$id);
            }
            $sql = "UPDATE permiso SET borrado = 1 WHERE id = :id";
            $qr = $this->db->prepare($sql);
            $qr->bindParam(":id", $id, PDO::PARAM_INT);
            $qr->execute();
            return true;
        }
        else{
            return false;
        }
    }


    public function add($permiso) {
        
        if (isset($permiso)) {
            $sql = "INSERT INTO `permiso` (nombre) VALUES 
                   (:nombre)";

            $qr = $this->db->prepare($sql);
            
            $id = $permiso->getIdPermiso();
            $nombre = $permiso->getNombre();
            $borrado = $permiso->getBorrado();

            $qr->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $qr->execute();
            return true;
        }
        else{
            return false;
        }
    }
    public function cantidadPermisos(){
      $sql="SELECT COUNT(*)
            FROM permiso 
            WHERE borrado = 0";
      $qr=$this->_db->prepare($sql);
      $qr->execute();
      $numero = $qr->fetch(PDO::FETCH_ASSOC);
      return $numero['COUNT(*)'];       
    }
    public function allSinPag( ) {
        $sql = "SELECT *
                FROM permiso 
                WHERE borrado = 0";
        $qr = $this->db->prepare($sql);
        $qr->execute();

        $result = array();
        while ($p = $qr->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Permiso($p);
        }

        return $result;
    }

}