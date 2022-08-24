<?php
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	session_start();

	set_include_path(implode(PATH_SEPARATOR, array(
        realpath(dirname(__FILE__) . '/lib/phpseclib'),
        get_include_path(),
	)));
	include('Net/SFTP.php');
	include_once('lib/config.php');
	include_once('lib/pclzip.lib.php');
	include('lib/Db.php');
	include('lib/Conf.class.php');
	
 ///crear consulta 1 
 /*
$zip = new ZipArchive();*/
 
 $id_proyecto=$_GET['id'];
 $id_convocatoria=$_GET['c']; 
 $objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
	if($objFtp != false){
		// Login
		if (!$objFtp ->login(SRV_USER, SRV_PASS)){
			$objFtp->disconnect();
			//exit(1);
		}else{
			$aConsultas =  array("SELECT ruta FROM jp_documentacion WHERE id_proyecto =".$id_proyecto." and subida is NULL and ruta != ''",
								 "SELECT imagen FROM jp_inventario WHERE id_proyecto =".$id_proyecto." AND imagen != '' AND subida IS NULL",
								 "SELECT factura, acreditacion_pago FROM jp_facturacion WHERE id_proyecto =".$id_proyecto." AND subida IS NULL and factura !='' and acreditacion_pago !=''");
			//Conexion e inserción
			$bd = Db::getInstance();
			$consulta = "SELECT expediente,referencia, adjudicacion, seguimiento FROM jp_proyecto WHERE id =".$id_proyecto;
			$resultado = $bd->ejecutar($consulta);
			$row = $bd->obtener_fila($resultado);
			
			//Consultamos la referencia-expediente para nombrar así el fichero Zip que vamos a crear y eliminamos / y espacios
			$nombre=$row['referencia']."-".$row['expediente'];
			$nombre=str_replace('/','_',$nombre);
			$nombre=str_replace(' ','',$nombre);
			$filename = $nombre.'.zip';
			$archive = new PclZip('tmp/'.$filename);
			$v_list=0;
			$aArchivos = null;
			
			
			for($i = 0; $i < count($aConsultas);$i++){
				//echo "<p>".$aConsultas[$i]."</p>";
				$resultado = $bd->ejecutar($aConsultas[$i]); //ejecutas consulta
				//consultamos he incluimos los ficheros de documentación, facturación e inventario que existen en el array $aArchivos
				if($bd->totalRegistros($resultado) > 0){
					switch($i){
						case 0: while($fila = $bd->obtener_fila($resultado)){
									$aArchivos[] = array( PCLZIP_ATT_FILE_NAME => 'docs/'.$id_convocatoria.'/'.$id_proyecto.'/d/'.$fila['ruta'],
															  PCLZIP_ATT_FILE_NEW_FULL_NAME => 'documentacion/'.$fila['ruta']
														);
								}
								break;
						case 1: while($fila = $bd->obtener_fila($resultado)){
									$aArchivos[] = array( PCLZIP_ATT_FILE_NAME => 'docs/'.$id_convocatoria.'/'.$id_proyecto.'/i/'.$fila['imagen'],
															  PCLZIP_ATT_FILE_NEW_FULL_NAME => 'inventario/'.$fila['imagen']
														);
								}
								break;
						case 2: while($fila = $bd->obtener_fila($resultado)){
									$aArchivos[] = array(PCLZIP_ATT_FILE_NAME => 'docs/'.$id_convocatoria.'/'.$id_proyecto.'/f/'.$fila['factura'],
															  PCLZIP_ATT_FILE_NEW_FULL_NAME => 'facturacion/'.$fila['factura']);
									$aArchivos[] = array(PCLZIP_ATT_FILE_NAME => 'docs/'.$id_convocatoria.'/'.$id_proyecto.'/f/'.$fila['acreditacion_pago'],
															  PCLZIP_ATT_FILE_NEW_FULL_NAME => 'facturacion/'.$fila['acreditacion_pago']);
								}
								break;
					}					
				}
				
			}
			
			if($aArchivos!=null){
				//Si el array $aArchivos no está vacio creamos el fichero Zip
				$v_list = $archive->create($aArchivos, PCLZIP_OPT_REMOVE_PATH, 'docs/'.$id_convocatoria.'/'.$id_proyecto);
				if ($v_list == 0) {
					die("Error : ".$archive->errorInfo(true));
				}
				
				header("Content-type: application/octet-stream");
				header("Content-disposition: attachment; filename=".$filename);
				readfile('tmp/'.$filename);
				unlink('tmp/'.$filename);
			}else{
				//En caso de que no haya archivos informamos de esta circunstancia
				$_SESSION['error_descarga']= '<div class="alert alert-danger" role="alert">No hay ningún documento en el proyecto</div>';
				header("Location: editar.php?id=".$_GET['id']."&c=".$_GET['c']);
			}
		}
	}
	$objFtp->disconnect();
	

?>