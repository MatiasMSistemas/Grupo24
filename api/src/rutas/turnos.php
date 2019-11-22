<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require directorio . 'api/src/modelos/Turnos.php';
require directorio . 'api/vendor/autoload.php';
require directorio . 'api/src/config/db.php';


$app = new \Slim\App;
//obtener todos los turnos
$app ->get('/api/turnos', function (Request $request, Response $responce){
     	

    try{
        $resultado=Turnos::get_turnos();
        echo json_encode($resultado);
        return $resultado;
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//obtener horarios de turno de una Fecha
$app ->get('/api/turnos/{fecha}', function (Request $request, Response $responce){
    $fecha=$request -> getAttribute('fecha');
    $request -> removeAttribute('fecha');
    $consulta="SELECT turnos.hora FROM turnos where fecha='$fecha'";
    try{
        $resultado=Turnos::get_turnosFecha($fecha);
        //exportar y mostrar en JSON
        echo json_encode($resultado);
            $app->stop();
            error_log(print_r("llego al stop de turnos"));
            $app->finalize();
            error_log(print_r("llego al finalice de turnos"));
            unset($fecha);
        return $resultado;
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});
//agregar UN turno
$app ->post('/api/turnos/{dni}/fecha/{fecha}/hora/{hora}', function (Request $request, Response $responce){
        
        $dni=$request->getAttribute('dni');
        $fecha=$request->getAttribute('fecha');
        $hora=$request->getAttribute('hora');
        $request -> removeAttribute('dni');
        $request -> removeAttribute('fecha');
        $request -> removeAttribute('hora');
        try{
            Turnos::setTurno($dni, $fecha, $hora);
            $app->stop();
            error_log(print_r("llego al stop de reservar"));
            $app->finalize();
            error_log(print_r("llego al finalice de reservar"));
            unset($dni, $fecha, $hora);
            return true;
        }catch(PDOException $e){
            echo '{"error": {"text": '.$e->getMessage().'}';
        }

    });
//modificar UN turnos  NO SE PIDE
$app ->put('/api/turnos/actualizar/{id}', function (Request $request, Response $responce){
        $id=$request->getAttribute('id');
        $nombre=$request->getParam('nombre');
        $apellido=$request->getParam('apellido');
        $consulta="UPDATE turnos SET nombre = :nombre, apellido = :apellido WHERE id = $id";
        try{
            //intancia de base de datos
            $db=new db();
            //conexion
            $db=$db->conectar();
            $stmt = $db->prepare($consulta);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->execute();
            echo '{"notice": {"text": "Cliente actualizado"}';
            
        }catch(PDOException $e){
            echo '{"error": {"text": '.$e->getMessage().'}';
        }
    });
    //eliminar UN turnos por ID NO SE PIDE
$app ->delete('/api/turnos/borrar/{id}', function (Request $request, Response $responce){
    $id=$request -> getAttribute('id');
    $consulta="DELETE FROM turnos where id='$id'";
    try{
        //intancia de base de datos
        $db=new db();
        //conexion
        $db=$db->conectar();
        $stmt=$db->prepare($consulta);
        $stmt->execute();
        $db=null;
        echo '{"notice": {"text": "Cliente eliminado"}';
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//$app->run();

?>