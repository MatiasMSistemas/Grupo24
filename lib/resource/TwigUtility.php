<?php

require_once (directorio . 'vendor/autoload.php');

/**
*	Singleton TwigUtility template utility to render views.
**/

class TwigUtility {
	
	private static $twig;
	
	function __construct() {}


	public static function getInstance() {
		if (!isset(self::$twig)) {
			$loader = new Twig_Loader_Filesystem(array(directorio . '/views/',directorio . '/templates/'));


			self::$twig = new Twig_Environment($loader);

		}
		return self::$twig;
	}

	public function cargarVista($vista, $param=0){
		$loader = new Twig_Loader_Filesystem(array(directorio . '/views/',directorio . '/templates/'));

		self::$twig = new Twig_Environment($loader);
		if ($param != 0){
			echo self::$twig->render($vista, $param);
		}
		else{
			echo self::$twig->render($vista);
		}


	}

}

?>