<?php

 class Permiso {
    private $idPermiso;
    private $nombre;

    public function __construct ($params) {

        $this->setData($params);
    }
    public function setData(Array $params) {

        if(is_array($params)){
            
            if(isset($params["id"])){
                $this->setIdPermiso($params["id"]);
            }
                
            if(isset($params["nombre"])){
                $this->setNombre($params["nombre"]);
            }
        }
    }
    public function getIdPermiso(){
        return $this->idPermiso;
    }
    public function getNombre(){
        return $this->nombre;
    } 
     public function setIdPermiso($id){
        $this->idPermiso = $id;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    } 
 }