<?php

	 include_once directorio . "controller/Usuario.php";
	 include_once directorio . "controller/Rol.php";
	 include_once directorio . "controller/Permiso.php";
	 include_once directorio . "controller/Sitio.php";
	 include_once directorio . "lib/resource/RolResource.php";
	 include_once directorio . "lib/resource/TwigUtility.php";
 	 include_once directorio . "lib/resource/SitioResource.php";
 	 include_once directorio . "lib/resource/UsuarioResource.php";

class Rooteador{


	public function __construct(){

		$sitio = SitioResource::getinstance()->get();
		$habilitado = $sitio->getHabilitado();
		$arreglo ['titulo'] = $sitio->getTitulo();
		$arreglo ['descripcion'] = $sitio->getDescripcion();
		$arreglo ['mail'] = $sitio->getMail();

		//pregunta por el sitio para ver si esta habilitado o no
		
		if($habilitado == 0){
			if (isset($_SESSION['id']) and !empty($_GET) and !empty($_GET['m']) and $_GET["m"] != 'logOut'){
				$user = UsuarioResource::getInstance()->get($_SESSION['id']);
				$userRoles = $user->getRol();
				foreach ($userRoles as $idR) {
					$permisos = RolResource::getInstance()->listarPermisos($idR->getIdRol());

					if(!empty($_GET) and !empty($_GET['m']) and !empty($_GET['c']) ){
						$ok = False;
						foreach ($permisos as $p) {
							if($_GET['m'] == $p->getNombre()){
							 	$ok = True;
							}
						}
						if ($ok){
							$nombreController = $_GET['c']. 'Controller';
							if (file_exists( directorio . "/controller/" . $_GET["c"] . ".php")) {
								require_once (directorio . '/controller/' . $_GET["c"] . '.php');
								$controller = new $nombreController();
								unset($_GET["c"]);

								if (isset($_GET["m"]) and ($_GET["m"])) {
									$controller->$_GET["m"]($_POST);
									unset($_GET["m"]);
									return;
								}
							
							}
						}
					}
					else{
						echo TwigUtility::getInstance()->render( $_SESSION['rolActual'] . ".html" , $arreglo);
					}
				}
			}
			else{
				if(!empty($_GET) and !empty($_GET['c']) and $_GET["c"] == 'LogIn'){
					$nombreController = $_GET['c'] . 'Controller';
					if (file_exists( directorio . "controller/" . $_GET["c"] . ".php")) {
								require_once directorio . 'controller/' . $_GET["c"] . '.php';
								
								$controller = new $nombreController();
								unset($_GET["c"]);

								if (isset($_GET["m"]) and ($_GET["m"])) {
									$controller->$_GET["m"]($_POST);
									unset($_GET["m"]);
								}
								return ;
					}
				}
				//me fijo que vista cargar cuando se esta logueado
				if (isset($_SESSION['id'])){
		            $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
		            $arreglo['misRoles'] = $roles;
		            $cant = 0;
		            foreach ($roles as $r) {
		                    $nombre = $r->getNombre();
		                    $permisos = RolResource::getInstance()->listarPermisos($r->getIdRol());
		                    if (isset($nombre) and $cant == 0){
		                        $cant = 1;
		                        $arreglo['permisos'] = $permisos;
		                        echo TwigUtility::getInstance()->render( $nombre . ".html" , $arreglo);
		                        return;
		                    }   
		            }

		        }
		        else{
		                echo TwigUtility::getInstance()->render("index.html", $arreglo);
		        }
			}
		}
		else{
			//si el sitio esta deshabilitado
			if (isset($_SESSION['id']) and !empty($_GET) and !empty($_GET['m']) and $_GET["m"] != 'logOut'){
				
	            $roles = RolResource::getInstance()->getVarios($_SESSION['id']);
	            $arreglo['misRoles'] = $roles;
	            $cant = 0;
	            foreach ($roles as $r) {
	                    $nombre = $r->getNombre();
	                    $permisos = rolResource::getInstance()->listarPermisos($r->getIdRol());
	                    if (isset($nombre) and $nombre == "administrador" and $cant == 0){
	                    	$cant = 1;
	                    	if (!empty($_GET) and !empty($_GET['c']) and $_GET["c"] == 'Sitio') {
								$nombreController = $_GET['c']. 'Controller';
								if (file_exists( directorio . "/controller/" . $_GET["c"] . ".php")) {
									require_once (directorio . '/controller/' . $_GET["c"] . '.php');
									$controller = new $nombreController();
									unset($_GET["c"]);

									if (isset($_GET["m"]) and ($_GET["m"])) {
										$controller->$_GET["m"]($_POST);
										unset($_GET["m"]);
										return;
									}
								}
							}
	                        $arreglo['permisos'] = $permisos;
	                        echo TwigUtility::getInstance()->render( $nombre . ".html" , $arreglo);
	                        return;
	                    }   
	            }
	            echo TwigUtility::getInstance()->render("sitioDeshabilitado.html");
	            return;

	        }
	        else{
        		if(!empty($_GET) and !empty($_GET['c']) and $_GET["c"] == 'LogIn'){
					$nombreController = $_GET['c'] . 'Controller';
					if (file_exists( directorio . "controller/" . $_GET["c"] . ".php")) {
								require_once directorio . 'controller/' . $_GET["c"] . '.php';
								
								$controller = new $nombreController();
								unset($_GET["c"]);

								if (isset($_GET["m"]) and ($_GET["m"])) {
									$controller->$_GET["m"]($_POST);
									unset($_GET["m"]);
								}
								return;
					}
        		}
        		else{
        			echo TwigUtility::getInstance()->render("index.html", $arreglo);
        		}
	    	}
	                
		}	 
	} 
}

?>