<?php


	session_start();
	set_include_path(implode(PATH_SEPARATOR, array(
        realpath(dirname(__FILE__) . '/lib/phpseclib'),
        get_include_path(),
	)));
	
	include_once('lib/Db.php');
	//include_once('lib/config.php');
	include_once('lib/Conf.class.php');
	include_once('lib/funciones.php');
	require_once('Net/SFTP.php');
	header("Content-Type: text/html;charset=utf-8");	
	
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

?>

<html>  
<head>
	<title>Nueva convocatoria</title>        
	<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">	
	<script src="js/jquery-1.11.2.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/modal.js"></script>
	<script src="js/nuevaconvocatoria.js"></script>
	<link rel="stylesheet" href="css/styles.css"/>
	<link rel="stylesheet" href="css/bootstrap.css"/>
</head>	
<body>
	<div class="style-container">
		<?php include('resources/header.php'); ?>	
		<section id="main">
			<article>
				<!-- menu -->
				<?php include('resources/menu.php'); ?>
				<!-- Fin menú -->
			<!-- Si estan definidas, hemos realizado el login correctamente -->
			<?php if(isset($_SESSION['user']) and isset($_SESSION['pass'])){ ?>
			
			<!-- Breadcumbs -->
				<div id="id1">
					<ol class="breadcrumb">
						<li class="active">Inicio</li>
						<li>Nueva convocatoria</li>
					</ol>
				</div>
				<!-- Fin breadcrumbs -->
				<div class="clear"></div>
					
				<?php 
				// Enviamos el formulario de registro
				if($_SERVER["REQUEST_METHOD"] == "POST"){
					$error = 0; //echo "SRV - " .SRV_NAME."<br>";
					//Primero comprobamos si hay ficheros que subir
					$convocatoria = (isset($_FILES['convocatoria']['name']))? $_FILES['convocatoria']['name'] : "";
					$resolucion = (isset($_FILES['resolucion']['name']))? $_FILES['resolucion']['name'] : "";
					$bases = (isset($_FILES['bases']['name']))? $_FILES['bases']['name'] : "";
					
					//Antes de insertar el registro en la BD eliminamos los espacios de los nombres de los ficheros y le anteponemos la inicial del tipo de fichero que es
					if($convocatoria!=""){
						$convocatoria=str_replace(" ", "",$convocatoria);
						$convocatoria="C_".$convocatoria;
					}
					if($resolucion!=""){
						$resolucion=str_replace(" ", "",$resolucion);
						$resolucion="R_".$resolucion;
					}
					if($bases!=""){
						$bases=str_replace(" ", "",$bases);
						$bases="B_".$bases;
					}
					$organismo = $_POST['organismo'];
					
					//SQL
					$insertar = "INSERT INTO jp_convocatoria (nombre, convocatoria, resolucion, organismo, bases) 
					VALUES('" . $_POST["nombre"] . "', '". $convocatoria. "', '" . $resolucion . "', '". $organismo ."','". $bases . "');";
					//Conexion e inserción
					$bd = Db::getInstance();
					$resultado = $bd->ejecutar($insertar);
					//$conn->Close();

					if($bd->TotalAfectados() == 1 ){
						$id_conv = $bd->lastID();					
						$bd->desconectar();
						//if($convocatoria != "" or $resolucion != "" or $bases != ""){
							/*Comprobamos si hay documentación a subir. Si la hay, la subimos y guardamos en BD;
							**en caso contrario, terminamos y vamos al listado de convocatorias*/
							$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
							if($objFtp !== false){
								
								if (!$objFtp ->login(SRV_USER, SRV_PASS)){
									$error = 1;
									echo '<div class="alert alert-danger" role="alert">Error en el login con el servidor. Por favor, póngase en contacto con 
									el Servicio de Informática (SIRIUS)</div>';
								}
								else{
									
									$objFtp->chdir(SRV_DIR_UPLOAD);
									if($objFtp->file_exists($id_conv) == false){
										
										if(!$objFtp->mkdir($id_conv)){
											echo '<div class="alert alert-danger" role="alert">Error al crear el directorio. Por favor, contacte con el administrador.</div>'; 
											$objFtp->disconnect();
											exit;
										}							
									}
									//Si se han incluido convocatoria y resolucion, subimos los ficheros
									if($convocatoria != ""){
										$strData = file_get_contents($_FILES['convocatoria']['tmp_name']);										
										//echo "intentando subir el fichero convocatoria a la direccion :".SRV_DIR_UPLOAD.$id_conv."/".$convocatoria;										
										if (!$objFtp->put($id_conv."/".$convocatoria, $strData)){
											$error = 2;										
										}//if put convocatoria
									} //end if convocatoria != ""
								
									if($resolucion != ""){
										$strData = file_get_contents($_FILES['resolucion']['tmp_name']);
										//Subimos el archivo al servidor
										if (!$objFtp->put($id_conv."/".$resolucion, $strData)){
											$error = 2;										
										}//if put adjudicacion	
									} //end if adjudicacion != ""
									if($bases != ""){
										$strData = file_get_contents($_FILES['bases']['tmp_name']);
										//Subimos el archivo al servidor
										if (!$objFtp->put($id_conv."/".$bases, $strData)){
											$error = 2;										
										}
									}
									if($error == 2){
										echo '<div class="alert alert-danger" role="alert">Error al subir el/los documento/s. Si el problema persiste,
											guarde la convocatoria sin adjuntar el/los documento/s y contacte con el administrador.</div>';											
										$objFtp->disconnect();
									}	
								} //end if-else login
							}
							else{
								$error = 1;
								echo '<div class="alert alert-danger" role="alert">Error al conectar con el servidor. Si el problema persiste, por favor, póngase en contacto con el 
								Servicio de Informática (SIRIUS)</div>'; 							
							}//end if-else conexion ftp					
							$objFtp->disconnect();						
						//}
						header('Location:convocatorias.php');
						exit;
					}else{ //else de resultado (operacion BD)
						echo '<div class="alert alert-danger" role="alert">Ha ocurrido un error y  no se ha guardado. Si el problema 
						persiste, por favor, póngase en contacto con el Servicio de Informática (SIRIUS).</div>';
					}//end if-else resultado
				 //if error === 0				 	
				 }// if btn_alta
				//Formulario de alta convocatoria
				include('resources/form_convocatoria.php');
				echo '<div class="clear"></div>'; 
			} //if login intranet
			else{ 
				echo '<div id="error">Lo sentimos, pero no tiene permisos para acceder.<br><a href="index.php"><img src="img/volver.png" alt="Regresar al listado"/></a></div><br>';
			}
			?>
			<br>	
			</article>
		</section>
		<?php
		include('resources/footer.php');
		?>
	</div> <!-- Div container-->
	<div id="clear"><br></div>
</body>
</html>