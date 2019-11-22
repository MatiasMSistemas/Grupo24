<?php

include_once directorio . "lib/resource/PermisoResource.php";
include_once directorio . "lib/resource/TwigUtility.php";
include_once directorio . "lib/resource/SitioResource.php";
include_once directorio . "models/Permiso.php";

class PermisoController extends Controllers{
   
   public function __construct($params = array()) {

    }
    public function index($params) {

        //FIXME: Implement pagination
        $permiso = array();

        $params["permiso"] = PermisoResource::getInstance()->all();

        parent::index($params);
    }
    public function add($params) {

        echo TwigUtility::getInstance()->render("altaPermiso.html", $params);
    }

    public function onAdd($params) {
         
         if (isset($params)) {
            $permiso = new Permiso($params);
            if(PermisoResource::getinstance()->add($permiso)){

            }
            else{
                $this.add($params);
            }
         }
         else {
             
         }
    }          

    public function edit($params) {

        if (isset($params)) {
          $permiso = PermisoResource::getInstance()->get($params['id']);    
          if (!is_null($permiso)) {
             echo TwigUtility::getInstance()->render("modificacionPermiso.html", $permiso);
          }
          else{

          }
        }        
        else {
            //Error, invalid permiso, permiso not found
        }

    }

    public function onEdit($params) {

        if (isset($params)) {
            $permiso = PermisoResource::getInstance()->get($params['id']);
            if (!is_null($permiso)) {
                if (isset($params['nombre'])) {
                    $permiso->setNombre($params['nombre']);
                } 
                if (PermisoResource::getInstance()->save($permiso)) {
                } 
                else{
                    $this->edit($params);
                }
            }
            else {
            }
        }
        else{

        }
    }

    public function listar($inicio=0){
            $sitio = SitioResource::getInstance()->get();
            $cantidadPermisos = PermisoResource::getInstance()->cantidadPermisos();
            $cantiadDePaginas=$cantidadPermisos/$sitio->getCantidadElementos();
            if(($cantidadPermisos%$sitio->getCantidadElementos())>0){
                $cantidadDePaginas=intval($cantidadDePaginas);
                $cantidadDePaginas=$cantidadDePaginas +1;
            }
            $listado = PermisoResource::getInstance()->all($inico, $sitio->getCantidadElementos());
            if(isset($listado)){

            }
    }

    public function delete($params) {

        if (isset($params)) {
            $permiso = PermisoResource::getInstance()->get($params['id']);
            if (PermisoResource::getInstance()->delete($user)) {
                $this->listar(0);
            }
            else{

            }
        }
        else {
            //error on delete
        }
    }
}