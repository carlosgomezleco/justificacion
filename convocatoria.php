<?php
	
	session_start();
	set_include_path(implode(PATH_SEPARATOR, array(
        realpath(dirname(__FILE__) . '/lib/phpseclib'),
        get_include_path(),
	)));
	include('Net/SFTP.php');
	include_once('lib/Db.php');
	include_once('lib/Conf.class.php');
	include_once('lib/funciones.php');
	header("Content-Type: text/html;charset=utf-8");	
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	$bd = null;
	$_SESSION['id'] =  (isset($_GET['id']))? $_GET['id'] : $_SESSION['id'];
	
?>

<html>  
<head>
	<title>Intranet Justificación Proyectos</title>        
	<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">	
	<script src="js/jquery-1.11.2.min.js"></script>
	<script src="js/jquery.datetables.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/modal.js"></script>
	<script src="js/editconvocatoria.js"></script>
	
	<link rel="stylesheet" href="css/styles.css"/>
	<link rel="stylesheet" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css" media="screen" />
</head>	
<body>
	<div class="style-container">
		<?php
			include('resources/header.php');
		?>	
		<section id="main">
			<article>
			<?php include('resources/menu.php');				
			// Si estan definidas, hemos realizado el login correctamente
			if(isset($_SESSION['user']) and isset($_SESSION['pass'])){ ?>
				<!-- Breadcumbs -->
				<div id="id1">
					<ol class="breadcrumb">
						<li class="active">Inicio</li>
						<li>Editar convocatoria</li>
					</ol>					
				</div>
				<!-- Fin breadcrumbs -->
				<!-- Regresar al listado -->
				<div id="id3"><button class="btn btn-default" onclick="history.back();">Volver al listado</button></div>
				<div id="clear"></div>
				<!-- Bootstrap para mostrar el listado -->
				<div id="container-table">
				<?php 
					//Comprobamos si se ha enviado el formulario
					if($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['permisos']==0){
						
						$error = 0;
						$_SESSION['error_convo'] = "";
						/* Comprobamos si hemos modificado los archivos */
						$convocatoria = (isset($_FILES['convocatoria']['name']))? $_FILES['convocatoria']['name'] : $_SESSION['convo'];
						$resolucion = (isset($_FILES['resolucion']['name']))? $_FILES['resolucion']['name'] : $_SESSION['res_def'];
						$bases = (isset($_FILES['bases']['name']))? $_FILES['bases']['name'] : $_SESSION['bas'];
						
						if($convocatoria!=""){
							$convocatoria=str_replace(" ", "",$convocatoria);
							$pos = strpos($convocatoria, 'C_');
							//Si esto da problemas dejarlo en $convocatoria="C_".$convocatoria;
							if($pos == false ){
								$convocatoria="C_".$convocatoria;
							}else if($pos>0){
								$convocatoria="C_".$convocatoria;
							}
						}
						if($resolucion!=""){
							$resolucion=str_replace(" ", "",$resolucion);
							$pos = strpos($resolucion, 'R_');
							//Si esto da problemas dejarlo en $resolucion="R_".$resolucion;
							if($pos == false ){
								$resolucion="R_".$resolucion;
							}else if($pos>0){
								$resolucion="R_".$resolucion;
							}
						}
						if($bases!=""){
							$bases=str_replace(" ", "",$bases);
							$pos = strpos($bases, 'B_');
							//Si esto da problemas dejarlo en $bases="B_".$bases;
							if($pos == false ){
								$bases="B_".$bases;
							}else if($pos>0){
								$bases="B_".$bases;
							}
							$bases="B_".$bases;
						}
						$actualizar = "UPDATE jp_convocatoria SET nombre ='".$_POST['nombre']."', 
						convocatoria ='". $convocatoria ."', resolucion ='" . $resolucion."', bases ='".$bases."' WHERE id ='".$_POST['id']."'";
						//Conexion e inserción
						$bd = Db::getInstance();					
						$resultado = $bd->ejecutar($actualizar);
						//echo "<p>Código SENTENCIA UPDATE ".$bd->TotalAfectados()."</p>";
						
						//Si se ha actualizado, muestra el listado
						if($bd->TotalAfectados() > -1){
							/*Una vez actualizada la convocatoria, comprobamos si hay ficheros que subir*/
							if($convocatoria != "" or $resolucion != "" or $bases != ""){
								
								$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
								if($objFtp != false){
									if (!$objFtp ->login(SRV_USER, SRV_PASS)){
										$error = 1;
										echo '<div class="alert alert-danger" role="alert">Error en el login con el servidor. No se ha subido 
										la documentación. Por favor, póngase en contacto con el Servicio de Informática (SIRIUS)</div>';
										$objFtp->disconnect();
									}
									else{
										//Si se ha modificado la convocatoria, la modificamos
										if(isset($_FILES['convocatoria']['name'])){
												
											if(($_SESSION['convo'] != $_FILES['convocatoria']['name'])){												
												$strData = file_get_contents($_FILES['convocatoria']['tmp_name']);
												if (!$objFtp->put(SRV_DIR_UPLOAD.$_POST['id']."/".$convocatoria, $strData)){
													$error = 1;
													echo '<div class="alert alert-danger" role="alert">Error al subir la convocatoria. Si el problema persiste, por favor, póngase
													en contacto con el Servicio de Informática (SIRIUS)</div>';
													$objFtp->disconnect();
													//exit(1);
												}
												else{
													//elimino la antigua del server
													$objFtp->delete(SRV_DIR_UPLOAD.$_POST['id']."/".$_SESSION['convo'], false);
												}//if put convocatoria
											}//is sesion convo != files
										} //end if convocatoria != ""
											
										if(isset($_FILES['resolucion']['name'])){
											if($_SESSION['res_def'] != $_FILES['resolucion']['name']){
												$strData = file_get_contents($_FILES['resolucion']['tmp_name']);
												//Subimos el archivo al servidor
												if(!$objFtp->put(SRV_DIR_UPLOAD.$_POST['id']."/".$resolucion, $strData)){
													$error = 1;
													echo '<div class="alert alert-danger" role="alert">Error al subir la resolución. Si el problema persiste, por favor, póngase
													en contacto con el Servicio de Informática (SIRIUS)</div>';											
													$objFtp->disconnect();
													//exit(1);
												}//if put adjudicacion
												else{
													//elimino la antigua
													$objFtp->delete(SRV_DIR_UPLOAD.$_POST['id']."/".$_SESSION['res_def'], false);
												} // end else put
											} //end if($_SESSION['res_def'] != $_FILES['resolucion']['name']
										} //end if adjudicacion != ""
										if(isset($_FILES['bases']['name'])){
											if($_SESSION['bas'] != $_FILES['bases']['name']){
												$strData = file_get_contents($_FILES['bases']['tmp_name']);
												//Subimos el archivo al servidor
												if(!$objFtp->put(SRV_DIR_UPLOAD.$_POST['id']."/".$bases, $strData)){
													$error = 1;
													echo '<div class="alert alert-danger" role="alert">Error al subir la resolución. Si el problema persiste, por favor, póngase
													en contacto con el Servicio de Informática (SIRIUS)</div>';											
													$objFtp->disconnect();
													//exit(1);
												}//if put adjudicacion
												else{
													//elimino la antigua
													$objFtp->delete(SRV_DIR_UPLOAD.$_POST['id']."/".$_SESSION['bas'], false);
												} // end else put
											} //end if($_SESSION['res_def'] != $_FILES['resolucion']['name']
										} //end if adjudicacion != ""
										$objFtp->disconnect();
									} //end if-else login
								}
								else{
									echo '<div class="alert alert-danger" role="alert">Error al conectar con el servidor. No se ha guardado la 
									documentación asociada. Si el problema persiste, por favor, póngase en contacto con el Servicio de Informática
									(SIRIUS)</div>';
								} //end if-else conexion ftp
								
							} //if $convocatoria != "" or $resolucion != ""

							//Vamos al listado de las convocatorias						
							if($error == 0){
								header('Location:convocatorias.php');
								exit;
							}	
						}else 
							echo '<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y la convocatoria no se ha actualizado.</div>';
					}
					
					/*En función del ID de la convocatoria, mostramos un formulario de edición*/
					
					/*Realizamos la conexión*/
					if($bd == null) 
						$bd = Db::getInstance();									
					if($bd == null){
						echo '<div class="alert alert-danger" role="alert">Se ha producido un error al conectar o abrir la base de datos. 
						Inténtelo de nuevo pasados unos minutos.</div>';
					}
					else{
						/*Guardamos el id de la convocatoria para acceder a la misma en la petición POST, ya que en caso de error
						** volvemos a mostrar el formulario*/
						$_SESSION['id'] =  (isset($_GET['id']))? $_GET['id'] : $_SESSION['id'];
						//Obtenemos la información de la convocatoria seleccionada
						$sql = "SELECT * FROM jp_convocatoria WHERE id=". $_SESSION['id'] ." ORDER BY id DESC";
						//Realizamos la consulta de selección
						$resultado = $bd->ejecutar($sql);
					
						if($bd->totalRegistros($resultado) == 0){
							echo '<div class="alert alert-warning">Ha ocurrido un error al recuperar la convocatoria.</div>';
						}						
						else{
							//Obtenemos el resultado en array asociativo (accedemos a los campos por sus nombres)
							$fila = $bd->obtener_fila($resultado, MYSQLI_ASSOC);
							include('datos_convocatoria.php');
						} //Fin !resultado
						$bd->desconectar();	
					}					
					?>
				</div> <!-- div id="container-table"-->	
			<?php 
			}
			else{ 
				echo '<div id="error">Lo sentimos, pero no tiene permisos para acceder. <a href="index.php"><img src="img/volver.png" alt="Regresar al listado"/></a></div><br>';
			}
			?>
			<br>	
			</article>
		</section>
		<?php include('resources/footer.php'); ?>
	</div> <!-- Div container-->
    <div id="clear"><br></div>	
</body>
</html> 