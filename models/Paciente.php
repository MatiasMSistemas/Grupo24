<?php

 class Paciente {
    
    private $idPaciente ;
    private $apellido ;
    private $nombre ;
    private $domicilio ;
    private $tel ;
    private $fechaNac ;
    private $genero ;
    private $datosDemograficos ;
    private $obraSocial ;
    private $tipoDocumento;
    private $numero;

    public function __construct ($params) {

        $this->setData($params);
    }

    public function setData(Array $params) {

        if(is_array($params)){
            
            
            if(isset($params["id"])){
                $this->setIdPaciente($params["id"]);
            }
                
            if(isset($params["apellido"])){
                $this->setApellido($params["apellido"]);
            }
            
            if(isset($params["nombre"])){
                $this->setNombre($params["nombre"]);
            }
            
            if(isset($params["domicilio"])){
                $this->setDomicilio($params["domicilio"]);
            }
            
            if(isset($params["tel"])){
                $this->setTel($params["tel"]);
            }
            if(isset($params["fecha_nac"])){
                $this->setFechaNac($params["fecha_nac"]);
            }
            if(isset($params["genero"])){
                $this->setGenero($params["genero"]);
            }
            if(isset($params["datos_demograficos_id"])){
                $datos_demograficos_id = (int)$params["datos_demograficos_id"];
                $this->setDatosDemograficos($datos_demograficos_id);
            }
            if(isset($params["obra_social_id"])){
                $obra_social_id = (int)$params["obra_social_id"];
                $this->setObraSocial($obra_social_id);
            }
            if(isset($params["tipo_doc_id"])){
                $tipo_doc_id = (int)$params["tipo_doc_id"];
                $this->setTipoDocumento($tipo_doc_id);
            }
            if(isset($params["numero"])){
                $this->setNumero($params["numero"]);
            }
        }
    }

    public function getIdPaciente(){
        return $this->idPaciente;
    }
    public function getApellido(){
        return $this->apellido;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function getDomicilio(){
        return $this->domicilio;
    }
    public function getTel(){
        return $this->tel;
    }
    public function getFechaNac(){
        return $this->fechaNac;
    }
    public function getGenero(){
        return $this->genero;
    }
    public function getDatosDemograficos(){
        return $this->datosDemograficos;
    }
    public function getObrasocial(){
        return $this->obraSocial;
    }
    public function getTipoDocumento(){
        return $this->tipoDocumento;
    }
    public function getNumero(){
        return $this->numero;
    }
    public function setIdPaciente($id){
        $this->idPaciente = $id;
    }
    public function setApellido($apellido){
        $this->apellido = $apellido;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }
    public function setDomicilio($domicilio){
        $this->domicilio = $domicilio;
    }
    public function setTel($tel){
        $this->tel = $tel;
    }
    public function setFechaNac($fechaNac){
        $this->fechaNac = $fechaNac;
    }
    public function setGenero($genero){
        $this->genero = $genero;
    }
    public function setDatosDemograficos($datosDemograficos){
        $this->datosDemograficos = $datosDemograficos;
    }
    public function setObraSocial($obraSocial){
        $this->obraSocial = $obraSocial;
    }
    public function setTipoDocumento($tipoDocumento){
        $this->tipoDocumento = $tipoDocumento;
    }
    public function setNumero($numero){
        $this->numero = $numero;
    }
}