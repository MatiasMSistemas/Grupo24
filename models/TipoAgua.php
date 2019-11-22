<?php

 class TipoAgua{
    private $idTipoAgua;
    private $nombre;

    public function __construct ($params) {

        $this->setData($params);
    }
     public function setData(Array $params) {

        if(is_array($params)){
            
            if(isset($params["id"])){
                $this->setIdTipoAgua($params["id"]);
            }
                
            if(isset($params["nombre"])){
                $this->setNombre($params["nombre"]);
            }   
        }
    }

    public function getIdTipoAgua(){
        return $this->idTipoAgua;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function setIdTipoAgua($idTipoAgua){
        $this->idTipoAgua = $idTipoAgua;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    } 

}