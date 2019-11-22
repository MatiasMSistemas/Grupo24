<?php
include_once directorio . "models/Db.php"; 
include_once directorio . 'lib/resource/RolResource.php';

Class LoginUtility{
	private $db;
	private static $instance;

	private function __construct(){
		$this->db = DB::getInstance();	
	} 
	
	/*Avoids cloning the object*/
	public function __clone(){}
	
	/*Avoid the use of unserialize*/
	public function __wakeup(){}
	
    public static function getInstance() {
        if (is_null(self::$instance)){
            self::$instance = new LoginUtility();
        }
        return self::$instance;
    }
	
	public function login($userName, $pass){		

		$sql="SELECT u.id, u.activo
                FROM usuario AS u
                WHERE u.username = :userName and u.borrado = 0 and u.pass = :pass";
		$qr = $this->db->prepare($sql);
		$qr->bindParam(":userName", $userName, PDO::PARAM_STR);
		$qr->bindParam(":pass", $pass,PDO::PARAM_STR);
        $qr->execute();
		$row = array();
		$row = $qr->fetch(PDO::FETCH_ASSOC);
		if (!empty($row)) {
			if ($row['activo'] == 0 ){
					$_SESSION['id'] = $row['id'];
					$roles = RolResource::getInstance()->getVarios($row['id']);
					$ok = 0;
					foreach ($roles as $r) {
						if ($ok == 0){
							$rol = $r->getNombre();
							$ok = 1;
						}
					}
					$_SESSION['rolActual'] = $rol;
					return True;   
			} else {
				// return false;
				return False;
			}
		} else {
			// return false;
			return False;
		}
	}
	
	public function logout(){
		session_destroy();
	}
}