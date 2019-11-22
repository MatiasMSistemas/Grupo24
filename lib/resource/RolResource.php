<?php
include_once directorio . "models/Rol.php";
include_once directorio . "models/Db.php";
include_once directorio . "lib/resource/PermisoResource.php";
include_once directorio . "lib/resource/UsuarioResource.php";

class RolResource {

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
            self::$instance = new RolResource();
        }
        return self::$instance;
    }

 public function all($ini , $pageSize ) {
        if (isset($ini) && isset($pageSize)) {
          $sql = "SELECT r.id,
                       r.nombre
                FROM rol AS r
                WHERE r.borrado = 0
                LIMIT :init, :size";
        
          $qr = $this->db->prepare($sql);
          $ini = (int)$ini;
          $pageSize = (int)$pageSize;
          $qr->bindParam(":init", $ini, PDO::PARAM_INT);
          $qr->bindParam(":size", $pageSize, PDO::PARAM_INT);
          $qr->execute();

          $result = array();
        
          while ($r = $qr->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Rol($r);
          }
          return $result;  
        }
        
    }
  public function get($id) {

        if (isset($id)){
           $sql = "SELECT r.id,
                       r.nombre
                FROM rol AS r
                WHERE r.id = :id and r.borrado = 0";

           $qr = $this->db->prepare($sql);
           $qr->bindParam(":id", $id, PDO::PARAM_INT);
           $qr->execute();         
           $r= $qr->fetch(PDO::FETCH_ASSOC);
           if (!empty($r)) {
              $rol = new Rol($r);
              return $rol;
           } else {
            return null;
           }
        }
    }      
public function getVarios($idUsario){
   if(isset($idUsario)){
    $sql="SELECT rol_id
          FROM usuario_tiene_rol
          WHERE borrado = 0 and usuario_id = :idUsario";
    $qr = $this->db->prepare($sql);
    $qr->bindParam(":idUsario", $idUsario, PDO::PARAM_INT);
    $qr->execute();     
    $result = array();
    while ($r = $qr->fetch(PDO::FETCH_ASSOC)){
          $result[] = $this->get($r['rol_id']);
    }
    return $result;
   }
   else{
    return null;
   }
   
}

public function add($nombre){
   if(isset($nombre)){
     $sql="INSERT INTO `rol` (nombre) VALUES (:nombre)";
     $qr= $this->db->prepare($sql);
     $qr->bindParam(":nombre",$nombre,PDO::PARAM_STR);
     $qr->execute();
     return true;
   }
   else{
    return false;
   }
}

public function delete($id){
    if (isset($id)) {
       $usuario= array();
       $sql ="SELECT usuario_id FROM usuario_tiene_rol WHERE rol_id = :idRol";
       $qr = $this->db->prepare($sql);
       $qr->bindParam(":idRol", $id, PDO::PARAM_INT);
       $qr->execute();
       while ($u = $qr->fetch(PDO::FETCH_ASSOC)) {
         UsuarioResource::getInstance()->quitarRol($u['usuario_id'],$id);
       }
       $permisos =$this->listarPermisos($id);
       foreach ($permisos as $p) {
         $this->quitarPermiso($id,$p->getIdPermiso());
       }
       $sql = "UPDATE rol SET borrado = 1 WHERE id = :id";
       $qr = $this->db->prepare($sql);
       $qr->bindParam(":id", $id, PDO::PARAM_INT);
       $qr->execute();
       return true;
     }
     else{
        return false;
        } 
}

public function save($rol) {

        if (isset($rol)) {
            $sql = "UPDATE rol SET nombre = :nombre WHERE id = :id";
            $qr = $this->db->prepare($sql);
            $id=$rol->getIdRol();
            $nombre=$rol->getNombre();
            $qr->bindParam(":id", $id, PDO::PARAM_INT);
            $qr->bindParam(":nombre", $id, PDO::PARAM_STR);
            $qr->execute();
            return true;
        }
        else{
         return false;
    } 
 }

public function darPermiso($idRol,$idPermiso){
  if (isset($idRol) && isset($idPermiso)) {
            $sql = "SELECT borrado FROM rol_tiene_permiso WHERE rol_id = :idRol and permiso_id = :idPermiso";
            $qr = $this->db->prepare($sql);
            $qr->bindParam(":idRol", $idRol, PDO::PARAM_INT);
            $qr->bindParam(":idPermiso", $idRol, PDO::PARAM_INT);
            $qr->execute();
            $rp=$qr->fetch(PDO::FETCH_ASSOC);
            if(!isset($rp)){
                $sql = "INSERT INTO `rol_tiene_permiso` (rol_id, permiso_id) VALUES 
                   (:idRol, :idPermiso)";
                $qr = $this->db->prepare($sql);
                $qr->bindParam(":idRol", $idRol, PDO::PARAM_INT); 
                $qr->bindParam(":idPermiso", $idPermiso, PDO::PARAM_INT);
                $qr->execute();
                return true;
            }
            else{
               if($rp['borrado'] == 1){
                 $sql = "UPDATE rol_tiene_permiso SET borrado = 0 WHERE rol_id = :idRol";
                 $qr = $this->db->prepare($sql);
                 $qr->bindParam(":idRol", $idRol, PDO::PARAM_INT);
                 $qr->execute();
                 return true;
               }
               else{
                return false;
               }
            }
        }
        else{
            return false;
        }
   }
   
 public function quitarPermiso($idRol,$idPermiso){
   if (isset($idRol) && isset($idPermiso)) {
       $sql = "SELECT borrado FROM rol_tiene_permiso WHERE rol_id = :idRol and permiso_id = :idPermiso ";
       $qr = $this->db->prepare($sql);
       $qr->bindParam(":idRol", $idRol, PDO::PARAM_INT);
       $qr->bindParam(":idPermiso", $idRol, PDO::PARAM_INT);
       $qr->execute();
       $rp=$qr->fetch(PDO::FETCH_ASSOC);
       if (isset($rp)) {
           if($rp['borrado'] == 0){
                 $sql = "UPDATE rol_tiene_permiso SET borrado = 1 WHERE rol_id = :idRol and permiso_id = :idPermiso";
                 $qr = $this->db->prepare($sql);
                 $qr->bindParam(":idRol", $idRol, PDO::PARAM_INT);
                 $qr->bindParam(":idPermiso", $idRol, PDO::PARAM_INT);
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
public function listarPermisos($id){
   if (isset($id)) {
       $sql = "SELECT permiso_id FROM rol_tiene_permiso WHERE rol_id = :idRol ";
       $qr = $this->db->prepare($sql);
       $qr->bindParam(":idRol", $id, PDO::PARAM_INT);
       $qr->execute();
       $result = array();
       while ($p = $qr->fetch(PDO::FETCH_ASSOC)) {
            $result[] = PermisoResource::getInstance()->get($p['permiso_id']);
        }
        return $result;
      }
      else{
        return null;
      }
 }
 public function cantidadRoles(){
      $sql="SELECT COUNT(*)
            FROM rol 
            WHERE borrado = 0";
      $qr=$this->db->prepare($sql);
      $qr->execute();
      $numero=$qr->fetch(PDO::FETCH_ASSOC);
      return $numero['COUNT(*)'];       
    }
  public function allSinPag(){
      $sql="SELECT *
            FROM rol 
            WHERE borrado = 0";
      $qr=$this->db->prepare($sql);
      $qr->execute();
      $result = array();
    
      while ($r = $qr->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Rol($r);
      }
      return $result;
  }  
}