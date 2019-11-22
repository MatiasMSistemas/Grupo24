<?php

 class Usuario {
    
    private $idUsuario ;
    private $email ;
    private $userName ;
    private $password ;
    private $activo ;
    private $updatedAt ;
    private $createdAt ;
    private $firstName ;
    private $lastName ;
    private $rol;

    public function __construct ($params) {

        $this->setData($params);
    }

    public function setData(Array $params) {

        if(is_array($params)){
            
            
            if(isset($params["id"])){
                $this->setIdUsuario($params["id"]);
            }
                
            if(isset($params["email"])){
                $this->setEmail($params["email"]);
            }
            
            if(isset($params["username"])){
                $this->setUserName($params["username"]);
            }

            if(isset($params["pass"])){
                $this->setPassword($params["pass"]);
            }
            
            if(isset($params["activo"])){
                $this->setActivo($params["activo"]);
            }
            
            if(isset($params["updated_at"])){
                $this->setUpdatedAt($params["updated_at"]);
            }
            if(isset($params["created_at"])){
                $this->setCreatedAt($params["created_at"]);
            }
            if(isset($params["first_name"])){
                $this->setFirstName($params["first_name"]);
            }
            if(isset($params["last_name"])){
                $this->setLastName($params["last_name"]);
            }
            if(isset($params["rol"])){
                $this->setRol($params["rol"]);
            }
        }
    }

    public function getIdUsuario(){
        return $this->idUsuario;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getUserName(){
        return $this->userName;
    }
    public function getPassword(){
        return $this->password;
    }
    public function getActivo(){
        return $this->activo;
    }
    public function getUpdatedAt(){
        return $this->updatedAt;
    }
    public function getCreatedAt(){
        return $this->createdAt;
    }
    public function getFirstName(){
        return $this->firstName;
    }
    public function getLastName(){
        return $this->lastName;
    }
    public function getRol(){
        return $this->rol;
    }
    public function setIdUsuario($id){
        $this->idUsuario = $id;
    }
    public function setEmail($email){
        $this->email = $email;
    }
    public function setUserName($userName){
        $this->userName = $userName;
    }
    public function setPassword($password){
        $this->password = $password;
    }
    public function setActivo($activo){
        $this->activo = $activo;
    }
    public function setUpdatedAt($updatedAt){
        $this->updatedAt = $updatedAt;
    }
    public function setCreatedAt($createdAt){
        $this->createdAt = $createdAt;
    }
    public function setFirstName($firstName){
        $this->firstName = $firstName;
    }
    public function setLastName($lastName){
        $this->lastName = $lastName;
    }
    public function setRol($rol){
        $this->rol = $rol;
    }
}