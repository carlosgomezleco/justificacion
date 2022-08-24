<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	
	/*set_include_path(implode(PATH_SEPARATOR, array(
        realpath(dirname(__FILE__) . '/lib/phpseclib'),
        get_include_path(),
	)));
	require_once('Net/SFTP.php'); */	
	require_once('lib/Db.php');
	require_once('lib/Conf.class.php');
	require_once('lib/funciones.php');
	
	header("Content-Type: text/html;charset=utf-8");
	
	//echo "<p>Empezando...</p>";

	//Si estan definidas, hemos realizado el login correctamente
	if(isset($_SESSION['user']) and isset($_SESSION['pass'])){
		/*En la petición GET indicamos si el recurso a eliminar (convocatoria o proyecto)
		** y si tiene documentación asociada, la eliminamos, así como el registro de la base de datos
		**Eliminanos el registro de la base de datos.*/		
		$error = null;
		//echo "Comprobando recurso...<bR>";
		if($_GET['resource'] == "convocatoria"){
			if(eliminarConvocatoria($_GET['id'], $_GET['documents']) == "") header('Location:convocatorias.php');
		}				
		else if($_GET['resource'] == "proyecto"){	
			//echo "<p>Se va a eliminar el proyecto ".$_GET['id']."</p>";
			eliminarProyecto($_GET['id'],$_GET['idc']);
		
		}else if($_GET['resource'] == "logo"){
			eliminarLogo($_GET['idl'], $_GET['idc']);
			
		}else if($_GET['resource'] == "mod"){
			eliminarModificacion($_GET['idm'], $_GET['idp'], $_GET['idc']);
		
		}else if($_GET['resource'] == "doc"){
			eliminarDocumentacion($_GET['idd'], $_GET['idp'], $_GET['idc']);
		
		}else if($_GET['resource'] == "fac"){
			eliminarFactura($_GET['idf'], $_GET['idp'], $_GET['idc']);
		
		}else if($_GET['resource'] == "inv"){
			eliminarInventario($_GET['idi'], $_GET['idp'], $_GET['idc']);
			
		}else if($_GET['resource'] == "aud"){
			eliminarAuditoria($_GET['ida'], $_GET['idp'], $_GET['idc']);
			
		}else if($_GET['resource'] == "jus"){
			eliminarJustificacion($_GET['idj'], $_GET['idp'], $_GET['idc']);
			
		}else if($_GET['resource'] == "rei"){
			eliminarReintegro($_GET['idr'], $_GET['idp'], $_GET['idc']);
			
		}else if($_GET['resource'] == "basesConvo"){ echo "prueba";
			eliminarBasesConvo($_GET['idc']);
			
		}else if($_GET['resource'] == "docConvo"){
			//echo "<p>Eliminando una convo";
			eliminarDocConvo($_GET['idc']); 
			//echo "a es " . $a; exit;
			//if ($a == "") 
				//header('Location:convocatorias.php');
			//else echo "Error " . $a;
			
			
		}else if($_GET['resource'] == "resConvo"){
			eliminarResConvo($_GET['idc']);
			
		}
		
	} //if session
	else{
		echo '<div id="error">Lo sentimos, pero no tiene permisos para acceder. <a href="index.php"><img src="img/volver.png" alt="Regresar al listado"/></a></div><br>';
	}
?>