<?php

 class Sitio{
    private $idSitio;
    private $titulo;
    private $descripcion;
    private $mail;
    private $cantidadElementos;
    private $habilitado;

    public function __construct ($params) {

        $this->setData($params);
    }
    public function setData($params) {

        if(is_array($params)){
            
            if(isset($params["id"])){
                $this->setIdSitio($params["id"]);
            }
                
            if(isset($params["titulo"])){
                $this->setTitulo($params["titulo"]);
            }
            if(isset($params["descripcion"])){
                $this->setDescripcion($params["descripcion"]);
            }
                
            if(isset($params["mail"])){
                $this->setMail($params["mail"]);
            }
            if(isset($params["cantidadElementos"])){
                $this->setCantidadElementos($params["cantidadElementos"]);
            }
                
            if(isset($params["habilitado"])){
                $this->setHabilitado($params["habilitado"]);
            }
        }
    }
    public function getIdSitio(){
        return $this->idSitio;
    }
    public function getTitulo(){
        return $this->titulo;
    } 
    public function getDescripcion(){
        return $this->descripcion;
    }
    public function getMail(){
        return $this->mail;
    } 
    public function getCantidadElementos(){
        return $this->cantidadElementos;
    }
    public function getHabilitado(){
        return $this->habilitado;
    } 
     public function setIdSitio($id){
        $this->idSitio = $id;
    }
    public function setTitulo($titulo){
        $this->titulo = $titulo;
    }
    public function setDescripcion($descripcion){
        $this->descripcion = $descripcion;
    }
    public function setMail($mail){
        $this->mail = $mail;
    } 
    public function setCantidadElementos($cantidadElementos){
        $this->cantidadElementos = $cantidadElementos;
    }
    public function setHabilitado($habilitado){
        $this->habilitado = $habilitado;
    }  
 }