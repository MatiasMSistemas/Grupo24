<?php
	
 include_once directorio . "lib/resource/UsuarioResource.php";
 include_once directorio . "lib/resource/LogInResource.php";
 include_once directorio . "lib/resource/TwigUtility.php";
 include_once directorio . "lib/resource/RolResource.php";
 include_once directorio . "lib/resource/SitioResource.php";
 include_once directorio . "models/Rol.php";

class LogInController extends Controllers{
   
   public function __construct() {

    }

    public function logIn() {
        $sitio = SitioResource::getinstance()->get();
        $datosSitio ['titulo'] = $sitio->getTitulo();
        $datosSitio ['mail'] = $sitio->getMail();
        echo TwigUtility::getInstance()->render("login.html", $datosSitio);
    
    }

    public function onLogIn($params) {
        $sitio = SitioResource::getinstance()->get();
        $arreglo ['titulo'] = $sitio->getTitulo();
        $arreglo ['mail'] = $sitio->getMail();
        if (!isset($_SESSION['id'])) {        
            $userName = $params["userName"];
            $pass = $params["password"];
            $user = LogInUtility::getInstance()->login($userName,$pass);

            if ($user) {
                $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
                $cant = 0;
                foreach ($roles as $r) {

                        $nombre = $r->getNombre();
                        $permisos = RolResource::getInstance()->listarPermisos($r->getIdRol());
                        if (isset($nombre) and $cant == 0){
                            $cant = 1;
                            $arreglo['permisos'] = $permisos;
                            $arreglo['misRoles'] = $roles;
                            echo TwigUtility::getInstance()->render( $nombre . ".html" , $arreglo);
                        }   
                }

            }
            else{
                    echo TwigUtility::getInstance()->render("index.html", $arreglo);
            }
        }else{
            echo TwigUtility::getInstance()->render( $_SESSION['rolActual'] . ".html" , $arreglo);
        }

}
        

    public function logOut(){
        $sitio = SitioResource::getinstance()->get();
        $datosSitio ['titulo'] = $sitio->getTitulo();
        $datosSitio ['mail'] = $sitio->getMail();
        $datosSitio['descripcion'] =$sitio->getDescripcion();
        session_destroy();
        echo TwigUtility::getInstance()->render("index.html",$datosSitio);
    }

}

