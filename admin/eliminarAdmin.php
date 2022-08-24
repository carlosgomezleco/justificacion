<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	session_start();
	/*set_include_path(implode(PATH_SEPARATOR, array(
        realpath(dirname(__FILE__) . '/lib/phpseclib'),
        get_include_path(),
	)));
	require_once('/Net/SFTP.php'); */	
	require_once('../lib/Db.php');
	require_once('../lib/Conf.class.php');
	
	header("Content-Type: text/html;charset=utf-8");
	
	//Si estan definidas, hemos realizado el login correctamente
	if(isset($_SESSION['user']) and isset($_SESSION['pass'])){//permisos de Admin
		/*En la petición GET indicamos si el recurso a eliminar (logo o tipo documento)
		** y si tiene documentación asociada, la eliminamos, así como el registro de la base de datos
		**Eliminanos el registro de la base de datos.*/		
		$error = null;
		if($_GET['resource'] == "logo"){
			if(isset($_GET['id'])){
				$error = "";
				$bd = Db::getInstance();
				$sql ="SELECT imagen FROM jp_logo WHERE id=".$_GET['id'];
				$resultado = $bd->ejecutar($sql);
				$row = $bd->obtener_fila($resultado, MYSQLI_ASSOC);
				$imagen = $row['imagen'];
				
				
				$sql ="DELETE FROM jp_logo WHERE id=".$_GET['id'];
				$resultado = $bd->ejecutar($sql);
				//$bd->desconectar();
				if($bd->totalAfectados() == 1){
					//Eliminar la información del resto de tablas: jp_conv_proyectos, jp_modificacion_proyecto
					//Dependiendo las tablas, eliminar documentación si la hubiere
					//Ejecutamos la consulta
					$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
					if($objFtp != false){
						if($objFtp ->login(SRV_USER, SRV_PASS)){
							if($objFtp->file_exists(SRV_DIR_UPLOAD."\\logos\\".$imagen)){
								
								if(!$objFtp->delete(SRV_DIR_UPLOAD."\\logos\\".$imagen, true)){
									$error = "Ha ocurrido un error";
								}	
							}							
						}
					}
				}
				else $error = "Ha ocurrido un error";
				$bd->desconectar();
				//echo "<p>Saliendo de function eliminarModificacion</p>";
				return $error;
				
			}else{
				$error="No se ha recibido correctamente el logo a eliminar";
			}
			$_SESSION['adtab']=1;
			header('Location:index.php');
			return $error;
		}				
		else if($_GET['resource'] == "checklist"){
			if(isset($_GET['id'])){
				$error="";
				$bd = Db::getInstance();
				$sql ="DELETE FROM jp_tipo_documento WHERE id=".$_GET['id'];
				$resultado = $bd->ejecutar($sql);
				//$bd->desconectar();
				if($bd->totalAfectados() != 1){
					$error = "Ha ocurrido un error";
				}
				$bd->desconectar();
				
			}else{
				$error="No se ha recibido correctamente el logo a eliminar";
			}
			$_SESSION['adtab']=2;
			header('Location:index.php');
			return $error;
		}else if($_GET['resource'] == "usuario"){
			if(isset($_GET['id'])){
				$error="";
				$bd = Db::getInstance();
				$sql ="DELETE FROM jp_usuarios WHERE id=".$_GET['id'];
				$resultado = $bd->ejecutar($sql);
				//$bd->desconectar();
				if($bd->totalAfectados() != 1){
					$error = "Ha ocurrido un error";
				}
				$bd->desconectar();
				
			}else{
				$error="No se ha recibido correctamente el logo a eliminar";
			}
			$_SESSION['adtab']=3;
			header('Location:index.php');
			return $error;
		}
	} //if session
	else{
		echo '<div id="error">Lo sentimos, pero no tiene permisos para acceder. <a href="index.php"><img src="img/volver.png" alt="Regresar al listado"/></a></div><br>';
	}
?>