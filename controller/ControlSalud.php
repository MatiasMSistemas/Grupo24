<?php
    
 include_once directorio . "lib/resource/ControlSaludResource.php";
 include_once directorio . "lib/resource/TwigUtility.php";
 include_once directorio . "lib/resource/SitioResource.php";
 include_once directorio . "lib/resource/RolResource.php";
 include_once directorio . "lib/resource/PacienteResource.php";
 include_once directorio . "models/ControlSalud.php";

    class ControlSaludController extends Controllers{

   public function __construct($params = array()) {

        
    }
    public function index($params) {

        //FIXME: Implement pagination
        $controlSalud = array();

        $params["controlSalud"] = ControlSaludResource::getInstance()->all();

        parent::index($params);
    }
    public function add($params) {
        $sitio = SitioResource::getinstance()->get();
        $arreglo['titulo'] = $sitio->getTitulo();
        $arreglo['mail'] = $sitio->getMail();
        $arreglo['misRoles'] = RolResource::getInstance()->getVarios($_SESSION['id']);
        if(!is_null($params)){
            $arreglo[] = $params;
        }
        if(isset($_GET)){
            $arreglo['paciente'] = PacienteResource::getInstance()->get($_GET['id']);
        }

        echo TwigUtility::getInstance()->render("altaControlSalud.html", $arreglo);
    }

    public function onAdd($params) {

         if(isset($params)) {
            $controlSalud = new ControlSalud($params);
            if(!is_null($controlSalud)){
                if (isset($params['edad']) and ($params['edad'] != "" )) {
                    $controlSalud->setEdad($params['edad']);
                }
                $controlSalud->setFecha(date("Y-m-d H:i:s"));
                if (isset($params['peso']) and $params['peso'] != "" ) {
                    $controlSalud->setPeso($params['peso']);
                }
                if (isset($params['vacunas_completas']) and $params['vacunas_completas'] != "" ) {
                    $controlSalud->setVacunasCompletas($params['vacunas_completas']);
                }
                if (isset($params['maduracion_acorde']) and $params['maduracion_acorde'] != "" ) {
                    $controlSalud->setMaduracionAcorde($params['maduracion_acorde']);
                }
                if(isset($params["maduracion_observaciones"]) and $params['maduracion_observaciones'] != "" ){
                  $controlSalud->setMaduracionObservaciones($params["maduracion_observaciones"]);
                }
                if (isset($params['ex_fisico_normal']) and $params['ex_fisico_normal'] != "" ) {
                    $controlSalud->setExFisicoNormal($params['ex_fisico_normal']);
                }
                if (isset($params['ex_fisico_observaciones']) and $params['ex_fisico_observaciones'] != "" ) {
                    $controlSalud->setExFisicoObservaciones($params['ex_fisico_observaciones']);
                }
                if (isset($params['pc']) and $params['pc'] != "" ) {
                    $controlSalud->setPc($params['pc']);
                }
                if (isset($params['ppc']) and $params['ppc'] != "" ) {
                    $controlSalud->setPpc($params['ppc']);
                }
                if (isset($params['talla']) and $params['talla'] != "" ) {
    
                    $controlSalud->setTalla($params['talla']);
                }    
                if (isset($params['alimentacion']) and $params['alimentacion'] != "" ) {
                    $controlSalud->setAlimentacion($params['alimentacion']);
                }                 
                if (isset($params['observaciones_generales']) and $params['observaciones_generales'] != "" ) {
                    $controlSalud->setObservacionesGenerales($params['observaciones_generales']);
                }
                $controlSalud->setPaciente($_GET['id']);
                $controlSalud->setUser($_SESSION['id']);
                if (ControlSaludResource::getInstance()->add($controlSalud)) {
                    $this->listar();
                }
                else{
                    $this->add($params);
                }  
            }
            else{
             $this->add($params);   
            } 
         } 
         else{
             $this->add($params);
         }
        }
    public function edit($params){
        $sitio = SitioResource::getinstance()->get();
        $arreglo['titulo'] = $sitio->getTitulo();
        $arreglo['mail'] = $sitio->getMail();
        $arreglo['misRoles'] = RolResource::getInstance()->getVarios($_SESSION['id']);
        if(isset($params['pc'])){
            $arreglo[] = $params;
        }
        else{
            $controlSalud = ControlSaludResource::getInstance()->get($_GET['id']);
            if (!is_null($controlSalud)) {
                    $arreglo['control'] = $controlSalud;
                    if (isset($_GET['idp'])) {
                        $p = PacienteResource::getInstance()->get($_GET['idp']);
                    }
                    if (isset($p)) {
                        $arreglo['paciente'] = $p;
                    }
            }
        }

        echo TwigUtility::getInstance()->render("modificacionControlSalud.html", $arreglo);   
    }    

    public function onEdit($params) {
        if(isset($params)) {
            $controlSalud = ControlSaludResource::getInstance()->get($_GET['id']);
            if(!is_null($controlSalud)){
                if (isset($params['edad']) and $params['edad'] != "" ) {
                    $controlSalud->setEdad($params['edad']);
                }
                if (isset($params['fecha']) and $params['fecha'] != "") {
                    $controlSalud->setFecha($params['fecha']);
                }
                if (isset($params['peso']) and $params['peso'] != "") {
                    $controlSalud->setPeso($params['peso']);
                }
                if (isset($params['vacunas_completas']) and $params['vacunas_completas'] != "") {
                    $controlSalud->setVacunasCompletas($params['vacunas_completas']);
                }
                if (isset($params['maduracion_acorde']) and $params['maduracion_acorde'] != "") {
                    $controlSalud->setMaduracionAcorde($params['maduracion_acorde']);
                }
                if(isset($params["maduracion_observaciones"]) and $params['maduracion_observaciones'] != ""){
                  $controlSalud->setMaduracionObservaciones($params["maduracion_observaciones"]);
                }
                if (isset($params['ex_fisico_normal']) and $params['ex_fisico_normal'] != "") {
                    $controlSalud->setExFisicoNormal($params['ex_fisico_normal']);
                }
                if (isset($params['ex_fisico_observaciones']) and $params['ex_fisico_observaciones'] != "") {
                    $controlSalud->setExFisicoObservaciones($params['ex_fisico_observaciones']);
                }
                if (isset($params['pc']) and $params['pc'] != "") {
                    $controlSalud->setPc($params['pc']);
                }
                if (isset($params['ppc']) and $params['ppc'] != "") {
                    $controlSalud->setPpc($params['ppc']);
                }
                if (isset($params['talla']) and $params['talla'] != "") {
                    $controlSalud->setTalla($params['talla']);
                }    
                if (isset($params['alimentacion']) and $params['alimentacion'] != "") {
                    $controlSalud->setAlimentacion($params['alimentacion']);
                }                 
                if (isset($params['observaciones_generales']) and $params['observaciones_generales'] != "") {
                    $controlSalud->setObservacionesGenerales($params['observaciones_generales']);
                }
                if (ControlSaludResource::getInstance()->save($controlSalud)) {
                    $this->listar(0);
                }
                else{
                    $this->edit($params);
                }  
            }
            else{
             $this->edit($params);   
            } 
         } 
         else{
             $this->edit($params);
         }
    }
    public function delete() {

        $cs = ControlSaludResource::getInstance()->get($_GET['idC']);
            if (!is_null($cs)) {
                if (ControlSaludResource::getInstance()->delete($cs->getIdControlSalud())) {
                   $this->listar(0);
                }
                else{
                   $this->listar(0);
                }
            }
    }
    public function graficoTalla(){
        $arreglo = array();
        $sitio = SitioResource::getInstance()->get();
        $arreglo['titulo'] = $sitio->getTitulo();
        $arreglo['mail'] = $sitio->getMail();
        $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
        $arreglo['misRoles'] = $roles;
        $a = ControlSaludResource::getInstance()->valoresTalla($_GET['id']);
        $arreglo['fechas'] = $a['fecha'] ;
        $arreglo['array'] = $a['array'];
        $arreglo['fechas'] = json_encode($arreglo['fechas']);
        $arreglo['array'] = json_encode($arreglo['array']);
        echo TwigUtility::getInstance()->render("vistaGraficoTalla.html", $arreglo);

    }
    public function graficoPC(){
        $arreglo = array();
        $sitio = SitioResource::getInstance()->get();
        $arreglo['titulo'] = $sitio->getTitulo();
        $arreglo['mail'] = $sitio->getMail();
        $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
        $arreglo['misRoles'] = $roles;
        $a = ControlSaludResource::getInstance()->valoresPC($_GET['id']);
        $arreglo['fechas'] = $a['fecha'];
        $arreglo['array'] = $a['array'];
        $arreglo['fechas'] = json_encode($arreglo['fechas']);
        $arreglo['array'] = json_encode($arreglo['array']);
        echo TwigUtility::getInstance()->render("vistaGraficoPC.html", $arreglo);

    }
    public function graficoPPC(){
        $arreglo = array();
        $sitio = SitioResource::getInstance()->get();
        $arreglo['titulo'] = $sitio->getTitulo();
        $arreglo['mail'] = $sitio->getMail();
        $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
        $arreglo['misRoles'] = $roles;
        $a = ControlSaludResource::getInstance()->valoresPPC($_GET['id']);
        $arreglo['fechas'] = $a['fecha'];
        $arreglo['array'] = $a['array'];
        $arreglo['fechas'] = json_encode($arreglo['fechas']);
        $arreglo['array'] = json_encode($arreglo['array']);
        echo TwigUtility::getInstance()->render("vistaGraficoPPC.html", $arreglo);
    }
    public function graficoPeso(){
        $arreglo = array();
        $sitio = SitioResource::getInstance()->get();
        $arreglo['titulo'] = $sitio->getTitulo();
        $arreglo['mail'] = $sitio->getMail();
        $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
        $arreglo['misRoles'] = $roles;
        $a = ControlSaludResource::getInstance()->valoresPeso($_GET['id']);
        $arreglo['fechas'] = $a['fecha'];
        $arreglo['array'] = $a['array'];
        $arreglo['fechas'] = json_encode($arreglo['fechas']);
        $arreglo['array'] = json_encode($arreglo['array']);
        echo TwigUtility::getInstance()->render("vistaGraficoPeso.html", $arreglo);   
    }
    public function graficoEstatura(){
        $arreglo = array();
        $sitio = SitioResource::getInstance()->get();
        $arreglo['titulo'] = $sitio->getTitulo();
        $arreglo['mail'] = $sitio->getMail();
        $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
        $arreglo['misRoles'] = $roles;
        $a = ControlSaludResource::getInstance()->valoresEstatura($_GET['id']);
        var_dump($a);
        $arreglo['fechas'] = $a['fecha'];
        $arreglo['array'] = $a['array'];
        $arreglo['fechas'] = json_encode($arreglo['fechas']);
        $arreglo['array'] = json_encode($arreglo['array']);
        echo TwigUtility::getInstance()->render("vistaGraficoEstatura.html", $arreglo);   
    }
    public function graficoCrecimiento(){
     $arreglo = array();
        $sitio = SitioResource::getInstance()->get();
        $arreglo['titulo'] = $sitio->getTitulo();
        $arreglo['mail'] = $sitio->getMail();
        $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
        $arreglo['misRoles'] = $roles;
        $a = ControlSaludResource::getInstance()->valoresCrecimiento($_GET['id']);
        $arreglo['fechas'] = $a['fecha'];
        $arreglo['array'] = $a['array'];
        $arreglo['fechas'] = json_encode($arreglo['fechas']);
        $arreglo['array'] = json_encode($arreglo['array']);
        echo TwigUtility::getInstance()->render("vistaGraficoCrecimiento.html", $arreglo);      
    }
     public function listar($inicio=0,$metodo="listarParaListar"){
        $arreglo = array();
        $sitio = SitioResource::getInstance()->get();
        $arreglo['titulo'] = $sitio->getTitulo();
        $arreglo['mail'] = $sitio->getMail();
        $arreglo['controlador'] = 'ControlSalud';
        $arreglo['metodo'] = 'listar';
        $arreglo['metodo2'] = $metodo;
        $cantidadControlSalud= ControlSaludResource::getInstance()->cantidadControlSalud($_GET['id']);
        $cantidadDePaginas=$cantidadControlSalud/$sitio->getCantidadElementos();
        if(($cantidadControlSalud%$sitio->getCantidadElementos())>0){
            $cantidadDePaginas=intval($cantidadDePaginas);
            $cantidadDePaginas=$cantidadDePaginas +1;
        }
        if (isset($_GET['inicio']) and isset($_GET['id'])) {
            $listado = ControlSaludResource::getInstance()->all($_GET['inicio'], $sitio->getCantidadElementos(),$_GET['id']);
        }
        else{
           $listado = ControlSaludResource::getInstance()->all(0, $sitio->getCantidadElementos(),$_GET['id']);
        }
        if(isset($listado)){
            $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
                foreach ($roles as $rol) {
                    if ($rol->getNombre() == $_SESSION['rolActual']){
                      $idRol = $rol->getIdRol();
                    }
                }
            $arreglo['idPaciente'] = $_GET['id'];
            $arreglo['misRoles'] = $roles;
            $arreglo['permisos'] = RolResource::getInstance()->listarPermisos($idRol);
            $arreglo['resultados'] = $listado;
            $arreglo['cantidadBotones'] = $cantidadDePaginas;
            $arreglo['cantidad'] = $sitio->getCantidadElementos();
            echo TwigUtility::getInstance()->render("crudControlesDePaciente.html", $arreglo); 
        }
    }
 }

