<?php

 class Rol {
    private $idRol;
    private $nombre;

    public function __construct ($params) {

        $this->setData($params);
    }
    public function setData(Array $params) {

        if(is_array($params)){
            
            if(isset($params["id"])){
                $this->setIdRol($params["id"]);
            }
                
            if(isset($params["nombre"])){
                $this->setNombre($params["nombre"]);
            }
        }
    }
    public function getIdRol(){
        return $this->idRol;
    }
    public function getNombre(){
        return $this->nombre;
    } 
     public function setIdRol($id){
        $this->idRol = $id;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    } 
 }