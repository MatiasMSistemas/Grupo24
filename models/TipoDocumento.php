<?php

 class TipoDocumento{
    private $idTipoDocumento;
    private $nombre;

    public function __construct ($params) {

        $this->setData($params);
    }
     public function setData(Array $params) {

        if(is_array($params)){
            
            if(isset($params["id"])){
                $this->setIdTipoDocumento($params["id"]);
            }
                
            if(isset($params["nombre"])){
                $this->setNombre($params["nombre"]);
            }   
        }
    }

    public function getIdTipoDocumento(){
        return $this->idTipoDocumento;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function setIdTipoDocumento($idTipoDocumento){
        $this->idTipoDocumento = $idTipoDocumento;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    } 

}