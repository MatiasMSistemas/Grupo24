<?php

 class TipoVivienda{
    private $idTipoVivienda;
    private $nombre;

    public function __construct ($params) {

        $this->setData($params);
    }
     public function setData(Array $params) {

        if(is_array($params)){
            
            if(isset($params["id"])){
                $this->setIdTipoVivienda($params["id"]);
            }
                
            if(isset($params["nombre"])){
                $this->setNombre($params["nombre"]);
            }   
        }
    }

    public function getIdTipoVivienda(){
        return $this->idTipoVivienda;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function setIdTipoVivienda($idTipoVivienda){
        $this->idTipoVivienda = $idTipoVivienda;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    } 

}