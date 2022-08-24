<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	
	header('Content-type: application/json'); //echo dirname(__FILE__)."../lib/phpseclib<br>";
	set_include_path(implode(PATH_SEPARATOR, array(
        realpath(dirname(__FILE__) . '/../lib/phpseclib'),
        get_include_path(),
	)));

	include_once('Net/SFTP.php');
	require_once('../lib/Conf.class.php');
	require_once('../lib/Db.php');
	
	
	switch ($_GET['accion']) {
		case 'ELIMINAR_ARCHIVO' :
			$devolver['resultado']=false;
			$id=$_GET['id'];
			$bd = Db::getInstance();
            $sql="UPDATE ".$_GET['tabla']." SET ".$_GET['campo']." = '' WHERE id =".$id;
			$resultado = $bd->ejecutar($sql);
			if($bd->totalAfectados() != 1){						
				//echo '<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el archivo no se ha eliminado de la BD.</div>';
				echo json_encode('Ha habido un error y no se ha actualizado nada');
				exit;
			}else{
				$devolver['resultado']=true;
				//echo json_encode($devolver);
				//exit;
				$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
				if($objFtp != false){
					if($objFtp ->login(SRV_USER, SRV_PASS)){						
						if($objFtp->file_exists(SRV_DIR_UPLOAD.$_GET['ruta'])){
							
							if($objFtp->delete(SRV_DIR_UPLOAD.$_GET['ruta'])){
								$devolver['resultado']=true;
							}	
						}
					}
				}
			}
            echo json_encode($devolver);
            break;
	}
	
?>