<?php

include_once directorio . "lib/resource/PacienteResource.php";
include_once directorio . "lib/resource/TwigUtility.php";
include_once directorio . "lib/resource/SitioResource.php";
include_once directorio . "models/Paciente.php";
include_once directorio . "lib/resource/RolResource.php";
include_once directorio . "lib/resource/TipoDocumentoResource.php";
include_once directorio . "lib/resource/TipoDeObraSocialResource.php";
include_once directorio . "controller/DatosDemograficos.php";


 class PacienteController extends Controllers{
   
    public function __construct($params = array()) {

       
    }
    public function index($params) {

        //FIXME: Implement pagination
        $paciente = array();

        $params["paciente"] = PacienteResource::getInstance()->all();

        parent::index($params);
    }
    public function add($params) {
        $arreglo = array();
        $sitio = SitioResource::getInstance()->get();
        $titulo = $sitio->getTitulo();
        $mail = $sitio->getMail();
        $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
        $nombre = $_SESSION['rolActual'];
        $arreglo['titulo'] = $titulo;
        $arreglo['mail'] = $mail;
        $arreglo['misRoles'] = $roles;
        $arreglo['rolActual'] = $nombre; 
        $arreglo['TipoDocumentos'] = TipoDocumentoResource::getInstance()->allSinPag();
        $arreglo['TiposDeObraSocial'] = TipoDeObraSocialResource::getInstance()->allSinPag();
        if (!is_null($params)) {
            $arreglo[] = $params;
        }
        echo TwigUtility::getInstance()->render("altaPaciente.html", $arreglo);
    }


    public function onAdd($params) {
        if(isset($_POST)) {
             $datos = array();
             $datos['apellido'] = $_POST['apellido'];
             $datos['nombre'] = $_POST['nombre'];
             $datos['domicilio'] = $_POST['domicilio'];
             $datos['tel'] = $_POST['tel'];
             $datos['fecha_nac'] = $_POST['fecha_nac'];
             $datos['genero'] = $_POST['genero'];
             $datos['obra_social_id'] = $_POST['obra_social_id'];
             $datos['tipo_doc_id'] = $_POST['tipo_doc_id'];
             $datos['numero'] = $_POST['numero'];
             $datos['datos_demograficos_id'] = 0;
             $paciente = new Paciente($datos);
             if (PacienteResource::getInstance()->add($paciente)) {
                $pacienteId = PacienteResource::getInstance()->getByParametros($paciente); 
                $arreglo['pacienteId'] = $pacienteId;
                $arreglo['paciente'] = $paciente->getNombre();
                $DatosDemograficos = new DatosDemograficosController(); 
                $DatosDemograficos->add($arreglo); 
            }
             else {
                $this->add($datos);
             } 
        }
    }


     public function edit($params) {
        $arreglo = array();
        $sitio = SitioResource::getInstance()->get();
        $titulo = $sitio->getTitulo();
        $mail = $sitio->getMail();
        $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
        $arreglo['titulo'] = $titulo;
        $arreglo ['mail'] = $mail;
        $arreglo['misRoles'] = $roles;
        $arreglo['TipoDocumentos'] = TipoDocumentoResource::getInstance()->allSinPag();
        $arreglo['TiposDeObraSocial'] = TipoDeObraSocialResource::getInstance()->allSinPag();
        if (isset($params['paciente'])) {
            $arreglo[] =$params ;        
        }
        else {
            $paciente = PacienteResource::getInstance()->get($_GET['id']);
            if (!is_null($paciente)) {
                $arreglo['paciente'] = $paciente;
            }
        }
        echo TwigUtility::getInstance()->render("modificacionPaciente.html", $arreglo);
    }

    public function onEdit($params) {
        
        if (isset($params)) {
            $paciente = PacienteResource::getInstance()->get($_GET['id']);

            if (!is_null($paciente)){
                if (isset($params['apellido'])){
                    $paciente->setApellido($params['apellido']);
                }
                if (isset($params['nombre'])){
                    $paciente->setNombre($params['nombre']);
                }               
                if (isset($params['domicilio'])) {
                    $paciente->setDomicilio($params['domicilio']);
                }
                if (isset($params['tel'])) {
                    $paciente->setTel($params['tel']);
                }
                if (isset($params['fecha_nac'])) {
                    $paciente->setFechaNac($params['fecha_nac']);
                }
                if (isset($params['genero'])) {
                    $paciente->setGenero($params['genero']);
                }

                if (isset($params['datos_demograficos_id'])) {
                    $paciente->setDatosDemograficos($params['datos_demograficos_id']);
                }
                if (isset($params['obra_social_id'])) {
                    $paciente->setObrasocial($params['obra_social_id']);
                }

                if (isset($params['tipo_doc_id'])) {
                    $paciente->setTipoDocumento($params['tipo_doc_id']);
                }
                if (isset($params['numero'])) {
                    $paciente->setNumero($params['numero']);
                }

                if (PacienteResource::getInstance()->save($paciente)) {
                        $this->listar();
                }
                else{
                    $this->edit($params);
                }
            }
            else{
                $this->edit($params);
            }
        }
        else {
            
            }
        }

    public function listar(){
        $sitio = SitioResource::getInstance()->get();
        $titulo = $sitio->getTitulo();
        $mail = $sitio->getMail();
        $cantidadPacientes = PacienteResource::getInstance()->cantidadPacientes();
        $cantidadDePaginas = $cantidadPacientes / $sitio->getCantidadElementos();
        if(($cantidadPacientes % $sitio->getCantidadElementos())>0){
            $cantidadDePaginas=intval($cantidadDePaginas);
            $cantidadDePaginas=$cantidadDePaginas +1;
        }
        if (isset($_GET['inicio'])) {
            $listado = PacienteResource::getInstance()->all($_GET['inicio'], $sitio->getCantidadElementos());
        }
        else{
            $listado = PacienteResource::getInstance()->all(0, $sitio->getCantidadElementos());
        }
        if(isset($listado)){
            $resultados['resultados'] = $listado;
            $resultados['cantidadBotones'] = $cantidadDePaginas;
            $resultados['cantidad'] = $sitio->getCantidadElementos();
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
            $resultados['controlador'] = 'Paciente';
            $resultados['metodo'] = 'listar';
            $resultados['rolActual'] = $_SESSION['rolActual']; 
            $resultados['obrasSociales'] = TipoDeObraSocialResource::getInstance()->allSinPag();
            $resultados['tipoDocumentos'] = TipoDocumentoResource::getInstance()->allSinPag();
            echo TwigUtility::getInstance()->render("crudPacientes.html",$resultados);
        }
    }

    public function delete(){
            $paciente = PacienteResource::getInstance()->get($_GET['id']);
            if (! is_null($paciente)) {
                if (PacienteResource::getInstance()->delete($paciente->getIdPaciente())) {
                   $this->listar(0);
                }
                else{
                   $this->listar(0);
                }
            }

            
    }
   public function listarParaListar(){
      $arreglo = array();
      $sitio = SitioResource::getInstance()->get();
      $arreglo['titulo'] = $sitio->getTitulo();
      $arreglo['mail'] = $sitio->getMail();
      $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
            foreach ($roles as $rol) {
                if ($rol->getNombre() == $_SESSION['rolActual']){
                    $idRol = $rol->getIdRol();
                }
            }
      $arreglo['misRoles'] = $roles;
      $arreglo['permisos'] = RolResource::getInstance()->listarPermisos($idRol);
      $arreglo['controlador'] = 'Paciente';
      $arreglo['metodo'] = 'listarParaListar';
      $cantidadPacientes = PacienteResource::getInstance()->cantidadPacientes();
      $cantidadDePaginas = $cantidadPacientes / $sitio->getCantidadElementos();
      if(($cantidadPacientes % $sitio->getCantidadElementos())>0){
            $cantidadDePaginas=intval($cantidadDePaginas);
            $cantidadDePaginas=$cantidadDePaginas +1;
        }
      if (isset($_GET['inicio'])) {
            $listado = PacienteResource::getInstance()->all($_GET['inicio'], $sitio->getCantidadElementos());
      }
      else{
            $listado = PacienteResource::getInstance()->all(0, $sitio->getCantidadElementos());
      }
      if (isset($listado)) {
          $arreglo['resultados'] = $listado;
          $arreglo['cantidad'] = $sitio->getCantidadElementos();
          $arreglo['cantidadBotones'] = $cantidadDePaginas;
          echo TwigUtility::getInstance()->render("listadoParaControlSalud.html",$arreglo);

      }
   } 
   public function listarParaAgregar(){
      $arreglo = array();
      $sitio = SitioResource::getInstance()->get();
      $arreglo['titulo'] = $sitio->getTitulo();
      $arreglo['mail'] = $sitio->getMail();
      $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
            foreach ($roles as $rol) {
                if ($rol->getNombre() == $_SESSION['rolActual']){
                    $idRol = $rol->getIdRol();
                }
            }
      $arreglo['misRoles'] = $roles;
      $arreglo['permisos'] = RolResource::getInstance()->listarPermisos($idRol);
      $arreglo['controlador'] = 'Paciente';
      $arreglo['metodo'] = 'listarParaAgregar';
      $cantidadPacientes = PacienteResource::getInstance()->cantidadPacientes();
      $cantidadDePaginas = $cantidadPacientes / $sitio->getCantidadElementos();
      if(($cantidadPacientes % $sitio->getCantidadElementos())>0){
            $cantidadDePaginas=intval($cantidadDePaginas);
            $cantidadDePaginas=$cantidadDePaginas +1;
        }
      if (isset($_GET['inicio'])) {
            $listado = PacienteResource::getInstance()->all($_GET['inicio'], $sitio->getCantidadElementos());
      }
      else{
            $listado = PacienteResource::getInstance()->all(0, $sitio->getCantidadElementos());
      }
      if (isset($listado)) {
          $arreglo['resultados'] = $listado;
          $arreglo['cantidad'] = $sitio->getCantidadElementos();
          $arreglo['cantidadBotones'] = $cantidadDePaginas;
          echo TwigUtility::getInstance()->render("listadoParaControlSalud.html",$arreglo);

      }
   }
   public function filtrar(){
     $arreglo = array();
     $arreglo['TiposDocumento'] = TipoDocumentoResource::getInstance()->allSinPag();
     $sitio = SitioResource::getInstance()->get();
     $titulo = $sitio->getTitulo();
     $mail = $sitio->getMail(); 
     $arreglo['titulo'] = $titulo;
     $arreglo['mail'] = $mail;
     $arreglo['misRoles'] = RolResource::getInstance()->getVarios($_SESSION['id']);
     $arreglo['rolActual'] = $_SESSION['rolActual'];
     echo TwigUtility::getInstance()->render("filtrarPacientes.html",$arreglo);
   } 
   public function onFiltrar(){
        $sitio = SitioResource::getInstance()->get();
        $cantidadPacientes = PacienteResource::getInstance()->cantidadPacientes();
        $cantidadDePaginas = $cantidadPacientes / $sitio->getCantidadElementos();
        if(($cantidadPacientes % $sitio->getCantidadElementos())>0){
            $cantidadDePaginas=intval($cantidadDePaginas);
            $cantidadDePaginas=$cantidadDePaginas +1;
        }
        if (isset($_POST['numero']) and ($_POST['numero'] != '')) {
            $num=$_POST['numero'];
        }
        else{
            $num = null;
        }
        if (isset($_POST['apellido']) and ($_POST['apellido']) != '') {
            $ape=$_POST['apellido'];
        }
        else{
            $ape = null;
        }
        if (isset($_POST['nombre']) and ($_POST['nombre'] != '')) {
            $nom=$_POST['nombre'];
        }
        else{
            $nom = null;
        }
        if (isset($_POST['tipoDocumento'])) {
            $td=$_POST['tipoDocumento'];
        }
        else{
            $td = null;
        }
        $listado = PacienteResource::getInstance()->getAntn($ape,$nom , $td,$num, $_GET['inicio'], $sitio->getCantidadElementos());
        if(isset($listado)){
            $resultados['resultados'] = $listado;
            $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
            $titulo = $sitio->getTitulo();
            $mail = $sitio->getMail(); 
            $resultados['cantidadBotones'] = $cantidadDePaginas;
            $resultados['cantidad'] = $sitio->getCantidadElementos();
            $resultados['titulo'] = $titulo;
            $resultados['mail'] = $mail;
            foreach ($roles as $rol) {
                if ($rol->getNombre() == $_SESSION['rolActual']){
                    $idRol = $rol->getIdRol();
                }
            }
            $resultados['misRoles']=$roles;
            $resultados['permisos'] = RolResource::getInstance()->listarPermisos($idRol);
            $resultados['controlador'] = 'Paciente';
            $resultados['metodo'] = 'filtrar'; 
            echo TwigUtility::getInstance()->render("crudPacientes.html",$resultados);
        }
   }


}
