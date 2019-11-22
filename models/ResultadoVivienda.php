<?php
class ResultadoVivienda{
    private $nombre;
    private $cantidad;

    public function __construct ($params) {

        $this->setData($params);
    }
     public function setData(Array $params) {

        if(is_array($params)){
            
            if(isset($params["nombre"])){
                $this->setNombre($params["nombre"]);
            }
                
            if(isset($params["cantidad"])){
                $this->setCantidad($params["cantidad"]);
            }   
        }
    }

    public function getCantidad(){
        return $this->cantidad;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function setCantidad($cantidad){
        $this->cantidad = $cantidad;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    } 

}