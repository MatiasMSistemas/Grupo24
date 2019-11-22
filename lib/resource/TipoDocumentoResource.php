<?php

 include_once directorio . "models/TipoDocumento.php";
 include_once directorio . "models/Db.php";
include_once directorio . 'vendor/rmccue/requests/library/Requests.php';

class TipoDocumentoResource {

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
            self::$instance = new TipoDocumentoResource();
        }
        return self::$instance;
    }

    public function all($ini , $pageSize ) {
        if (isset($ini) && isset($pageSize)) {
            $sql = "SELECT t.id,
                       t.nombre
                FROM tipo_documento AS t
                WHERE t.borrado = 0
                LIMIT :init, :size";
            $qr = $this->db->prepare($sql);
            $ini = (int)$ini;
            $pageSize = (int)$pageSize;
            $qr->bindParam(":init", $ini, PDO::PARAM_INT);
            $qr->bindParam(":size", $pageSize, PDO::PARAM_INT);
            $qr->execute();
            $result = array();
            while ($td = $qr->fetch(PDO::FETCH_ASSOC)) {
                $result[] = new TipoDocumento($td);
            } 
            return $result;    
        }
    }

    public function allSinPag(){
      $headers = array('Accept' => 'application/json');
      $options = array();
      $collection_respose = Requests::get('https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-documento', $headers, $options);
      $json_collection = $collection_respose->body;
      $data = json_decode($json_collection, true);
      foreach($data as $single_json) {
          $result[]= new TipoDocumento($single_json);
        }
      return $result;
  }  

    public function get($id) {

        if (isset($id)) {
            $headers = array('Accept' => 'application/json');
            $options = array();
            $direccion = 'https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-documento/' . $id ;
            $response = Requests::get($direccion, $headers, $options);
            $json = $response->body;
            $data = json_decode($json, true);
            $tipoDocumento = new TipoDocumento($data);
            return $tipoDocumento;
        }
        else{
            return null;
        }
    }

    public function save($tipoDocumento) {

        
        if (isset($tipoDocumento)) {
            $sql = "UPDATE tipo_documento SET nombre = :nombre
                        WHERE id = :id";
            $qr = $this->db->prepare($sql);
            $id = $tipoDocumento->getIdTipoDocumento();
            $nombre = $tipoDocumento->getNombre();
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


    public function add($tipoDocumento) {
        
        if (isset($tipoDocumento)) {
            $sql = "INSERT INTO `tipo_documento` (nombre) VALUES 
                   (:nombre)";
            $qr = $this->db->prepare($sql);
            $nombre = $tipoDocumento->getNombre();
            $qr->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $qr->execute();
            return true;
        }
        else{
         return false;   
        }
    }

}