<?php
define("directorio", __dir__ . "/"); 
require_once directorio . 'vendor/autoload.php';
require_once directorio . 'controller/Controller.php';
require_once directorio . 'controller/Rooteador.php';

$loader = new Twig_Loader_Filesystem(array('./views/','./templates/'));

$twig = new Twig_Environment($loader);

if (!isset($_SESSION)){
	session_start();
}

$rooteador = new Rooteador($twig);

?>