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
			//seleccionar el nombre con el id que me manda por GET
			//con el Referencia-Expediente
			$consulta = "SELECT expediente,referencia, adjudicacion, seguimiento FROM jp_proyecto WHERE id =".$id_proyecto;
			$bd = Db::getInstance();
			$resultado = $bd->ejecutar($consulta);
			$row = $bd->obtener_fila($resultado);
			
			//obtenemos la referencia-expediente para nombrar así al fichero zip que vamos a generar y eliminamos espacios y /
			$nombre=$row['referencia']."-".$row['expediente'];
			$nombre=str_replace('/','_',$nombre);
			$nombre=str_replace(' ','',$nombre);
			 
			$filename = $nombre.'.zip';

			$archive = new PclZip('tmp/'.$filename);
			//$v_list = $archive->create('tmp/file.txt');	'docs/'.$id_convocatoria.'/'.$id_proyecto.'/
			
			$aArchivos = null;
			$nArchivos = 0;
			//comprobamos que cada una de las carpetas que contiene el proyecto existen y tienen al menos un fichero, en cuyo caso 
			//la incluimos en el fichero zip
			if($row['adjudicacion']!=''){
				$aArchivos[] = array( PCLZIP_ATT_FILE_NAME => 'docs/'.$id_convocatoria.'/'.$id_proyecto.'/'.$row['adjudicacion'],
								   PCLZIP_ATT_FILE_NEW_FULL_NAME => 'adj_'.$row['adjudicacion']);
								   $nArchivos++;
			}
			
			if($row['seguimiento']!=''){
				$aArchivos[] = array( PCLZIP_ATT_FILE_NAME => 'docs/'.$id_convocatoria.'/'.$id_proyecto.'/'.$row['seguimiento'],
								   PCLZIP_ATT_FILE_NEW_SHORT_NAME => 'seg_'.$row['seguimiento']);
								   $nArchivos++;
			}
			
			if(file_exists('docs/'.$id_convocatoria.'/'.$id_proyecto.'/a') and count (scandir('docs/'.$id_convocatoria.'/'.$id_proyecto.'/a'))>2){
				$aArchivos[] = array( PCLZIP_ATT_FILE_NAME => 'docs/'.$id_convocatoria.'/'.$id_proyecto.'/a',
								   PCLZIP_ATT_FILE_NEW_FULL_NAME => 'auditoria');
								   $nArchivos++;
			}
			
			if(file_exists('docs/'.$id_convocatoria.'/'.$id_proyecto.'/d')and count (scandir('docs/'.$id_convocatoria.'/'.$id_proyecto.'/d'))>2){
				$aArchivos[] = array( PCLZIP_ATT_FILE_NAME => 'docs/'.$id_convocatoria.'/'.$id_proyecto.'/d',
								   PCLZIP_ATT_FILE_NEW_SHORT_NAME => 'documentacion');
								   $nArchivos++;
			}
			
			if(file_exists('docs/'.$id_convocatoria.'/'.$id_proyecto.'/f')and count (scandir('docs/'.$id_convocatoria.'/'.$id_proyecto.'/f'))>2){
				$aArchivos[] = array( PCLZIP_ATT_FILE_NAME => 'docs/'.$id_convocatoria.'/'.$id_proyecto.'/f',
								   PCLZIP_ATT_FILE_NEW_SHORT_NAME => 'facturacion');
								   $nArchivos++;
			}
			
			if(file_exists('docs/'.$id_convocatoria.'/'.$id_proyecto.'/i')and count (scandir('docs/'.$id_convocatoria.'/'.$id_proyecto.'/i'))>2){
				$aArchivos[] = array( PCLZIP_ATT_FILE_NAME => 'docs/'.$id_convocatoria.'/'.$id_proyecto.'/i',
								   PCLZIP_ATT_FILE_NEW_SHORT_NAME => 'inventario');
								   $nArchivos++;
			}
			
			if(file_exists('docs/'.$id_convocatoria.'/'.$id_proyecto.'/j')and count (scandir('docs/'.$id_convocatoria.'/'.$id_proyecto.'/j'))>2){
				$aArchivos[] = array( PCLZIP_ATT_FILE_NAME => 'docs/'.$id_convocatoria.'/'.$id_proyecto.'/j',
								   PCLZIP_ATT_FILE_NEW_SHORT_NAME => 'justificacion');
								   $nArchivos++;
			}
			
			if(file_exists('docs/'.$id_convocatoria.'/'.$id_proyecto.'/m')and count (scandir('docs/'.$id_convocatoria.'/'.$id_proyecto.'/m'))>2){
				$aArchivos[] = array( PCLZIP_ATT_FILE_NAME => 'docs/'.$id_convocatoria.'/'.$id_proyecto.'/m',
								   PCLZIP_ATT_FILE_NEW_SHORT_NAME => 'modificacion');
								   $nArchivos++;
			}
			if($nArchivos>0){
				//Si hay algún fichero en la carpeta del proyecto generamos el fichero con las carpetas que hemos ido incluyendo en el array $aArchivos
				$v_list = $archive->create($aArchivos, PCLZIP_OPT_REMOVE_PATH, 'docs/'.$id_convocatoria.'/'.$id_proyecto);
				if ($v_list == 0) {
					die("Error : ".$archive->errorInfo(true));
				}
				header("Content-type: application/octet-stream");
				header("Content-disposition: attachment; filename=".$filename);
				readfile('tmp/'.$filename);
				unlink('tmp/'.$filename);
			}else{
				//En caso de que no haya ningún documento informamos de esta circunstancia.
				$_SESSION['error_descarga']= '<div class="alert alert-danger" role="alert">No hay ningún documento en el proyecto</div>';
				header("Location: editar.php?id=".$_GET['id']."&c=".$_GET['c']);
			}
		}
	}
	$objFtp->disconnect();
	 //Destruyearchivo temporal
// $it=0;
//if($zip->open("'".$filename."'",ZIPARCHIVE::CREATE)===true) {
        
		//echo "entra en open <br/>";
		//realizar la consulta de la documentacion
		//$zip->addFile('docs/'.$id_convocatoria.'/'.$id_proyecto.'/d/otri_logo2.png', $nombre.'/otri_logo2.png');
		/*$consulta="SELECT ruta FROM jp_documentacion WHERE id_proyecto ='".$id_proyecto."'";
		$c = $bd->ejecutar($consulta);
		while($row = $bd->obtener_fila($c, MYSQLI_ASSOC)){
			if($row['ruta']!=""){
				$zip->addFile(SRV_DIR_DOWNLOAD.$id_convocatoria.'/'.$id_proyecto.'/d/'.$row['ruta'], $nombre.'/'.$row['ruta']);
				$it++;
				echo SRV_DIR_DOWNLOAD.$id_convocatoria.'/'.$id_proyecto.'/d/'.$row['ruta'];
				
			}
		} */
        
		
		//realiar la consulta de las facturas
		//$zip->addFile('docs/'.$id_convocatoria.'/'.$id_proyecto.'/f/otri_logo2.png', $nombre.'/otri_logo2.png');
		/*$consulta="SELECT factura, acreditacion_pago FROM jp_facturacion WHERE id_proyecto ='".$id_proyecto."'";
		$c = $bd->ejecutar($consulta);
		while($row = $bd->obtener_fila($c, MYSQLI_ASSOC)){
			if($row['factura']!=""){
				$zip->addFile(SRV_DIR_DOWNLOAD.$id_convocatoria.'/'.$id_proyecto.'/f/'.$row['factura'], $nombre.'/'.$row['factura']);
				$it++;
				echo SRV_DIR_DOWNLOAD.$id_convocatoria.'/'.$id_proyecto.'/f/'.$row['factura'];
			}
			if($row['acreditacion_pago']!=""){
				$zip->addFile(SRV_DIR_DOWNLOAD.$id_convocatoria.'/'.$id_proyecto.'/f/'.$row['acreditacion_pago'], $nombre.'/'.$row['acreditacion_pago']);
				$it++;
				echo SRV_DIR_DOWNLOAD.$id_convocatoria.'/'.$id_proyecto.'/f/'.$row['acreditacion_pago'];
			}
		}
		/
		//$zip->addFile('docs/otri_logo2.png');
		$zip->addFile('a.php');
		//realizar la consulta del inventario
		//$zip->addFile('docs/'.$id_convocatoria.'/'.$id_proyecto.'/i/otri_logo2.png', $nombre.'/otri_logo2.png');
		/*$consulta="SELECT imagen FROM jp_inventario WHERE id_proyecto ='".$id_proyecto."'";
		$c = $bd->ejecutar($consulta);
		while($row = $bd->obtener_fila($c, MYSQLI_ASSOC)){
			if($row['imagen']!=""){
				$zip->addFile(SRV_DIR_DOWNLOAD.$id_convocatoria.'/'.$id_proyecto.'/i/'.$row['imagen'], $nombre.'/'.$row['imagen']);
				$it++;
				echo SRV_DIR_DOWNLOAD.$id_convocatoria.'/'.$id_proyecto.'/i/'.$row['imagen'];
			}
			
		}//
		//$zip->addFile('docs/otri_logo2.png');
		/*
		if($it==0){
			$zip->addFile('docs/vacio.txt', $nombre.'/vacio.txt');
			//echo 'docs/vacio.txt';
		}
		//$zip->addFile('docs/otri_logo2.png', $nombre.'/otri_logo2.png');	
        $zip->close();
		
        header("Content-type: application/octet-stream");
	header("Content-disposition: attachment; filename=".$filename);
		// leemos el archivo creado
		readfile("'".$filename."'");
		// Por último eliminamos el archivo temporal creado
		unlink("'".$filename."'"); //Destruyearchivo temporal
		//volver a editar*/
?>