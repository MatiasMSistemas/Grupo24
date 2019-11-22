<?php

 class TipoCalefaccion{
    private $idTipoCalefaccion;
    private $nombre;

    public function __construct ($params) {

        $this->setData($params);
    }
     public function setData(Array $params) {

        if(is_array($params)){
            
            if(isset($params["id"])){
                $this->setIdTipoCalefaccion($params["id"]);
            }
                
            if(isset($params["nombre"])){
                $this->setNombre($params["nombre"]);
            }   
        }
    }

    public function getIdTipoCalefaccion(){
        return $this->idTipoCalefaccion;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function setIdTipoCalefaccion($idTipoCalefaccion){
        $this->idTipoCalefaccion = $idTipoCalefaccion;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    } 

}