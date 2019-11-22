<?php

 include_once directorio . "models/Usuario.php";
 include_once directorio . "models/Db.php";
 include_once directorio . "lib/resource/RolResource.php";

class UsuarioResource {
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
            self::$instance = new UsuarioResource();
        }
        return self::$instance;
    }

    public function all($ini , $pageSize ) {
        if (isset($ini) && isset($pageSize)) {
           $sql = "SELECT u.id,
                        u.email,
                        u.username,
                        u.pass,
                        u.activo,
                        u.updated_at,
                        u.created_at,
                        u.first_name,
                        u.last_name
                FROM usuario AS u
                WHERE u.borrado = 0
                LIMIT :init, :size";
           $qr = $this->db->prepare($sql);
           $ini = (int)$ini;
           $pageSize = (int)$pageSize;
           $qr->bindParam(":init", $ini, PDO::PARAM_INT);
           $qr->bindParam(":size", $pageSize, PDO::PARAM_INT);
           $qr->execute();
           $result = array();
           while ($u = $qr->fetch(PDO::FETCH_ASSOC)) {
                  $u['rol'] = RolResource::getInstance()->get($u['id']);
                  $result[] = new Usuario($u);
           }
           return $result;  
        }
    }
    public function cambiarRoles($id,$roles){
      if (isset($id) and isset($roles)) {
        $ru = RolResource::getInstance()->allSinPag();
        foreach ($ru as $r) {
          if (isset($roles[$r->getNombre()])) {
            $this->darRol($id,$r->getIdRol());
          }
          else{
            $this->quitarRol($id,$r->getIdRol());
          }
        }
        return true;
        
      }else{
        return false;
      }
      
    }

    public function get($id) {

        if (isset($id)) {
           $sql = "SELECT u.id,
                        u.email,
                        u.username,
                        u.pass,
                        u.activo,
                        u.updated_at,
                        u.created_at,
                        u.first_name,
                        u.last_name
                FROM usuario AS u
                WHERE u.id = :id and u.borrado = 0";

          $qr = $this->db->prepare($sql);
          $qr->bindParam(":id", $id, PDO::PARAM_INT);

          $qr->execute();
          $row = $qr->fetch(PDO::FETCH_ASSOC);           

          if (!empty($row)) {
             $row['rol'] = RolResource::getInstance()->getVarios($row['id']);
             $user = new Usuario($row);
             return $user;
         }
           else {
             return null;
          }
        }
        else{
          return null;
        }
    }
    public function filtrado($nombreU,$activo,$nombre,$apellido,$fechaDeCreacion,$fechaModificacion,$desendente,$ini , $pageSize){
        $sql = "SELECT id,
                       email,
                       username,
                       pass,
                       activo,
                       updated_at,
                       created_at,
                       first_name,
                       last_name
                FROM usuario 
                WHERE borrado = 0";
        if(!is_null($nombreU)){
            $sql= $sql . " and username LIKE :userName";
        }
        if (!is_null($activo)) {
            $sql= $sql . " and activo = :activo";   
        }
        if (!is_null($nombre)) {
            $sql= $sql . " and first_name LIKE :nombre";   
       }
       if (!is_null($apellido)) {
            $sql= $sql . " and last_name LIKE :apellido"; 
       }
       if (!is_null($fechaDeCreacion)) {
           $sql= $sql . " ORDER BY created_at";
       }
       else{
        if (!is_null($fechaModificacion)) {
           $sql= $sql . " ORDER BY updated_at"; 
        }
       }
       if (!is_null($fechaModificacion) && !is_null($fechaDeCreacion)) {
           $sql= $sql . " ,updated_at";
       }
       if (!is_null($desendente)) {
           $sql= $sql . " DESC"; 
       }
       if(isset($ini) && isset($pageSize)){
        $sql= $sql. " LIMIT :init , :size";
       }
       $qr = $this->db->prepare($sql);
       if (!is_null($nombreU)) {
         $nombreU = '%' . $nombreU . '%';
         $qr->bindParam(":userName", $nombreU, PDO::PARAM_STR);
       }
       if (!is_null($activo)) {
         $qr->bindParam(":activo", $activo, PDO::PARAM_INT);
       }
       if (!is_null($nombre)) {
         $nombre= '%' .$nombre . '%';
         $qr->bindParam(":nombre", $nombre, PDO::PARAM_STR); 
       }
       if (!is_null($apellido)) {
         $apellido = '%' . $apellido .'%';
         $qr->bindParam(":apellido", $apellido, PDO::PARAM_STR); 
       }
       if (isset($ini) && isset($pageSize)) {
          $ini = (int)$ini;
          $pageSize = (int)$pageSize;
          $qr->bindParam(":init", $ini, PDO::PARAM_INT);
          $qr->bindParam(":size", $pageSize, PDO::PARAM_INT);
       }
       $qr->execute();
       $result = array();
       while ($u = $qr->fetch(PDO::FETCH_ASSOC)) {
            $u['rol'] = RolResource::getInstance()->get($u['id']);
            $result[] = new Usuario($u);
       }
        return $result;
    }

    public function save($user) {

        
        if (isset($user)) {
            $sql = "UPDATE usuario SET email = :email, 
                                    userName = :userName, 
                                    pass = :password,
                                    activo = :activo,
                                    updated_at = :updatedAt,
                                    created_at = :createdAt,
                                    first_name = :firstName,
                                    last_name = :lastName
                                WHERE id = :id";

            $qr = $this->db->prepare($sql);
            
            $id = $user->getIdUsuario();
            $email = $user->getEmail();
            $userName = $user->getUserName();
            $password =$user->getPassword();
            $activo =$user->getActivo();
            $updatedAt = $user->getUpdatedAt();
            $createdAt = $user->getCreatedAt();
            $firstName = $user->getFirstName();
            $lastName = $user->getLastName();
            $fechaModificacion =date("Y-m-d H:i:s", strtotime($updatedAt));
            $fechaDeCreacion = date("Y-m-d H:i:s", strtotime($createdAt)); 
            
            $qr->bindParam(":id", $id, PDO::PARAM_INT);
            $qr->bindParam(":email", $email, PDO::PARAM_STR);
            $qr->bindParam(":userName" , $userName , PDO::PARAM_STR);
            $qr->bindParam(":password" , $password , PDO::PARAM_STR);
            $qr->bindParam(":activo", $activo, PDO::PARAM_INT);
            $qr->bindParam(":updatedAt", $fechaModificacion, PDO::PARAM_STR);
            $qr->bindParam(":createdAt", $fechaDeCreacion, PDO::PARAM_STR);
            $qr->bindParam(":firstName", $firstName, PDO::PARAM_STR);
            $qr->bindParam(":lastName", $lastName, PDO::PARAM_STR);
            $qr->execute();
            return true;
            }
         else{    
            return false;
        }
    }

    public function delete($id) {

        if (isset($id)){
            $sql ="SELECT rol_id FROM usuario_tiene_rol WHERE usuario_id = :idUsuario";
            $qr = $this->db->prepare($sql);
            $qr->bindParam(":idUsuario", $id, PDO::PARAM_INT);
            $qr->execute();
            while ($r = $qr->fetch(PDO::FETCH_ASSOC)) {
               $this->quitarRol($id,$r['rol_id']);
            }
            $sql = "UPDATE usuario SET borrado = 1 WHERE id = :id";
            $qr = $this->db->prepare($sql);
            $qr->bindParam(":id", $id, PDO::PARAM_INT);
            $qr->execute();
            return true;
        }
        else{
            return false;
        }
    }
     public function cambiarEstado($id) {

        if (isset($id)) {
            $sql = "SELECT activo FROM usuario WHERE id = :id";
            $qr = $this->db->prepare($sql);
            $qr->bindParam(":id", $id, PDO::PARAM_INT);
            $qr->execute();
            $activo =  $qr->fetch(PDO::FETCH_ASSOC);
            if ($activo['activo'] == 0) {
               $sql = "UPDATE usuario SET activo = 1 WHERE id = :id";
               $qr = $this->db->prepare($sql);
               $qr->bindParam(":id", $id, PDO::PARAM_INT);
               $qr->execute();   
              }
              else{
                $sql = "UPDATE usuario SET activo = 0 WHERE id = :id";
                $qr = $this->db->prepare($sql);
                $qr->bindParam(":id", $id, PDO::PARAM_INT);
                $qr->execute();
              }  
            return true;
        }
        else{
            return false;
        }

    }

    public function add($user) {        
        if (isset($user)) {
            $sql = "INSERT INTO `usuario` (email, userName, pass, activo, updated_at, created_at, first_name, last_name) VALUES 
                   (:email, :userName, :password, :activo, :updatedAt, :createdAt, :firstName, :lastName)";

            $qr = $this->db->prepare($sql);
            
            $email = $user->getEmail();
            $userName = $user->getUserName();
            $password =$user->getPassword();
            $activo =$user->getActivo();
            $updatedAt = $user->getUpdatedAt();
            $createdAt = $user->getCreatedAt();
            $firstName = $user->getFirstName();
            $lastName = $user->getLastName();
            $fechaModificacion =date("Y-m-d H:i:s", strtotime($updatedAt));
            $fechaDeCreacion = date("Y-m-d H:i:s", strtotime($createdAt));
            
            $qr->bindParam(":email", $email, PDO::PARAM_STR);
            $qr->bindParam(":userName" , $userName , PDO::PARAM_STR);
            $qr->bindParam(":password" , $password , PDO::PARAM_STR);
            $qr->bindParam(":activo", $activo, PDO::PARAM_INT);
            $qr->bindParam(":updatedAt",$fechaModificacion , PDO::PARAM_STR);
            $qr->bindParam(":createdAt", $fechaDeCreacion, PDO::PARAM_STR);
            $qr->bindParam(":firstName", $firstName, PDO::PARAM_STR);
            $qr->bindParam(":lastName", $lastName, PDO::PARAM_STR);
            $qr->execute();
            return true;   
        }
        else{
            return false;
        }
    }

    public function darRol($idUsuario ,$idRol) {
          if (isset($idUsuario) && isset($idRol)) {
            $sql = "SELECT borrado FROM usuario_tiene_rol WHERE usuario_id = :idUsuario and rol_id = :idRol";
            $qr = $this->db->prepare($sql);
            $qr->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT);
            $qr->bindParam(":idRol", $idRol, PDO::PARAM_INT);
            $qr->execute();
            $ur=$qr->fetch(PDO::FETCH_ASSOC);
            if($ur == false){
              $sql = "INSERT INTO `usuario_tiene_rol` (usuario_id, rol_id) VALUES 
                   (:idUsuario, :idRol)";
              $qr = $this->db->prepare($sql);
              $qr->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT); 
              $qr->bindParam(":idRol", $idRol, PDO::PARAM_INT);
              $qr->execute();
            }
            else{
             if($ur['borrado'] == 1){
                $sql="UPDATE usuario_tiene_rol SET borrado = 0 WHERE usuario_id = :idUsuario and rol_id = :idRol";
                $qr = $this->db->prepare($sql);
                $qr->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT);
                $qr->bindParam(":idRol", $idRol, PDO::PARAM_INT);
                $qr->execute();
             }     
            }
            
            return true;
        }
        else{
            return false;
        }

    }
    public function quitarRol($idUsuario ,$idRol){
       if (isset($idUsuario) && isset($idRol)) {
            $sql = "SELECT borrado FROM usuario_tiene_rol WHERE usuario_id = :idUsuario and rol_id = :idRol";
            $qr = $this->db->prepare($sql);
            $qr->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT);
            $qr->bindParam(":idRol", $idRol, PDO::PARAM_INT);
            $qr->execute();
            $ur=$qr->fetch(PDO::FETCH_ASSOC);
            if($ur == false){
              $sql = "INSERT INTO `usuario_tiene_rol` (usuario_id, rol_id) VALUES 
                   (:idUsuario, :idRol)";
              $qr = $this->db->prepare($sql);
              $qr->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT); 
              $qr->bindParam(":idRol", $idRol, PDO::PARAM_INT);
              $qr->execute();
            }
            else {
             if($ur['borrado'] == 0){
                $sql="UPDATE usuario_tiene_rol SET borrado = 1 WHERE usuario_id = :idUsuario and rol_id = :idRol";
                $qr = $this->db->prepare($sql);
                $qr->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT);
                $qr->bindParam(":idRol", $idRol, PDO::PARAM_INT);
                $qr->execute();
             }     
            }
            return true;
        }
        else{
            return false;
        } 

    }
    public function cantidadUsuarios(){
      $sql="SELECT COUNT(*)
            FROM usuario 
            WHERE borrado = 0";
      $qr=$this->db->prepare($sql);
      $qr->execute();
      $numero =$qr->fetch(PDO::FETCH_ASSOC);
      return $numero['COUNT(*)'];       
    }
    public function allSinPag(){
      $sql="SELECT *
            FROM usuario 
            WHERE borrado = 0";
      $qr=$this->db->prepare($sql);
      $qr->execute();
      $result = array();
    
      while ($u = $qr->fetch(PDO::FETCH_ASSOC)) {
            $u['rol'] = RolResource::getInstance()->getVarios($u['id']);
            $result[] = new Usuario($u);
      }
      return $result;
    }
}