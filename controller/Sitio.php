<?php
	
 include_once directorio . "lib/resource/SitioResource.php";
 include_once directorio . "lib/resource/TwigUtility.php";
 include_once directorio . "lib/resource/RolResource.php";

    class SitioController extends Controllers{
   
   public function __construct($params = array()) {
   }
    public function index($params) {

        //FIXME: Implement pagination
        $sitio = array();

        $params["sitio"] = SitioResource::getInstance()->all();

        parent::index($params);
    }
    
    
     public function edit() {
           $arrglo = array();
           $sitio = SitioResource::getInstance()->get();
           $titulo= $sitio->getTitulo();
           $mail = $sitio->getMail();
           $arreglo['sitio'] = $sitio;
           $arreglo['titulo'] = $titulo;
           $arreglo['mail'] = $mail;
           $arreglo['misRoles'] = RolResource::getInstance()->getVarios($_SESSION['id']);
           if (isset($sitio)) {
               echo TwigUtility::getInstance()->render("cambiarConfiguracion.html", $arreglo);
           } 
           else {
            //Error, invalid user, user not found.... i don't know
          }

    }

    public function onEdit($params) {
        if (isset($params)) {
           $arrglo = array();
           $sitio = SitioResource::getInstance()->get();
           $titulo= $sitio->getTitulo();
           $mail = $sitio->getMail();
           $arreglo['sitio'] = $sitio;
           $arreglo['titulo'] = $titulo;
           $arreglo['mail'] = $mail;
           $arreglo['misRoles'] = RolResource::getInstance()->getVarios($_SESSION['id']);

            if (isset($sitio)) {

                if (isset($params['titulo'])) {
                    $sitio->setTitulo($params['titulo']);
                }

                if (isset($params['descripcion'])) {
                    $sitio->setDescripcion($params['descripcion']);
                }
                if (isset($params['mail'])) {
                    $sitio->setMail($params['mail']);
                }
                if (isset($params['cantidadElementos']) ) {
                    $sitio->setCantidadElementos($params['cantidadElementos']);
                }                
                if (SitioResource::getInstance()->save($sitio)) {
                  $titulo= $sitio->getTitulo();
                  $mail = $sitio->getMail();
                  $arreglo['titulo'] = $titulo;
                  $arreglo['mail'] = $mail;

                  $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
                  $arreglo['misRoles'] = $roles;
                  foreach ($roles as $r) {
                    if($r->getNombre() == $_SESSION['rolActual']){
                        $arreglo['permisos'] = RolResource::getInstance()->listarPermisos($r->getIdRol());
                    }
                  }
                  echo TwigUtility::getInstance()->render($_SESSION['rolActual'] . ".html", $arreglo);
                } 
                else {
                    $this->edit();
                }
            }
            else{
              $this->edit();
            }
        }
        else {
            $this->edit();
        }
    }
    public function onChange(){
        if(SitioResource::getInstance()->cambiarEstado()){
            $arrglo = array();
            $sitio = SitioResource::getInstance()->get();
            $titulo= $sitio->getTitulo();
            $mail = $sitio->getMail();
            $arreglo['sitio'] = $sitio;
            $arreglo['titulo'] = $titulo;
            $arreglo['mail'] = $mail;
            $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
            $arreglo['misRoles'] = $roles;
            foreach ($roles as $r) {
              if($r->getNombre() == $_SESSION['rolActual']){
                  $arreglo['permisos'] = RolResource::getInstance()->listarPermisos($r->getIdRol());
              }
            }
            echo TwigUtility::getInstance()->render($_SESSION['rolActual'].".html", $arreglo);
        }
        else{

        }
    }
   public function administrar(){
            $arrglo = array();
            $sitio = SitioResource::getInstance()->get();
            $titulo= $sitio->getTitulo();
            $mail = $sitio->getMail();
            $arreglo['sitio'] = $sitio;
            $arreglo['titulo'] = $titulo;
            $arreglo['mail'] = $mail;
            $arreglo['misRoles'] = RolResource::getInstance()->getVarios($_SESSION['id']);
            echo TwigUtility::getInstance()->render("configSitio.html", $arreglo);
   } 

 }

