<?php

class Turnos{
    
    public function __construct(){
    }
    
    public function get_turnos(){
        $consulta="SELECT * FROM turnos";
        try{
            //intancia de base de datos
            $db=new db();
            //conexion
            $db=$db->conectar();
            $ejecutar=$db->query($consulta);
            $clientes=$ejecutar->fetchAll(PDO::FETCH_OBJ);
            $db=null;
            return $clientes;
        }catch(PDOException $e){
            echo '{"error": {"text": '.$e->getMessage().'}';
            
        }
    }

    public function get_turnosFecha($fecha){
        $consulta="SELECT turnos.hora FROM turnos where fecha='$fecha'";

        try{
            //intancia de base de datos
            $db=new db();
            //conexion
            $db=$db->conectar();
            $ejecutar=$db->query($consulta);

            
            if (!(Turnos::existe($fecha))){
                
                return;
            }
            if (!isset($ejecutar)){
                $arreglo=array("8:00-8:30","8:30-9:00","9:00-9:30","9:30-10:00","10:00-10:30","10:30-11:00","11:00-11:30","11:30-12:00","12:00-12:30","12:30-13:00","13:00-13:30","13:30-14:00","14:00-14:30","14:30-15:00","15:00-15:30","15:30-16:00","16:00-16:30","16:30-17:00","17:00-17:30","17:30-18:00","18:00-18:30","18:30-19:00","19:00-19:30","19:30-20:00");
                
                return $arreglo;
            }
            
            while ($filas =$ejecutar->fetch(PDO::FETCH_ASSOC)) {
               $a[]=$filas;
            }
            $db=null;
            $arreglo=array("8:00-8:30","8:30-9:00","9:00-9:30","9:30-10:00","10:00-10:30","10:30-11:00","11:00-11:30","11:30-12:00","12:00-12:30","12:30-13:00","13:00-13:30","13:30-14:00","14:00-14:30","14:30-15:00","15:00-15:30","15:30-16:00","16:00-16:30","16:30-17:00","17:00-17:30","17:30-18:00","18:00-18:30","18:30-19:00","19:00-19:30","19:30-20:00");
            $horasBD=array();
            if (isset($a)){
            for ($i = 0; $i <= (sizeof($a)-1); $i++) {
                $d= $a[$i];
                array_push($horasBD, $d["hora"]);    
            }
            }else{
                $arreglo=array("8:00-8:30","8:30-9:00","9:00-9:30","9:30-10:00","10:00-10:30","10:30-11:00","11:00-11:30","11:30-12:00","12:00-12:30","12:30-13:00","13:00-13:30","13:30-14:00","14:00-14:30","14:30-15:00","15:00-15:30","15:30-16:00","16:00-16:30","16:30-17:00","17:00-17:30","17:30-18:00","18:00-18:30","18:30-19:00","19:00-19:30","19:30-20:00");
                return $arreglo;
            }
            $resultado = array_diff($arreglo, $horasBD);
            return $resultado;
        }catch(PDOException $e){
            echo '{"error": {"text": '.$e->getMessage().'}';
        }
            
    }

    public function setTurno($dni, $fecha, $hora){
        $consulta="INSERT INTO turnos (dni, fecha, hora) values (:dni, :fecha, :hora)";
        try{
            if( (Turnos::existe($fecha)) and (Turnos::horaValida($hora)) and (Turnos::horaNoRepetida($hora, $fecha))){
            //intancia de base de datos
            $db=new db();
            //conexion
            $db=$db->conectar();
            $stmt = $db->prepare($consulta);
            $stmt->bindParam(':dni', $dni);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':hora', $hora);
            $stmt->execute();
            echo '{"notice": {"text": "Cliente agregado"}';
        }
        }catch(PDOException $e){
            echo '{"error": {"text": '.$e->getMessage().'}';
        }
            
    }

    public function existe($fecha){
       $fechaActual=date("Y")."-".date("m")."-".date("d");
       if($fecha > $fechaActual){
            return true;
       }
       echo '{"notice": {"text": "la fecha es invalida"}';
       return false;
            
    }

    public function horaValida($hora){
        $arreglo=array("8:00-8:30","8:30-9:00","9:00-9:30","9:30-10:00","10:00-10:30","10:30-11:00","11:00-11:30","11:30-12:00","12:00-12:30","12:30-13:00","13:00-13:30","13:30-14:00","14:00-14:30","14:30-15:00","15:00-15:30","15:30-16:00","16:00-16:30","16:30-17:00","17:00-17:30","17:30-18:00","18:00-18:30","18:30-19:00","19:00-19:30","19:30-20:00");
        if (in_array($hora, $arreglo)) {
            return true;
        }
        echo '{"notice": {"text": "La hora ingresada es incorrecta en formato: hh:mm-hh:mm o se encuentra en un horario no valido"}';
        return false;
             
     }
     public function horaNoRepetida($hora, $fecha){
        $consulta="SELECT * FROM turnos WHERE turnos.fecha = :fecha and turnos.hora = :hora";
        $db=new db();
        //conexion
        $db=$db->conectar();
        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);
        $stmt->execute();
      
        $clientes=$stmt->fetchAll(PDO::FETCH_OBJ);
        $db=null;
        if (empty($clientes)){
            return true;
        }
        echo '{"notice": {"text": "este horario ya esta asignado"}';
        return false;
             
     }
     
}
?>