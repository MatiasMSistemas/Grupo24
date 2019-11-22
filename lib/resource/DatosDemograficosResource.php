<?php

 include_once directorio . "models/DatosDemograficos.php";
 include_once directorio . "models/ResultadoAgua.php";
 include_once directorio . "models/ResultadoCalefaccion.php";
 include_once directorio . "models/ResultadoVivienda.php";
 include_once directorio . "lib/resource/PacienteResource.php";
 include_once directorio . "lib/resource/TipoAguaResource.php";
 include_once directorio . "lib/resource/TipoCalefaccionResource.php";
 include_once directorio . "lib/resource/TipoViviendaResource.php";
 include_once directorio . "models/Db.php";

class DatosDemograficosResource {

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
            self::$instance = new DatosDemograficosResource();
        }
        return self::$instance;
    }

    public function all($ini , $pageSize ) {
       if (isset($ini) && isset($pageSize)) {
         $sql = "SELECT d.id,
                       d.heladera,
                       d.electricidad,
                       d.mascota,
                       d.tipo_vivienda_id,
                       d.tipo_calefaccion_id,
                       d.tipo_agua_id
                FROM datos_demograficos AS d
                WHERE d.borrado = 0
                LIMIT :init, :size";
         $qr = $this->db->prepare($sql);
         $ini = (int)$ini;
         $pageSize = (int)$pageSize;
         $qr->bindParam(":init", $ini, PDO::PARAM_INT);
         $qr->bindParam(":size", $pageSize, PDO::PARAM_INT);
         $qr->execute();
         $result = array();
         while ($dd = $qr->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new DatosDemograficos($dd);
         }
         return $result; 
       }
        
    }

    public function get($id) {
        if (isset($id)) {
          $sql = "SELECT d.id,
                       d.heladera,
                       d.electricidad,
                       d.mascota,
                       d.tipo_vivienda_id,
                       d.tipo_calefaccion_id,
                       d.tipo_agua_id
                FROM datos_demograficos AS d
                WHERE d.id = :id and d.borrado = 0";
          $qr = $this->db->prepare($sql);
          $id = (int)$id;
          $qr->bindParam(":id", $id, PDO::PARAM_INT);
          $qr->execute();
          $row = $qr->fetch(PDO::FETCH_ASSOC);      
          if (!empty($row)) {
             $datosDemograficos = new DatosDemograficos($row);
             return $datosDemograficos;
          }
          else {
            return null;
          }
        }
        return null;
    }


    public function getIdDatos($datosDemograficos) {
        if (isset($datosDemograficos)) {
          $sql = "SELECT id
                  FROM datos_demograficos 
                  WHERE heladera = :heladera and electricidad = :electricidad and mascota = :mascotas and tipo_vivienda_id = :tipo_vivienda_id 
                  and tipo_calefaccion_id = :tipo_calefaccion_id and tipo_agua_id = :tipo_agua_id and borrado = 0";
          $qr = $this->db->prepare($sql);
          $heladera = (int)$datosDemograficos->getHeladera();
          $electricidad = (int)$datosDemograficos->getElectricidad();
          $mascotas = (int)$datosDemograficos->getMascotas();
          $tipoVivienda = (int)$datosDemograficos->getTipoVivienda();
          $tipoCalefaccion = (int)$datosDemograficos->getTipoCalefaccion();
          $tipoAgua = (int)$datosDemograficos->getTipoAgua();
          $qr->bindParam(":heladera", $heladera, PDO::PARAM_INT);
          $qr->bindParam(":electricidad", $electricidad, PDO::PARAM_INT);
          $qr->bindParam(":mascotas", $mascotas, PDO::PARAM_INT);
          $qr->bindParam(":tipo_vivienda_id", $tipoVivienda, PDO::PARAM_INT);
          $qr->bindParam(":tipo_calefaccion_id", $tipoCalefaccion, PDO::PARAM_INT);
          $qr->bindParam(":tipo_agua_id", $tipoAgua, PDO::PARAM_INT);
          $qr->execute();
          $row = $qr->fetch(PDO::FETCH_ASSOC);        
          if (!empty($row)) {
             $id = $row['id'];
             return $id;
          }
          else {
            return null;
          }
        }
        return null;
    }

    public function save($datosDemograficos) {

        
        if (isset($datosDemograficos)) {
            $sql = "UPDATE datos_demograficos SET heladera = :heladera,
                        electricidad = :electricidad,
                        mascota = :mascotas,
                        tipo_vivienda_id = :tipoVivienda,
                        tipo_calefaccion_id = :tipoCalefaccion,
                        tipo_agua_id = :tipoAgua
                        WHERE id = :id";

            $qr = $this->db->prepare($sql);
            
            $id = $datosDemograficos->getIdDatosDemograficos();
            $heladera = $datosDemograficos->getHeladera();
            $electricidad = $datosDemograficos->getElectricidad();
            $mascotas =$datosDemograficos->getMascotas();
            $tipoVivienda =$datosDemograficos->getTipoVivienda();
            $tipoCalefaccion = $datosDemograficos->getTipoCalefaccion();
            $tipoAgua = $datosDemograficos->getTipoAgua();
            
            $qr->bindParam(":id", $id, PDO::PARAM_INT);
            $qr->bindParam(":heladera", $heladera, PDO::PARAM_STR);
            $qr->bindParam(":electricidad" , $electricidad , PDO::PARAM_STR);
            $qr->bindParam(":mascotas" , $mascotas , PDO::PARAM_INT);
            $qr->bindParam(":tipoVivienda", $tipoVivienda, PDO::PARAM_INT);
            $qr->bindParam(":tipoCalefaccion", $tipoCalefaccion, PDO::PARAM_INT);
            $qr->bindParam(":tipoAgua", $tipoAgua, PDO::PARAM_INT);
            $qr->execute();
            return true;
            }
         else{    
            return false;
        }
    }

    public function delete($id) {

            if (isset($id)) {
              $sql = "UPDATE datos_demograficos SET borrado = 1 WHERE id = :id";
              $qr = $this->db->prepare($sql);
              $qr->bindParam(":id", $id, PDO::PARAM_INT);
              $qr->execute();
              return true;
            }
            else{
              return false;
            }
    }

    public function add($datosDemograficos) {
        
        if (isset($datosDemograficos)) {

            $sql = "INSERT INTO `datos_demograficos` (heladera, electricidad, mascota, tipo_vivienda_id, tipo_calefaccion_id, tipo_agua_id) VALUES 
                   (:heladera, :electricidad, :mascotas, :tipoVivienda, :tipoCalefaccion, :tipoAgua)";

            $qr = $this->db->prepare($sql);
            
            $heladera = $datosDemograficos->getHeladera();
            $electricidad = $datosDemograficos->getElectricidad();
            $mascotas =$datosDemograficos->getMascotas();
            $tipoVivienda =$datosDemograficos->getTipoVivienda();
            $tipoCalefaccion = $datosDemograficos->getTipoCalefaccion();
            $tipoAgua = $datosDemograficos->getTipoAgua();
            
            $qr->bindParam(":heladera", $heladera, PDO::PARAM_STR);
            $qr->bindParam(":electricidad" , $electricidad , PDO::PARAM_STR);
            $qr->bindParam(":mascotas" , $mascotas , PDO::PARAM_INT);
            $qr->bindParam(":tipoVivienda", $tipoVivienda, PDO::PARAM_INT);
            $qr->bindParam(":tipoCalefaccion", $tipoCalefaccion, PDO::PARAM_INT);
            $qr->bindParam(":tipoAgua", $tipoAgua, PDO::PARAM_INT);
            $qr->execute();
            return true;
        }
        else{
          return false;
        }
    }
     public function heladera(){
       $sql = "SELECT heladera
               FROM datos_demograficos
               WHERE borrado = 0";
       $qr=$this->db->prepare($sql);
       $qr->execute();
       $arreglo = array();
       $arreglo['hTiene'] = 0;
       $arreglo['hNoTiene'] = 0;
       while ($h = $qr->fetch(PDO::FETCH_ASSOC)) {
            if ($h['heladera'] == 0) {
              $arreglo['hNoTiene'] = $arreglo['hNoTiene'] + 1;
            }
            else{
              $arreglo['hTiene'] = $arreglo['hTiene'] + 1;
            }
       }
       return $arreglo;       
     }
     public function electricidad(){
       $sql = "SELECT electricidad
               FROM datos_demograficos
               WHERE borrado = 0";
       $qr=$this->db->prepare($sql);
       $qr->execute();
       $arreglo = array();
       $arreglo['eTiene'] = 0;
       $arreglo['eNoTiene'] = 0;
       while ($e = $qr->fetch(PDO::FETCH_ASSOC)) {
            if ($e['electricidad'] == 0) {
              $arreglo['eNoTiene'] = $arreglo['eNoTiene'] + 1;
            }
            else{
              $arreglo['eTiene'] = $arreglo['eTiene'] + 1;
            }
       }
       return $arreglo;       
     }
     public function mascotas(){
       $sql = "SELECT mascota
               FROM datos_demograficos
               WHERE borrado = 0";
       $qr=$this->db->prepare($sql);
       $qr->execute();
       $arreglo = array();
       $arreglo['mTiene'] = 0;
       $arreglo['mNoTiene'] = 0;
       while ($m = $qr->fetch(PDO::FETCH_ASSOC)) {
            if ($m['mascota'] == 0) {
              $arreglo['mNoTiene'] = $arreglo['mNoTiene'] + 1;
            }
            else{
              $arreglo['mTiene'] = $arreglo['mTiene'] + 1;
            }
       }
       return $arreglo;       
     }
     public function tipoAgua(){
      $sql = "SELECT tipo_agua_id
              FROM datos_demograficos
              WHERE borrado = 0";
      $qr=$this->db->prepare($sql);
      $qr->execute();
      $arreglo = array();
      $aux = array();
      $tipoAguas= TipoAguaResource::getInstance()->allSinPag();
      foreach ($tipoAguas as $ta) {
                $aux['nombre'] = $ta->getNombre();
                $aux['cantidad'] = 0;
                $arreglo[] = new ResultadoAgua($aux);
              }     
       while ($tas = $qr->fetch(PDO::FETCH_ASSOC)) { 
             $ta = TipoAguaResource::getInstance()->get($tas['tipo_agua_id']);
             foreach ($arreglo as $a) {
               if ($a->getNombre() == $ta->getNombre()) {
                   $valor = $a->getCantidad();
                   $valor = $valor + 1;
                   $a->setCantidad($valor);
               }
             }
       }
       $cantidadDatosDemograficos = $this->cantidadDatosDemograficos();
       foreach ($arreglo as $a) {
                   $valor = $a->getCantidad();
                   $valor = $valor *100/$cantidadDatosDemograficos;
                   $a->setCantidad($valor);
             }
       return $arreglo;               
     }
      public function tipoCalefaccion(){
      $sql = "SELECT tipo_calefaccion_id
              FROM datos_demograficos
              WHERE borrado = 0";
      $qr=$this->db->prepare($sql);
      $qr->execute();
      $arreglo = array();
      $aux = array();
      $tipoCalefacciones= TipoCalefaccionResource::getInstance()->allSinPag();
      foreach ($tipoCalefacciones as $tc) {
                $aux['nombre'] = $tc->getNombre();
                $aux['cantidad'] = 0;
                $arreglo[] = new ResultadoCalefaccion($aux);
              }
       while ($tcs = $qr->fetch(PDO::FETCH_ASSOC)) {
             $tc = TipoCalefaccionResource::getInstance()->get($tcs['tipo_calefaccion_id']);
             foreach ($arreglo as $a) {
               if ($a->getNombre() == $tc->getNombre()) {
                   $valor = $a->getCantidad();
                   $valor = $valor + 1;
                   $a->setCantidad($valor);
               }
             }
       }
       $cantidadDatosDemograficos = $this->cantidadDatosDemograficos();
       foreach ($arreglo as $a) {
                   $valor = $a->getCantidad();
                   $valor = $valor *100/$cantidadDatosDemograficos;
                   $a->setCantidad($valor);
             }
       return $arreglo;               
     }
      public function tipoVivienda(){
      $sql = "SELECT tipo_vivienda_id
              FROM datos_demograficos
              WHERE borrado = 0";
      $qr=$this->db->prepare($sql);
      $qr->execute();
      $arreglo = array();
      $aux = array();
      $tipoViviendas= TipoViviendaResource::getInstance()->allSinPag();
      foreach ($tipoViviendas as $tv) {
                $aux['nombre'] = $tv->getNombre();
                $aux['cantidad'] = 0;
                $arreglo[] = new ResultadoVivienda($aux);
              }
       while ($tas = $qr->fetch(PDO::FETCH_ASSOC)) {
             $tv = TipoViviendaResource::getInstance()->get($tas['tipo_vivienda_id']);
             foreach ($arreglo as $a) {
               if ($a->getNombre() == $tv->getNombre()) {
                   $valor = $a->getCantidad();
                   $valor = $valor + 1;
                   $a->setCantidad($valor);
               }
             }
       }
       $cantidadDatosDemograficos = $this->cantidadDatosDemograficos();
       foreach ($arreglo as $a) {
                   $valor = $a->getCantidad();
                   $valor = $valor *100/$cantidadDatosDemograficos;
                   $a->setCantidad($valor);
             }
       return $arreglo;               
     }
     public function cantidadDatosDemograficos(){
      $sql="SELECT COUNT(*)
            FROM datos_demograficos
            WHERE borrado = 0";
      $qr=$this->db->prepare($sql);
      $qr->execute();
      $numero=$qr->fetch(PDO::FETCH_ASSOC);
      return $numero['COUNT(*)'];       
    }
    public function allSinPag(){
      $sql="SELECT *
            FROM datos_demograficos 
            WHERE borrado = 0";
      $qr=$this->db->prepare($sql);
      $qr->execute();
      $result = array();
    
      while ($d = $qr->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new DatosDemograficos($d);
      }
      return $result;
  }
}