<?php

 include_once directorio . "models/TipoAgua.php";
 include_once directorio . "models/Db.php";
 include_once directorio . 'vendor/rmccue/requests/library/Requests.php';

class TipoAguaResource {

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
            self::$instance = new TipoAguaResource();
        }
        return self::$instance;
    }

    public function all($ini , $pageSize ) {
        if (isset($ini) && isset($pageSize)) {
           $sql = "SELECT t.id,
                       t.nombre
                FROM tipo_agua AS t
                WHERE t.borrado = 0
                LIMIT :init, :size";
           $qr = $this->db->prepare($sql);
           $ini = (int)$ini;
           $pageSize = (int)$pageSize;
           $qr->bindParam(":init", $ini, PDO::PARAM_INT);
           $qr->bindParam(":size", $pageSize, PDO::PARAM_INT);
           $qr->execute();

           $result = array();
           while ($ta = $qr->fetch(PDO::FETCH_ASSOC)) {
             $result[] = new TipoAgua($ta);
           } 
           return $result;    
        }
        
    }


    public function allSinPag(){
      $headers = array('Accept' => 'application/json');
      $options = array();
      $collection_respose = Requests::get('https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-agua', $headers, $options);
      $json_collection = $collection_respose->body;
      $data = json_decode($json_collection, true);
      foreach($data as $single_json) {
          $result[]= new TipoAgua($single_json);
        }
      return $result;
  }  

    public function get($id) {

        if (isset($id)) {
            $headers = array('Accept' => 'application/json');
            $options = array();
            $direccion = 'https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-agua/' . $id ;
            $response = Requests::get($direccion, $headers, $options);
            $json = $response->body;
            $data = json_decode($json, true);
            $tipoAgua = new TipoAgua($data);
            return $tipoAgua;
        }
        else{
            return null;
        }
    }

    public function save($tipoAgua) {

        
        if (isset($tipoAgua)) {
            $sql = "UPDATE tipo_agua SET nombre = :nombre
                        WHERE id = :id";


            $qr = $this->db->prepare($sql);
            
            $id = $tipoAgua->getIdTipoAgua();
            $nombre = $tipoAgua->getNombre();

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
            $sql = "UPDATE tipo_agua SET borrado = 1 WHERE id = :id";
            $qr = $this->db->prepare($sql);
            $qr->bindParam(":id", $id, PDO::PARAM_INT);
            $qr->execute();
            return true;
        }
        else{
            return false;
        }
    }


    public function add($tipoAgua) {
        
        if (isset($tipoAgua)) {
            $sql = "INSERT INTO `tipo_agua` (nombre) VALUES 
                   (:nombre)";

            $qr = $this->db->prepare($sql);
            
            $nombre = $tipoAgua->getNombre();
            $qr->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $qr->execute();
            return true;
        }
        else{
            return false;
        } 
    }

}