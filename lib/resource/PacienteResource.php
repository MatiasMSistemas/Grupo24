<?php

 include_once directorio . "models/Paciente.php";
 include_once directorio . "models/Db.php";
 include_once directorio . "lib/resource/DatosDemograficosResource.php";

class PacienteResource {

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
            self::$instance = new PacienteResource();
        }
        return self::$instance;
    }

    public function all($ini , $pageSize ) {
        if(isset($ini) && isset($pageSize)){
         $sql = "SELECT p.id,
                       p.apellido,
                       p.nombre,
                       p.domicilio,
                       p.tel,
                       p.fecha_nac,
                       p.genero,
                       p.datos_demograficos_id,
                       p.obra_social_id,
                       p.tipo_doc_id,
                       p.numero
                FROM paciente AS p
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
            $result[] = new Paciente($p);
         }
         return $result;
       }
    }

    public function get($id) {

        if (isset($id)) {
            $sql = "SELECT p.id,
                       p.apellido,
                       p.nombre,
                       p.domicilio,
                       p.tel,
                       p.fecha_nac,
                       p.genero,
                       p.datos_demograficos_id,
                       p.obra_social_id,
                       p.tipo_doc_id,
                       p.numero
                FROM paciente AS p
                WHERE p.id = :id and p.borrado = 0";

            $qr = $this->db->prepare($sql);
            $qr->bindParam(":id", $id, PDO::PARAM_INT);
            $qr->execute();
            $row = $qr->fetch(PDO::FETCH_ASSOC);        
            if (!empty($row)) {
              $paciente = new Paciente($row);
              return $paciente;
            }
            else {
             return null;
            }
        }
        else{
            return null;
        }
        
    }
    public function getAntn($apellido , $nombre , $tipoDocumento, $numero , $ini , $pageSize){
          $sql = "SELECT p.id,
                       p.apellido,
                       p.nombre,
                       p.domicilio,
                       p.tel,
                       p.fecha_nac,
                       p.genero,
                       p.datos_demograficos_id,
                       p.obra_social_id,
                       p.tipo_doc_id,
                       p.numero
                FROM paciente AS p
                WHERE p.borrado = 0";
          if(!is_null($apellido)){
            $sql=$sql .  " and p.apellido LIKE :apellido";
          }
          if(!is_null($nombre)){
             $sql= $sql . " and p.nombre LIKE :nombre";
          }
          if(!is_null($tipoDocumento)){
              $sql = $sql . " and p.tipo_doc_id = :tipoDocumento";
          }
          if(!is_null($numero)){
            $sql = $sql . " and p.numero = :numero";
          }
          if (isset($ini) && isset($pageSize)) {
            $sql = $sql. " LIMIT :init , :size";
          }
          $qr = $this->db->prepare($sql);
          if(!is_null($apellido)){
            $apellido = '%' . $apellido . '%';
            $qr->bindParam(":apellido", $apellido, PDO::PARAM_STR);
          }
          if(!is_null($nombre)){
             $nombre = '%' . $nombre . '%';
             $qr->bindParam(":nombre", $nombre, PDO::PARAM_STR);
          }
          if(!is_null($tipoDocumento)){
              $tipoDocumento = (int)$tipoDocumento;
              $qr->bindParam(":tipoDocumento", $tipoDocumento, PDO::PARAM_INT);
          }
          if(!is_null($numero)){
            $numero = (int)$numero;
            $qr->bindParam(":numero", $numero, PDO::PARAM_INT);
          }
          if (isset($ini) && isset($pageSize)) {
            $ini = (int)$ini;
            $pageSize = (int)$pageSize;
            $qr->bindParam(":init", $ini, PDO::PARAM_INT);
            $qr->bindParam(":size",$pageSize,PDO::PARAM_INT);
          }
          $qr->execute();        
          $pasientes =array();
          while ($r = $qr->fetch(PDO::FETCH_ASSOC)) {
             $pasientes[] = new Paciente($r);
          }
          return $pasientes;     
    }

    public function getByParametros($paciente){
      if (isset($paciente)){
        $sql= "SELECT id
               FROM paciente
               WHERE apellido = :apellido and nombre = :nombre and domicilio = :domicilio and tel = :tel and fecha_nac = :fecha_nac and 
                     genero = :genero and datos_demograficos_id = :datosDemograficos and obra_social_id = :obraSocial and 
                     tipo_doc_id = :tipoDoc and numero = :numero and borrado = 0";
        $qr = $this->db->prepare($sql);
        $apellido = $paciente->getApellido(); 
        $nombre = $paciente->getNombre();
        $domicilio = $paciente->getDomicilio(); 
        $tel = $paciente->getTel();
        $fechaNac = $paciente->getFechaNac(); 
        $genero = (int)$paciente->getGenero();
        $datosDemograficos = (int)$paciente->getDatosDemograficos(); 
        $obraSocial = (int)$paciente->getObrasocial();
        $tipoDoc = (int)$paciente->getTipoDocumento(); 
        $numero = (int)$paciente->getNumero();
        $fechaDeNac = date("Y-m-d H:i:s", strtotime($fechaNac)); 
        $qr->bindParam(":apellido", $apellido, PDO::PARAM_STR);
        $qr->bindParam(":nombre", $nombre, PDO::PARAM_STR);
        $qr->bindParam(":domicilio",$domicilio,PDO::PARAM_STR);
        $qr->bindParam(":tel",$tel,PDO::PARAM_STR);
        $qr->bindParam(":fecha_nac",$fechaDeNac,PDO::PARAM_STR);
        $qr->bindParam(":genero",$genero,PDO::PARAM_INT);
        $qr->bindParam(":datosDemograficos",$datosDemograficos,PDO::PARAM_INT);
        $qr->bindParam(":obraSocial",$obraSocial,PDO::PARAM_INT);
        $qr->bindParam(":tipoDoc",$tipoDoc,PDO::PARAM_INT);
        $qr->bindParam(":numero",$numero,PDO::PARAM_INT);
        $qr->execute();
        $row = $qr->fetch(PDO::FETCH_ASSOC);
        if(!is_null($row)){
          $id = $row['id'];
          return $id;  
        }
        else{
          return null;
        }                    
      }
      else{
        return null;
      }
    }

    public function save($paciente) {

        if (isset($paciente)) {
            $sql = "UPDATE paciente SET apellido = :apellido,
                        nombre = :nombre,
                        domicilio = :domicilio,
                        tel = :tel,
                        fecha_nac = :fechaNac,
                        genero = :genero,
                        datos_demograficos_id = :datosDemograficos,
                        obra_social_id = :obraSocial,
                        tipo_doc_id = :tipoDocumento,
                        numero = :numero
                        WHERE id = :id";

            $qr = $this->db->prepare($sql);
            
            $id = $paciente->getIdPaciente();
            $apellido = $paciente->getApellido();
            $nombre = $paciente->getNombre();
            $domicilio =$paciente->getDomicilio();
            $tel =$paciente->getTel();
            $fechaNac = $paciente->getFechaNac();
            $genero = (int)$paciente->getGenero();
            $datosDemograficos = (int)$paciente->getDatosDemograficos();
            $obraSocial = (int)$paciente->getObraSocial();
            $tipoDocumento = (int)$paciente->getTipoDocumento();
            $numero = (int)$paciente->getNumero();
            $fechaDeNac = date("Y-m-d H:i:s", strtotime($fechaNac));

            $qr->bindParam(":id", $id, PDO::PARAM_INT);
            $qr->bindParam(":apellido", $apellido, PDO::PARAM_STR);
            $qr->bindParam(":nombre" , $nombre , PDO::PARAM_STR);
            $qr->bindParam(":domicilio" , $domicilio , PDO::PARAM_STR);
            $qr->bindParam(":tel", $tel, PDO::PARAM_INT);
            $qr->bindParam(":fechaNac", $fechaDeNac, PDO::PARAM_STR);
            $qr->bindParam(":genero", $genero, PDO::PARAM_STR);
            $qr->bindParam(":datosDemograficos", $datosDemograficos, PDO::PARAM_STR);
            $qr->bindParam(":obraSocial", $obraSocial, PDO::PARAM_STR);
            $qr->bindParam(":tipoDocumento", $tipoDocumento, PDO::PARAM_STR);
            $qr->bindParam(":numero", $numero, PDO::PARAM_INT);
            $qr->execute();
            return true;
            }
         else{    
            return false;
        }
    }

    public function delete($id) {
        if (isset($id)) {
            $paciente = $this->get($id); 
            if(isset($paciente)){
              if (DatosDemograficosResource::getInstance()->delete($paciente->getDatosDemograficos())) {
                $sql = "UPDATE paciente SET borrado = 1 WHERE id = :id";
                $qr = $this->db->prepare($sql);
                $qr->bindParam(":id", $id, PDO::PARAM_INT);
                $qr->execute();
                return true;
              }
              else{
                return false;
              }
            }
            else{
              return false;
            } 
        }
        else{
            return false;
        }
    }

   public function getPorDatoDemo($id){
    if (isset($id)) {
      $sql ="SELECT id
             FROM paciente
             WHERE datos_demograficos_id = :id";
      $qr = $this->db->prepare($sql);
      $qr->bindParam(":id",$id,PDO::PARAM_INT);
      $qr->execute();
      $row = $qr->fetch(PDO::FETCH_ASSOC); 
      if (!empty($row)) {
               $idP = $row['id'];
               return $idP;
      }
      else{
        return null;
      }       
    }
    else{
      return null;
    }
   } 

    public function add($paciente) {

        
        if (isset($paciente)) {

            $sql = "INSERT INTO `paciente` (apellido, nombre, domicilio, tel, fecha_nac, genero, datos_demograficos_id, obra_social_id, tipo_doc_id, numero) VALUES 
                   (:apellido, :nombre, :domicilio, :tel, :fechaNac, :genero, :datosDemograficos, :obraSocial, :tipoDocumento, :numero)";

            $qr = $this->db->prepare($sql);
            
            $apellido = $paciente->getApellido();
            $nombre = $paciente->getNombre();
            $domicilio =$paciente->getDomicilio();
            $tel =$paciente->getTel();
            $fechaNac = $paciente->getFechaNac();
            $genero = (int)$paciente->getGenero();
            $datosDemograficos = (int)$paciente->getDatosDemograficos();
            $obraSocial = (int)$paciente->getObraSocial();
            $tipoDocumento = (int)$paciente->getTipoDocumento();
            $numero = (int)$paciente->getNumero();
            $fechaDeNac = date("Y-m-d H:i:s", strtotime($fechaNac));

            $qr->bindParam(":apellido", $apellido, PDO::PARAM_STR);
            $qr->bindParam(":nombre" , $nombre , PDO::PARAM_STR);
            $qr->bindParam(":domicilio" , $domicilio , PDO::PARAM_STR);
            $qr->bindParam(":tel", $tel, PDO::PARAM_INT);
            $qr->bindParam(":fechaNac", $fechaDeNac, PDO::PARAM_STR);
            $qr->bindParam(":genero", $genero, PDO::PARAM_STR);
            $qr->bindParam(":datosDemograficos", $datosDemograficos, PDO::PARAM_STR);
            $qr->bindParam(":obraSocial", $obraSocial, PDO::PARAM_STR);
            $qr->bindParam(":tipoDocumento", $tipoDocumento, PDO::PARAM_STR);
            $qr->bindParam(":numero", $numero, PDO::PARAM_INT);
            $qr->execute();
            return true;
         }
         else{
            return false;
         }
    }
    public function cantidadPacientes(){
      $sql="SELECT COUNT(*)
            FROM paciente 
            WHERE borrado = 0";
      $qr=$this->db->prepare($sql);
      $qr->execute();
      $numero =$qr->fetch(PDO::FETCH_ASSOC);
      return $numero['COUNT(*)'];       
    }
    public function allSinPag(){
      $sql="SELECT *
            FROM paciente 
            WHERE borrado = 0";
      $qr=$this->db->prepare($sql);
      $qr->execute();
      $result = array();
    
      while ($p = $qr->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Paciente($p);
      }
      return $result;
  }

}