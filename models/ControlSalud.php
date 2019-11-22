<?php

 class ControlSalud{
    
    private $idControlSalud ;
    private $edad ;
    private $fecha ;
    private $peso ;
    private $vacunasCompletas ;
    private $maduracionAcorde ;
    private $maduracionObservaciones ;
    private $exFisicoNormal ;
    private $exFisicoObservaciones ;
    private $pc ;
    private $ppc ;
    private $talla ;
    private $alimentacion ;
    private $observacionesGenerales ;
    private $paciente ;
    private $user ;

    public function __construct ($params) {

        $this->setData($params);
    }

    public function setData(Array $params) {

        if(is_array($params)){
            
            
            if(isset($params["id"])){
                $this->setIdControlSalud($params["id"]);
            }
                
            if(isset($params["edad"])){
                $this->setEdad($params["edad"]);
            }
            
            if(isset($params["fecha"])){
                $this->setFecha($params["fecha"]);
            }
            
            if(isset($params["peso"])){
                $this->setPeso($params["peso"]);
            }
            
            if(isset($params["vacunas_completas"])){
                $this->setVacunasCompletas($params["vacunas_completas"]);
            }
            if(isset($params["maduracion_acorde"])){
                $this->setMaduracionAcorde($params["maduracion_acorde"]);
            }
            if(isset($params["maduracion_observaciones"])){
                $this->setMaduracionObservaciones($params["maduracion_observaciones"]);
            }
            if(isset($params["ex_fisico_normal"])){
                $this->setExFisicoNormal($params["ex_fisico_normal"]);
            }
            if(isset($params["ex_fisico_observaciones"])){
                $this->setExFisicoObservaciones($params["ex_fisico_observaciones"]);
            }
            if(isset($params["pc"])){
                $this->setPc($params["pc"]);
            }
            if(isset($params["ppc"])){
                $this->setPpc($params["ppc"]);
            }
            if(isset($params["talla"])){
                $this->setTalla($params["talla"]);
            }
            if(isset($params["alimentacion"])){
                $this->setAlimentacion($params["alimentacion"]);
            }
            if(isset($params["observaciones_generales"])){
                $this->setObservacionesGenerales($params["observaciones_generales"]);
            }
            if(isset($params["paciente_id"])){
                $this->setPaciente($params["paciente_id"]);
            }
            if(isset($params["user_id"])){
                $this->setUser($params["user_id"]);
            }
        }
    }

    public function getIdControlSalud(){
        return $this->idControlSalud;
    }
    public function getEdad(){
        return $this->edad;
    }
    public function getFecha(){
        return $this->fecha;
    }
    public function getPeso(){
        return $this->peso;
    }
    public function getVacunasCompletas(){
        return $this->vacunasCompletas;
    }
    public function getMaduracionAcorde(){
        return $this->maduracionAcorde;
    }
    public function getMaduracionObservaciones(){
        return $this->maduracionObservaciones;
    }
    public function getExFisicoNormal(){
        return $this->exFisicoNormal;
    }
    public function getExFisicoObservaciones(){
        return $this->exFisicoObservaciones;
    }
    public function getPc(){
        return $this->pc;
    }
    public function getPpc(){
        return $this->ppc;
    }
    public function getTalla(){
        return $this->talla;
    }
    public function getAlimentacion(){
        return $this->alimentacion;
    }
    public function getObservacionesGenerales(){
        return $this->observacionesGenerales;
    }
    public function getPaciente(){
        return $this->paciente;
    }
    public function getUser(){
        return $this->user;
    }
    public function setIdControlSalud($id){
        $this->idControlSalud = $id;
    }
    public function setEdad($edad){
        $this->edad = $edad;
    }
    public function setFecha($fecha){
        $this->fecha = $fecha;
    }
    public function setPeso($peso){
        $this->peso = $peso;
    }
    public function setVacunasCompletas($vacunasCompletas){
        $this->vacunasCompletas = $vacunasCompletas;
    }
    public function setMaduracionAcorde($maduracionAcorde){
        $this->maduracionAcorde = $maduracionAcorde;
    }
    public function setMaduracionObservaciones($maduracionObservaciones){
        $this->maduracionObservaciones = $maduracionObservaciones;
    }
    public function setExFisicoNormal($exFisicoNormal){
        $this->exFisicoNormal = $exFisicoNormal;
    }
    public function setExFisicoObservaciones($exFisicoObservaciones){
        $this->exFisicoObservaciones = $exFisicoObservaciones;
    }
    public function setPc($pc){
        $this->pc = $pc;
    }
    public function setPpc($ppc){
        $this->ppc = $ppc;
    }
    public function setTalla($talla){
        $this->talla = $talla;
    }
    public function setAlimentacion($alimentacion){
        $this->alimentacion = $alimentacion;
    }
    public function setObservacionesGenerales($observacionesGenerales){
        $this->observacionesGenerales = $observacionesGenerales;
    }
    public function setPaciente($paciente){
        $this->paciente = $paciente;
    }
    public function setUser($user){
        $this->user = $user;
    }
}