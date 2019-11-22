<?php
	
 include_once directorio . "lib/resource/UsuarioResource.php";
 include_once directorio . "lib/resource/TwigUtility.php";
 include_once directorio . "lib/resource/SitioResource.php";
 include_once directorio . "lib/resource/PermisoResource.php";
 include_once directorio . "lib/resource/RolResource.php";
 include_once directorio . "models/Usuario.php";

class UsuarioController extends Controllers{
   
    
    public function __construct($params = array()){

    }

    public function index($params) {

        //FIXME: Implement pagination
        $users = array();

        $params["users"] = UsuarioResource::getInstance()->all();

        parent::index($params);
    }

    public function add($params) {
        $arreglo = array();
        $sitio = SitioResource::getinstance()->get();
        $arreglo['titulo'] = $sitio->getTitulo();
        $arreglo['mail'] = $sitio->getMail();
        $arreglo['misRoles'] = RolResource::getInstance()->getVarios($_SESSION['id']);
        if (isset($params)) {
            $arreglo[]=$params;
        }
        echo TwigUtility::getInstance()->render("altaUsuario.html", $arreglo);
    }

    public function onAdd() { 
        if(isset($_POST)) {
             $datos = array();
             $datos['email']=$_POST['email'];
             $datos['username']=$_POST['userName'];
             $datos['pass']=$_POST['password'];
             $datos['activo']=$_POST['activo'];
             $datos['first_name']=$_POST['first_name'];
             $datos['last_name']=$_POST['last_name'];
             $datos['created_at'] = date("Y-m-d H:i:s");
             $datos['updated_at'] = date("Y-m-d H:i:s");
             $user = new Usuario($datos);
             if (UsuarioResource::getInstance()->add($user)) {
                $this->listar();
             }
             else {
                $this->add($datos);
             }
        }
    }

    public function edit($params) {
            $arreglo = array();
            $sitio= SitioResource::getInstance()->get();
            $arreglo['titulo'] = $sitio->getTitulo();
            $arreglo ['mail'] = $sitio->getMail();
            $arreglo['misRoles'] = RolResource::getInstance()->getVarios($_SESSION['id']);
            $roles = RolResource::getInstance()->allSinPag();
            $arreglo['rol'] = $roles;
            if (!isset($params)) {
               $arreglo[] = $params;
            }
            else{
                $user = UsuarioResource::getInstance()->get($_GET['id']);
                if (!is_null($user)) {
                    $arreglo['usuario'] = $user;
                }
            }
            echo TwigUtility::getInstance()->render("modificacionUsuario.html", $arreglo);
    }

    public function onEdit($params) {
        
        if (isset($params)){
            $user = UsuarioResource::getInstance()->get($_GET['id']);
            if (!is_null($user)){
                
                if(isset($params['email'])){
                    $user->setEmail($params['email']);
                }
                if (isset($params['password'])){
                    $user->setPassword($params['password']);
                }
                if (isset($params['userName'])) {
                    $user->setUserName($params['userName']);
                }
                if (isset($params['last_name'])) {
                    $user->setLastName($params['last_name']);
                }
                if (isset($params['first_name'])) {
                    $user->setFirstName($params['first_name']);
                }
                if (isset($params['updated_at'])) {
                    $user->setUpdatedAt(date("Y-m-d H:i:s"));
                }
                if (isset($params['activo'])) {
                    $user->setActivo($params['activo']);
                }
                if (UsuarioResource::getInstance()->save($user)) {
                   $this->listar(0);
                } 
                else {
                  $this->edit($params);
                }
            }
        } 
        else {
            
        }
    }

    public function delete() {

        $usuario = UsuarioResource::getInstance()->get($_GET['id']);
            if (!is_null($usuario)) {
                if (UsuarioResource::getInstance()->delete($usuario->getIdUsuario())) {
                   $this->listar(0);
                }
                else{
                   $this->listar(0);
                }
            }
    }

    public function vistaCambiarRoles(){
        if (isset($_GET['id'])) {
            $sitio = SitioResource::getInstance()->get();
            $titulo = $sitio->getTitulo();
            $mail = $sitio->getMail();
            $resultados['usuarioId'] = $_GET['id'];
            $resultados['titulo'] = $titulo;
            $resultados['mail'] = $mail;
            $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
            $resultados['misRoles']=$roles;
            $roles2 = RolResource::getInstance()->getVarios($_GET['id']);
            $resultados['susRoles']=$roles;
            $r = RolResource::getInstance()->allSinPag();
            $resultados['roles'] = $r;
            echo TwigUtility::getInstance()->render("modificarRoles.html",$resultados);
        }


    }

    public function cambiadoDeRoles(){
        if (isset($_GET['id'])) {
            $sitio = SitioResource::getInstance()->get();
            $titulo = $sitio->getTitulo();
            $mail = $sitio->getMail();
            $resultados['usuarioId'] = $_GET['id'];
            $resultados['titulo'] = $titulo;
            $resultados['mail'] = $mail;
            $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
            $resultados['misRoles']=$roles;
            $roles2 = $_POST;
            if (UsuarioResource::getInstance()->cambiarRoles($_GET['id'], $roles2)) {
                $this->listar();
            }
            else{
                $this->listar();
            }
        }

    }


    public function listar(){
            $sitio = SitioResource::getInstance()->get();
            $titulo = $sitio->getTitulo();
            $mail = $sitio->getMail();
            $cantidadUsuarios = UsuarioResource::getInstance()->cantidadUsuarios();
            $cantidadDePaginas=$cantidadUsuarios/$sitio->getCantidadElementos();
            if(($cantidadUsuarios%$sitio->getCantidadElementos())>0){
                $cantidadDePaginas=intval($cantidadDePaginas);
                $cantidadDePaginas=$cantidadDePaginas +1;
            }
            if (!isset($_GET['inicio'])) {
                $listado = UsuarioResource::getInstance()->all(0, $sitio->getCantidadElementos());
            }else{
                $listado = UsuarioResource::getInstance()->all($_GET['inicio'], $sitio->getCantidadElementos());    
            }
            
            if(isset($listado)){
                $resultados['resultados'] = $listado;
                $resultados['cantidadBotones'] = $cantidadDePaginas;
                $resultados['desde'] = $sitio->getCantidadElementos();
                $resultados['titulo'] = $titulo;
                $resultados['mail'] = $mail;
                $resultados['cantidad'] = $sitio->getCantidadElementos();
                $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
                foreach ($roles as $rol) {
                    if ($rol->getNombre() == $_SESSION['rolActual']){
                      $idRol = $rol->getIdRol();
                    }
                }
                $resultados['misRoles']=$roles;
                $resultados['permisos'] = RolResource::getInstance()->listarPermisos($idRol);
                $resultados['controlador'] = 'Usuario';
                $resultados['metodo'] = 'listar'; 
                echo TwigUtility::getInstance()->render("listadoUsuarios.html",$resultados);
            }
    }
    public function cargarInicio(){
        $arreglo = array();
        $sitio = SitioResource::getInstance()->get();
        $titulo = $sitio->getTitulo();
        $mail = $sitio->getMail();
        $arreglo['titulo'] = $titulo;
        $arreglo['mail'] = $mail;
        $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
        foreach ($roles as $rol) {
           if ($rol->getNombre() == $_SESSION['rolActual']){
                    $idRol = $rol->getIdRol();
           }
        }
        $arreglo['misRoles']=$roles;
        $arreglo['permisos'] = RolResource::getInstance()->listarPermisos($idRol);
        echo TwigUtility::getInstance()->render($_SESSION['rolActual'] .".html",$arreglo);
    }
    public function onChangeRolActual(){
         $nuevoActual= $_GET['rol'];
         $ok = false ;
         $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
         foreach ($roles as $rol) {
           if (($rol->getNombre()) == $nuevoActual && (!$ok)){
                    $ok =true ;
           }
         }
         if($ok){
            $_SESSION['rolActual'] = $nuevoActual; 
         }
         $this->cargarInicio();
    }
    public function filtrar(){
        $arreglo=array();
        $sitio= SitioResource::getInstance()->get();
        $arreglo ['titulo'] = $sitio->getTitulo();
        $arreglo ['mail'] = $sitio->getMail();
        $arreglo['misRoles'] = RolResource::getInstance()->getVarios($_SESSION['id']); 
        echo TwigUtility::getInstance()->render("filtrarUsuarios.html", $arreglo); 

    }
    public function onFiltrar(){
        $sitio = SitioResource::getInstance()->get();
        $cantidadUsuarios = UsuarioResource::getInstance()->cantidadUsuarios();
        $cantidadDePaginas = $cantidadUsuarios / $sitio->getCantidadElementos();
        if(($cantidadUsuarios % $sitio->getCantidadElementos())>0){
            $cantidadDePaginas=intval($cantidadDePaginas);
            $cantidadDePaginas=$cantidadDePaginas +1;
        }
        if (isset($_POST['userName']) and $_POST['userName'] != '') {
            $userName=$_POST['userName'];
        }
        else{
            $userName = null;
        }
        if (isset($_POST['lastName']) and $_POST['lastName'] != '') {
            $ape=$_POST['lastName'];
        }
        else{
            $ape = null;
        }
        if (isset($_POST['firstName']) and $_POST['firstName'] != '') {
            $nom=$_POST['firstName'];
        }
        else{
            $nom = null;
        }
        if (isset($_POST['activo']) and $_POST['activo'] != '') {
            $activo=$_POST['activo'];
        }
        else{
            $activo = null;
        }
        if (isset($_POST['fechaDeCreacion'])) {
            $fechaDeCreacion=$_POST['fechaDeCreacion'];
        }
        else{
            $fechaDeCreacion = null;
        }
        if (isset($_POST['fechaModificacion'])) {
            $fechaModificacion=$_POST['fechaModificacion'];
        }
        else{
            $fechaModificacion = null;
        }
        if (isset($_POST['desendente'])) {
            $decendente=$_POST['desendente'];
        }
        else{
            $decendente = null;
        }
        $listado = UsuarioResource::getInstance()->filtrado($userName,$activo,$nom ,$ape, $fechaDeCreacion,$fechaModificacion,$decendente, $_GET['inicio'], $sitio->getCantidadElementos());
        if(isset($listado)){
            $resultados['resultados'] = $listado;
            $titulo = $sitio->getTitulo();
            $mail = $sitio->getMail(); 
            $resultados['cantidadBotones'] = $cantidadDePaginas;
            $resultados['desde'] = $sitio->getCantidadElementos();
            $resultados['titulo'] = $titulo;
            $resultados['mail'] = $mail;
            $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
            foreach ($roles as $rol) {
                if ($rol->getNombre() == $_SESSION['rolActual']){
                    $idRol = $rol->getIdRol();
                }
            }
            $resultados['misRoles']=$roles;
            $resultados['permisos'] = RolResource::getInstance()->listarPermisos($idRol);
            $resultados['controlador'] = 'Usuario';
            $resultados['metodo'] = 'filtrar';
            echo TwigUtility::getInstance()->render("listadoUsuarios.html",$resultados);
        }
    }
}