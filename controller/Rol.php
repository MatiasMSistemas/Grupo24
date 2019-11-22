<?php
	
 include_once directorio . "lib/resource/RolResource.php";
 include_once directorio . "lib/resource/TwigUtility.php";
 include_once directorio . "lib/resource/SitioResource.php";
 include_once directorio . "models/Rol.php";

class RolController extends Controllers{
   
   public function __construct($params = array()) {

    }
    public function index($params) {

        //FIXME: Implement pagination
        $rol = array();

        $params["rol"] = RolResource::getInstance()->all();

        parent::index($params);
    }
    public function add($params) {
        echo TwigUtility::getInstance()->render("altaRol.html", $params);
    }

    public function onAdd($params) {
            if(isset($params)){
              $rol = new Rol($params);
              if (!is_null($rol)) {
                if(isset($params["nombre"])){
                  $this->setNombre($params["nombre"]);
                }
                if(RolResource::getinstance()->add($rol)){
                  echo TwigUtility::getInstance()->render("index.html");
                }
                else {
                  $this->add($params);
                }
              }
              else{

              }
            }
            else{

            }
    }
    public function edit($params) {
        if (isset($params)) {
          $rol = RolResource::getInstance()->get($params['id']);     
          if (!is_null($rol)) {
             echo TwigUtility::getInstance()->render("modificacionRol.html", $rol);
          }
          else{

          }
        }
        else {
        
        }
    }
    public function onEdit($params) {

        if (isset($params)) {
            $rol = RolResource::getInstance()->get($params['idRol']);
            if (!is_null($rol)) {
                if (isset($params['nombre'])) {
                    $rol->setNombre($params['nombre']);
                }
                if (RolResource::getInstance()->save($rol)) {
                    
                } 
                else {
                    $this->edit($params);
                }
            } 
        }
        else {
            
        }
    }
   public function listar($inicio=0){
            $sitio = SitioResource::getInstance()->get();
            $cantidadRoles = RolResource::getInstance()->cantidadRoles();
            $cantiadDePaginas = $cantidadRoles / $sitio->getCantidadElementos();
            if(($cantidadRoles / $sitio->getCantidadElementos())>0){
                $cantidadDePaginas = intval($cantidadDePaginas);
                $cantidadDePaginas = $cantidadDePaginas + 1;
            }
            $listado = RolResource::getInstance()->all($inico, $sitio-this>getCantidadElementos());
            if(isset($listado)){

            }
    } 
}

