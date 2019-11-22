<?php

include_once directorio . "lib/resource/DatosDemograficosResource.php";
include_once directorio . "lib/resource/TwigUtility.php";
include_once directorio . "lib/resource/SitioResource.php";
include_once directorio . "lib/resource/PacienteResource.php";
include_once directorio . "models/DatosDemograficos.php";
include_once directorio . "lib/resource/TipoCalefaccionResource.php";
include_once directorio . "lib/resource/TipoViviendaResource.php";
include_once directorio . "lib/resource/TipoAguaResource.php";
include_once directorio . "lib/resource/RolResource.php";


    class DatosDemograficosController extends Controllers{
   
   public function __construct($params = array()) {

        
    }
    public function index($params) {

        //FIXME: Implement pagination
        $paciente = array();

        $params["paciente"] = DatosDemograficosResource::getInstance()->all();

        parent::index($params);
    }
    public function add($params) {
        $arreglo = array();
        $sitio = SitioResource::getinstance()->get();
        $arreglo ['titulo'] = $sitio->getTitulo();
        $arreglo ['mail'] = $sitio->getMail();
        $nombre = $_SESSION['rolActual'];
        $arreglo['misRoles'] = RolResource::getInstance()->getVarios($_SESSION['id']);
        $arreglo['paciente'] = $params['paciente'];
        $arreglo['pacienteId'] = $params['pacienteId'];  
        $arreglo ['viviendas'] = TipoViviendaResource::getInstance()->allSinPag();
        $arreglo ['aguas'] = TipoAguaResource::getInstance()->allSinPag();
        $arreglo ['calefacciones'] = TipoCalefaccionResource::getInstance()->allSinPag();
        $arreglo ['misRoles'] = RolResource::getInstance()->getVarios($_SESSION['id']);
        if (!is_null($params)) {
          $arreglo[] = $params;
        }
        echo TwigUtility::getInstance()->render("altaDatosDemo.html", $arreglo);
    }

    public function onAdd($params) {

         if (isset($params)) {
               if(isset($_POST['heladera'])){
                  $datos['heladera'] = $_POST['heladera'];
               }
               if(isset($_POST["electricidad"])){
                  $datos['electricidad'] = $_POST['electricidad'];
               }
               if(isset($_POST["mascotas"])){
                  $datos['mascotas'] = $_POST['mascotas'];
               }
               if(isset($_POST["tipo_vivienda_id"])){
                  $datos['tipo_vivienda_id'] = (int)$_POST['tipo_vivienda_id'];
               }
               if(isset($_POST["tipo_calefaccion_id"])){
                  $datos['tipo_calefaccion_id'] = (int)$_POST['tipo_calefaccion_id'];               
               }
               if(isset($_POST["tipo_agua_id"])){
                  $datos['tipo_agua_id'] = (int)$_POST['tipo_agua_id'];  
               }
               $datoDemografico = new DatosDemograficos($datos);
               $datoDemografico->setHeladera($datos['heladera']);
               $datoDemografico->setElectricidad($datos["electricidad"]);
               $datoDemografico->setMascotas($datos["mascotas"]);
               $datoDemografico->setTipoVivienda($datos['tipo_vivienda_id']);
               $datoDemografico->setTipoCalefaccion($datos['tipo_calefaccion_id']);
               $datoDemografico->setTipoAgua($datos['tipo_agua_id']);

               $paciente = PacienteResource::getInstance()->get($_POST['pacienteId']);

               if (is_null(DatosDemograficosResource::getInstance()->getIdDatos($datoDemografico))){
                   if (!DatosDemograficosResource::getinstance()->add($datoDemografico)) {
                        $this->add($params);
                   }
               }

                  $idDatosDemo = DatosDemograficosResource::getInstance()->getIdDatos($datoDemografico);
                  $paciente->setDatosDemograficos($idDatosDemo);
                  if (PacienteResource::getInstance()->save($paciente)) {
                    require_once (directorio . '/controller/Paciente.php');
                    $controller = new PacienteController();
                    $controller->listar();
                    }  
             }
             else{
              $this->add($params);
             }  
    }  


    public function edit($params) {
            $sitio= SitioResource::getInstance()->get();
            $arreglo ['titulo'] = $sitio->getTitulo();
            $arreglo ['mail'] = $sitio->getMail();
            $arreglo ['misRoles'] = RolResource::getInstance()->getVarios($_SESSION['id']);
            $datoDemografico = DatosDemograficosResource::getInstance()->get($_GET['id']);
            if (isset($params['heladera'])) {
              $arreglo[] = $params; 
            }
            else{
              if (isset($datoDemografico)) {
                $arreglo ['viviendas'] = TipoViviendaResource::getInstance()->allSinPag();
                $arreglo ['aguas'] = TipoAguaResource::getInstance()->allSinPag();
                $arreglo ['calefacciones'] = TipoCalefaccionResource::getInstance()->allSinPag();
                $arreglo ['datoDemografico'] = $datoDemografico;   
             }
            }
            echo TwigUtility::getInstance()->render("modificacionDatosDemo.html", $arreglo);
    }    


    public function onEdit($params) {
        if (isset($params)) {
            $datosDemograficos = DatosDemograficosResource::getInstance()->get($_GET['id']);
            if (isset($datosDemograficos)) {
                if (isset($params['heladera'])) {
                    $datosDemograficos->setHeladera($params['heladera']);
                }

                if (isset($params['electricidad'])) {
                    $datosDemograficos->setElectricidad($params['electricidad']);
                }               

                if (isset($params['mascotas'])) {
                    $datosDemograficos->setMascotas($params['mascotas']);
                }

                if (isset($params['tipo_vivienda_id']) ) {
                    $datosDemograficos->setTipovivienda($params['tipo_vivienda_id']);
                }
                if (isset($params['tipo_calefaccion_id']) ) {
                    $datosDemograficos->setTipoCalefaccion($params['tipo_calefaccion_id']);
                }

                if (isset($params['tipo_agua_id'])) {
                    $datosDemograficos->setTipoAgua($params['tipo_agua_id']);
                }
                if (DatosDemograficosResource::getInstance()->save($datosDemograficos)) {
                    
                    $this->listar();
                } 
                else {
                    $this->edit($params);
                }
            }
            else {
                $this->edit($params);
            }
    }
    else{
        $this->edit($params);
    }
  }

  public function listar($inicio=0){
        $sitio = SitioResource::getInstance()->get();
        $resultados['titulo'] = $sitio->getTitulo();
        $resultados['mail']  = $sitio->getMail();
        $cantidadDatosDemograficos = DatosDemograficosResource::getInstance()->cantidadDatosDemograficos();
        $cantidadDePaginas=$cantidadDatosDemograficos/$sitio->getCantidadElementos();
        if(($cantidadDatosDemograficos%$sitio->getCantidadElementos())>0){
                $cantidadDePaginas=intval($cantidadDePaginas);
                $cantidadDePaginas=$cantidadDePaginas +1;
        }
        if (!isset($_GET['inicio'])) {
            $listado = DatosDemograficosResource::getInstance()->all(0, $sitio->getCantidadElementos());
            $listadoP = PacienteResource::getInstance()->all(0, $sitio->getCantidadElementos());    
        } 
        else{
            $listado = DatosDemograficosResource::getInstance()->all($_GET['inicio'], $sitio->getCantidadElementos());
            $listadoP = PacienteResource::getInstance()->all($_GET['inicio'], $sitio->getCantidadElementos());  
        }
        
        if(isset($listado)){
            $resultados['resultados'] = $listado;
            $resultados['pacientes'] = $listadoP;
            $resultados['cantidadBotones'] = $cantidadDePaginas;
            $resultados['cantidad'] = $sitio->getCantidadElementos();
            $resultados ['viviendas'] = TipoViviendaResource::getInstance()->allSinPag();
            $resultados ['aguas'] = TipoAguaResource::getInstance()->allSinPag();
            $resultados ['calefacciones'] = TipoCalefaccionResource::getInstance()->allSinPag();

            $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
            $resultados['misRoles'] = $roles;

            $resultados['controlador'] = 'DatosDemograficos';
            $resultados['metodo'] = '   ';
            foreach ($roles as $rol) {
                if ($rol->getNombre() == $_SESSION['rolActual']){
                    $idRol = $rol->getIdRol();
                }
            }
            $resultados['permisos'] = RolResource::getInstance()->listarPermisos($idRol);
            echo TwigUtility::getInstance()->render("crudDatosDemo.html",$resultados);

        }
    }


    public function delete() {
      $datosDemograficos = DatosDemograficosResource::getInstance()->get($_GET['id']);
      $sitio = SitioResource::getInstance()->get();
      $resultados['titulo'] = $sitio->getTitulo();
      $resultados['mail']  = $sitio->getMail();
      $resultados['misRoles'] = RolResource::getInstance()->getVarios($_SESSION['id']);
      if (isset($datosDemograficos)) {   
          if (DatosDemograficosResource::getInstance()->delete($datosDemograficos->getIdDatosDemograficos())) {
              $this->listar(0);
          }
          else{
            echo TwigUtility::getInstance()->render("cartelDatosDemo.html",$resultados);                
          }
      }
      else {
          //error on delete
      }
    }
   public function graficoTorta(){
    $arreglo = array();
    $sitio = SitioResource::getInstance()->get();
    $arreglo['titulo'] = $sitio->getTitulo();
    $arreglo['mail']  = $sitio->getMail();
    $arreglo['misRoles'] = RolResource::getInstance()->getVarios($_SESSION['id']);
    $a = DatosDemograficosResource::getInstance()->heladera();
    $arreglo['hTiene'] = $a['hTiene'] ;
    $arreglo['hNoTiene'] = $a['hNoTiene'];
    $b = DatosDemograficosResource::getInstance()->electricidad();
    $arreglo['eNoTiene'] = $b['eNoTiene'];
    $arreglo['eTiene'] = $b['eTiene'];
    $c = DatosDemograficosResource::getInstance()->mascotas();
    $arreglo['mNoTiene'] = $c['mNoTiene'];
    $arreglo['mTiene'] = $c['mTiene'];
    $d = DatosDemograficosResource::getInstance()->tipoAgua();
    $e = DatosDemograficosResource::getInstance()->tipoCalefaccion();
    $f = DatosDemograficosResource::getInstance()->tipoVivienda();
    foreach ($f as $ff) {
      if ($ff->getNombre() == 'Chapa') {
        $arreglo['Chapa'] = $ff->getCantidad();
      }
      if ($ff->getNombre() == 'Madera') {
        $arreglo['Madera'] = $ff->getCantidad();
      }
      if ($ff->getNombre() == 'Material') {
        $arreglo['Material'] = $ff->getCantidad();
      }
    }
    $arreglo['array'] = "";
    $cantidadAgua = TipoAguaResource::getInstance()->allSinPag();
    $cantidadAgua = sizeof($cantidadAgua);
    $can =1;
    foreach ($d as $dd) {
      if($can < $cantidadAgua){
        $arreglo['array'] = $arreglo['array'] . "{name:'" . $dd->getNombre() . "' , y:" . $dd->getCantidad() . "},";
        $can = $can + 1;
      }
      else{
        $arreglo['array'] = $arreglo['array'] . "{name:'" . $dd->getNombre() . "' , y:" . $dd->getCantidad() . "}";
      }
    }
    
    $tipoCalefaccion = TipoCalefaccionResource::getInstance()->allSinPag();
    $arreglo['tipoCalefaccion'] = $tipoCalefaccion;
    foreach ($e as $ee) {
      $arreglo['cantidadCalefaccion'][] = $ee->getCantidad();
    }
    $cantidadCalefaccion = sizeof($tipoCalefaccion);
    $arreglo['cantidad'] = $cantidadCalefaccion;
  
    echo TwigUtility::getInstance()->render("vistaGraficoTortaDatosDemo.html",$arreglo);
   }
//cierre de la clase
}
