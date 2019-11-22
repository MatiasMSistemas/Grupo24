<?php

 include_once directorio . "models/ControlSalud.php";
 include_once directorio . "models/Db.php";

class ControlSaludResource {

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
            self::$instance = new ControlSaludResource();
        }
        return self::$instance;
    }

    public function all($ini, $pageSize, $idP) {
        if (isset($ini) && isset($pageSize)) {
          $sql = "SELECT c.id,
                       c.edad,
                       c.fecha,
                       c.peso,
                       c.vacunas_completas,
                       c.maduracion_acorde,
                       c.maduracion_observaciones,
                       c.ex_fisico_normal,
                       c.ex_fisico_observaciones,
                       c.pc,
                       c.ppc,
                       c.talla,
                       c.alimentacion,
                       c.observaciones_generales,
                       c.paciente_id,
                       c.user_id
                FROM control_salud AS c
                WHERE c.borrado = 0 and c.paciente_id = :idP
                LIMIT :init, :size";
          $qr = $this->db->prepare($sql);
          $ini = (int)$ini;
          $pageSize = (int)$pageSize;
          $qr->bindParam(":idP", $idP, PDO::PARAM_INT);
          $qr->bindParam(":init", $ini, PDO::PARAM_INT);
          $qr->bindParam(":size", $pageSize, PDO::PARAM_INT);
          $qr->execute();
          $result = array();
          while ($cs = $qr->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new ControlSalud($cs);
          }
          return $result;  
        }
        
    }

    public function get($id) {

        if (isset($id)) {
            $sql = "SELECT c.id,
                       c.edad,
                       c.fecha,
                       c.peso,
                       c.vacunas_completas,
                       c.maduracion_acorde,
                       c.maduracion_observaciones,
                       c.ex_fisico_normal,
                       c.ex_fisico_observaciones,
                       c.pc,
                       c.ppc,
                       c.talla,
                       c.alimentacion,
                       c.observaciones_generales,
                       c.paciente_id,
                       c.user_id
                FROM control_salud AS c
                WHERE c.id = :id and c.borrado = 0";

            $qr = $this->db->prepare($sql);
            $qr->bindParam(":id", $id, PDO::PARAM_INT);
            $qr->execute();
            $row = $qr->fetch(PDO::FETCH_ASSOC);        
            if (!empty($row)) {
                $controlSalud = new ControlSalud($row);
                return $controlSalud;
            }
            else{
                return null;
            }
        }
         else {
            return null;
        }
    }

    public function save($controlSalud) {
        
        if (isset($controlSalud)) {
            $sql = "UPDATE `control_salud` SET `edad` = :edad,
                        `fecha` = :fecha,
                        `peso` = :peso,
                        `vacunas_completas` = :vacunasCompletas,
                        `maduracion_acorde` = :maduracionAcorde,
                        `maduracion_observaciones` = :maduracionObservaciones,
                        `ex_fisico_normal` = :exFisicoNormal,
                        `ex_fisico_observaciones` = :exFisicoObservaciones,
                        `pc` = :pc,
                        `ppc` = :ppc,
                        `talla` = :talla,
                        `alimentacion` = :alimentacion,
                        `observaciones_generales` = :observacionesGenerales,
                        `paciente_id` = :pacienteId,
                        `user_id` = :userId
                        WHERE id = :id";


            $qr = $this->db->prepare($sql);
            
            $id = $controlSalud->getIdControlSalud();
            $edad = $controlSalud->getEdad();
            $fecha = $controlSalud->getFecha();
            $fec = date("Y-m-d H:i:s", strtotime($fecha));
            $peso =$controlSalud->getPeso();
            $vacunasCompletas =$controlSalud->getVacunasCompletas();
            $maduracionAcorde = $controlSalud->getMaduracionAcorde();
            $maduracionObservaciones = $controlSalud->getMaduracionObservaciones();
            $exFisicoNormal = $controlSalud->getExFisicoNormal();
            $exFisicoObservaciones = $controlSalud->getExFisicoObservaciones();
            $pc = $controlSalud->getPc();
            $ppc = $controlSalud->getPpc();
            $talla = $controlSalud->getTalla();
            $alimentacion = $controlSalud->getAlimentacion();
            $observacionesGenerales = $controlSalud->getObservacionesGenerales();
            $pacienteId = $controlSalud->getPaciente();
            $userId = $controlSalud->getUser();

            $qr->bindParam(":id", $id, PDO::PARAM_INT);
            $qr->bindParam(":edad", $edad, PDO::PARAM_INT);
            $qr->bindParam(":fecha" , $fec, PDO::PARAM_STR);
            $qr->bindParam(":peso" , $peso , PDO::PARAM_INT);
            $qr->bindParam(":vacunasCompletas", $vacunasCompletas, PDO::PARAM_INT);
            $qr->bindParam(":maduracionAcorde", $maduracionAcorde, PDO::PARAM_INT);
            $qr->bindParam(":maduracionObservaciones",$maduracionObservaciones, PDO::PARAM_STR);
            $qr->bindParam(":exFisicoNormal", $exFisicoNormal, PDO::PARAM_INT);
            $qr->bindParam(":exFisicoObservaciones", $exFisicoObservaciones, PDO::PARAM_STR);
            $qr->bindParam(":pc", $pc, PDO::PARAM_INT);
            $qr->bindParam(":ppc", $ppc, PDO::PARAM_INT);
            $qr->bindParam(":talla", $talla, PDO::PARAM_INT);
            $qr->bindParam(":alimentacion", $alimentacion, PDO::PARAM_STR);
            $qr->bindParam(":observacionesGenerales", $observacionesGenerales, PDO::PARAM_STR);
            $qr->bindParam(":pacienteId", $pacienteId, PDO::PARAM_INT);
            $qr->bindParam(":userId", $userId, PDO::PARAM_INT);
            $qr->execute();
            return true;
            }
         else{    
            return false;
        }
    }

    public function delete($id) {

        if (isset($id)) {
            $sql = "UPDATE control_salud SET borrado = 1 WHERE id = :id";
            $qr = $this->db->prepare($sql);
            $qr->bindParam(":id", $id, PDO::PARAM_INT);
            $qr->execute();
            return true;
        }
        else{
            return false;
        }
    }


    public function add($controlSalud) {
        if (isset($controlSalud)) {

            $sql = "INSERT INTO `control_salud` (`edad`,`fecha`,`peso`, `vacunas_completas`, `maduracion_acorde`, `maduracion_observaciones`, `ex_fisico_normal`, `ex_fisico_observaciones`, `pc`, `ppc`, `talla`, `alimentacion`, `observaciones_generales`, `paciente_id`, `user_id`)
                    VALUES (:edad, :fecha, :peso, :vacunasCompletas, :maduracionAcorde, :maduracionObservaciones,:exFisicoNormal, :exFisicoObservaciones, :pc, :ppc, :talla, :alimentacion, :observacionesGenerales, :pacienteId, :userId )";

            $qr = $this->db->prepare($sql);
            
            $edad = $controlSalud->getEdad();
            $fecha = $controlSalud->getFecha();
            $fec = date("Y-m-d H:i:s", strtotime($fecha));
            $peso =$controlSalud->getPeso();
            $vacunasCompletas =$controlSalud->getVacunasCompletas();
            $maduracionAcorde = $controlSalud->getMaduracionAcorde();
            $maduracionObservaciones = $controlSalud->getMaduracionObservaciones();
            $exFisicoNormal = $controlSalud->getExFisicoNormal();
            $exFisicoObservaciones = $controlSalud->getExFisicoObservaciones();
            $pc = $controlSalud->getPc();
            $ppc = $controlSalud->getPpc();
            $talla = $controlSalud->getTalla();
            $alimentacion = $controlSalud->getAlimentacion();
            $observacionesGenerales = $controlSalud->getObservacionesGenerales();
            $pacienteId = $controlSalud->getPaciente();
            $userId = $controlSalud->getUser();

            $qr->bindParam(":edad", $edad, PDO::PARAM_INT);
            $qr->bindParam(":fecha" , $fec, PDO::PARAM_STR);
            $qr->bindParam(":peso" , $peso , PDO::PARAM_INT);
            $qr->bindParam(":vacunasCompletas", $vacunasCompletas, PDO::PARAM_INT);
            $qr->bindParam(":maduracionAcorde", $maduracionAcorde, PDO::PARAM_INT);
            $qr->bindParam(":maduracionObservaciones",$maduracionObservaciones, PDO::PARAM_STR);
            $qr->bindParam(":exFisicoNormal", $exFisicoNormal, PDO::PARAM_INT);
            $qr->bindParam(":exFisicoObservaciones", $exFisicoObservaciones, PDO::PARAM_STR);
            $qr->bindParam(":pc", $pc, PDO::PARAM_INT);
            $qr->bindParam(":ppc", $ppc, PDO::PARAM_INT);
            $qr->bindParam(":talla", $talla, PDO::PARAM_INT);
            $qr->bindParam(":alimentacion", $alimentacion, PDO::PARAM_STR);
            $qr->bindParam(":observacionesGenerales", $observacionesGenerales, PDO::PARAM_STR);
            $qr->bindParam(":pacienteId", $pacienteId, PDO::PARAM_INT);
            $qr->bindParam(":userId", $userId, PDO::PARAM_INT);
            $qr->execute();
            return true;
        }
        else{
            return false;
        }
    }
     public function cantidadControlSalud($id){
      $sql="SELECT COUNT(*)
            FROM control_salud 
            WHERE borrado = 0 and paciente_id =:id";
      $qr=$this->db->prepare($sql);
      $qr->bindParam(":id", $id, PDO::PARAM_INT);
      $qr->execute();
      $numero=$qr->fetch(PDO::FETCH_ASSOC);
      return $numero['COUNT(*)'];       
    }
    public function valoresTalla($id){
      if (isset($id) and ($id != "")) {
        $sql = "SELECT fecha , talla 
                FROM control_salud
                WHERE paciente_id = :id and borrado = 0";
        $qr=$this->db->prepare($sql);
        $qr->bindParam(":id", $id, PDO::PARAM_INT);
        $qr->execute();
        $arreglo = array();
        while ($cs =$qr->fetch(PDO::FETCH_ASSOC)) {
                $arreglo['fecha'][] = $cs['fecha'];
                $arreglo['array'][] = (int)$cs['talla'];
        }
        return $arreglo;        
      }
      else{
        return null;
      }
    }
    public function valoresPC($id){
     if (isset($id) and ($id != "")) {
        $sql = "SELECT fecha , pc 
                FROM control_salud
                WHERE paciente_id = :id and borrado = 0";
        $qr=$this->db->prepare($sql);
        $qr->bindParam(":id", $id, PDO::PARAM_INT);
        $qr->execute();
        $arreglo = array();
        while ($cs =$qr->fetch(PDO::FETCH_ASSOC)) {
                $arreglo['fecha'][] = $cs['fecha'];
                $arreglo['array'][] = (int)$cs['pc'];
        }
        return $arreglo;        
      }
      else{
        return null;
      } 
    }
    public function valoresPPC($id){
     if (isset($id) and ($id != "")) {
        $sql = "SELECT fecha , ppc 
                FROM control_salud
                WHERE paciente_id = :id and borrado = 0";
        $qr=$this->db->prepare($sql);
        $qr->bindParam(":id", $id, PDO::PARAM_INT);
        $qr->execute();
        $arreglo = array();
        while ($cs =$qr->fetch(PDO::FETCH_ASSOC)) {
                $arreglo['fecha'][] = $cs['fecha'];
                $arreglo['array'][] = (int)$cs['ppc'];
        }
        return $arreglo;        
      }
      else{
        return null;
      } 
    }
    public function valoresPeso($id){
     if (isset($id) and ($id != "")) {
        $sql = "SELECT fecha , peso 
                FROM control_salud
                WHERE paciente_id = :id and borrado = 0";
        $qr=$this->db->prepare($sql);
        $qr->bindParam(":id", $id, PDO::PARAM_INT);
        $qr->execute();
        $arreglo = array();
        while ($cs =$qr->fetch(PDO::FETCH_ASSOC)) {
                $arreglo['fecha'][] = $cs['fecha'];
                $arreglo['array'][] = (int)$cs['peso'];
        }
        return $arreglo;        
      }
      else{
        return null;
      } 
    }
    public function valoresEstatura($id){
     if (isset($id) and ($id != "")) {
        $sql = "SELECT fecha , estatura 
                FROM control_salud
                WHERE paciente_id = :id and borrado = 0";
        $qr=$this->db->prepare($sql);
        $qr->bindParam(":id", $id, PDO::PARAM_INT);
        $qr->execute();
        $arreglo = array();
        while ($cs =$qr->fetch(PDO::FETCH_ASSOC)) {
                $arreglo['fecha'][] = $cs['fecha'];
                $arreglo['array'][] = (int)$cs['estatura'];
        }
        return $arreglo;        
      }
      else{
        return null;
      } 
    }
    public function valoresCrecimiento($id){
     if (isset($id) and ($id != "")) {
        $sql = "SELECT fecha , crecimiento 
                FROM control_salud
                WHERE paciente_id = :id and borrado = 0";
        $qr=$this->db->prepare($sql);
        $qr->bindParam(":id", $id, PDO::PARAM_INT);
        $qr->execute();
        $arreglo = array();
        while ($cs =$qr->fetch(PDO::FETCH_ASSOC)) {
                $arreglo['fecha'][] = $cs['fecha'];
                $arreglo['array'][] = (int)$cs['crecimiento'];
        }
        return $arreglo;        
      }
      else{
        return null;
      } 
    }
    public function allSinPag($id){
      $sql="SELECT *
            FROM control_salud 
            WHERE borrado = 0 and paciente_id = :id";
      $qr=$this->db->prepare($sql);
      $qr->bindParam(":id", $id, PDO::PARAM_INT);
      $qr->execute();
      $result = array();
    
      while ($c = $qr->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new ControlSalud($c);
      }
      return $result;
  }

}