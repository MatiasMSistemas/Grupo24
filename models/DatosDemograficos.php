<?php

 class DatosDemograficos {
    
    private $idDatosDemograficos ;
    private $heladera ;
    private $electricidad ;
    private $mascotas ;
    private $tipoVivienda ;
    private $tipoCalefaccion ;
    private $tipoAgua ;
    
    public function __construct ($params) {

        $this->setData($params);
    }

    public function setData(Array $params) {

        if(is_array($params)){
            
            
            if(isset($params["id"])){
                $this->setIdDatosDemograficos($params["id"]);
            }
                
            if(isset($params["heladera"])){
                $this->setHeladera($params["heladera"]);
            }
            
            if(isset($params["electricidad"])){
                $this->setElectricidad($params["electricidad"]);
            }
            
            if(isset($params["mascota"])){
                $this->setMascotas($params["mascota"]);
            }
            
            if(isset($params["tipo_vivienda_id"])){
                $this->setTipoVivienda($params["tipo_vivienda_id"]);
            }
            if(isset($params["tipo_calefaccion_id"])){
                $this->setTipoCalefaccion($params["tipo_calefaccion_id"]);
            }
            if(isset($params["tipo_agua_id"])){
                $this->setTipoAgua($params["tipo_agua_id"]);
            }
        }
    }

    public function getIdDatosDemograficos(){
        return $this->idDatosDemograficos;
    }
    public function getHeladera(){
        return $this->heladera;
    }
    public function getElectricidad (){
        return $this->electricidad ;
    }
    public function getMascotas(){
        return $this->mascotas;
    }
    public function getTipoVivienda(){
        return $this->tipoVivienda;
    }
    public function getTipoCalefaccion(){
        return $this->tipoCalefaccion;
    }
    public function getTipoAgua(){
        return $this->tipoAgua;
    }
    
    public function setIdDatosDemograficos($id){
        $this->idDatosDemograficos = $id;
    }
    public function setHeladera($heladera){
        $this->heladera = $heladera;
    }
    public function setElectricidad ($electricidad ){
        $this->electricidad  = $electricidad ;
    }
    public function setMascotas($mascotas){
        $this->mascotas = $mascotas;
    }
    public function setTipoVivienda($tipoVivienda){
        $this->tipoVivienda = $tipoVivienda;
    }
    public function setTipoCalefaccion($tipoCalefaccion){
        $this->tipoCalefaccion = $tipoCalefaccion;
    }
    public function setTipoAgua($tipoAgua){
        $this->tipoAgua = $tipoAgua;
    }
    
}