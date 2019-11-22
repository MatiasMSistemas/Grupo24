<?php

 class ObraSocial{
    private $idObraSocial;
    private $nombre;

    public function __construct ($params) {

        $this->setData($params);
    }
     public function setData(Array $params) {

        if(is_array($params)){
            
            if(isset($params["id"])){
                $this->setIdObraSocial($params["id"]);
            }
                
            if(isset($params["nombre"])){
                $this->setNombre($params["nombre"]);
            }   
        }
    }

    public function getIdObraSocial(){
        return $this->idObraSocial;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function setIdObraSocial($idObraSocial){
        $this->idObraSocial = $idObraSocial;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    } 

}