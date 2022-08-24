<?php

	session_start();
	set_include_path(implode(PATH_SEPARATOR, array(
        realpath(dirname(__FILE__) . '/lib/phpseclib'),
        get_include_path(),
	)));
	
	include_once('lib/Db.php');
	include_once('lib/Conf.class.php');
	include_once('lib/funciones.php');
	require_once('Net/SFTP.php');
	header("Content-Type: text/html;charset=utf-8");	
	
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

?>

<html>  
<head>
	<title>Modificación Proyecto</title>        
	<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">	
	<script src="js/jquery-1.11.2.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<link rel="stylesheet" href="css/styles.css"/>
	<link rel="stylesheet" href="css/bootstrap.css"/>
</head>	
<body>
	<div class="style-container">
		<?php include('resources/header.php'); ?>	
		<section id="main">
			<article>
			<?php
			include('resources/menu.php');
			//Si estan definidas, hemos realizado el login correctamente
			 if(isset($_SESSION['user']) and isset($_SESSION['pass'])){ ?>
			<!-- Breadcumbs -->
				<div id="id1">
				<ol class="breadcrumb">
					<li class="active">Inicio</li>
					<li>Modificación proyecto</li>
				</ol>
				</div>
				<!-- Fin breadcrumbs -->
				<div class="clear"></div>					
				<?php 
				$error = 0;
				//<!-- Enviamos el formulario de registro  -->
				if($_SERVER["REQUEST_METHOD"] == "POST"){
					
					$insertar = "INSERT INTO jp_proyecto (expediente, referencia, titulo) 
					VALUES('" . $_POST["expediente"] . "','". $_POST["referencia"] . "','". $_POST["titulo"]."')";
					//Conexion e inserción
					$bd = Db::getInstance() ;
					$resultado = $bd->ejecutar($insertar);
					//Si se ha insertado, muestra el listado
					if($bd->totalAfectados() == 1){
						/*Con el ID del proyecto que acabamos de insertar:
						** - si hemos asignado convocatoria, insertamos el registro en la tabla intermedia
						** - creamos una carpeta en el servidor en la que guardaremos la documentación*/
						$id_proy = $bd->lastID();					
						if(isset($_POST['selc'])){ // and $_POST['selc'] != "-1"){
							$insertar = "INSERT INTO jp_conv_proyectos (id_proyecto, id_convocatoria) VALUES ('".$id_proy."','".$_POST['selc']."')";
							$resultado = $bd->ejecutar($insertar);
							if(!$resultado){
								$error = 2;
								echo '<div class="alert alert-danger" role="alert">Ha ocurrido un error y se ha guardado el proyecto sin 
								asignar la convocatoria. Intente hacerlo desde la edición del proyecto.<br>Si el problema persiste, contacte 
								con el administrador. Pinche <a href="proyectos.php">aquí</a> para ir la listado de proyectos.</div>';
							}
						}							
						$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
						if($objFtp != false){
							// Login
							if (!$objFtp ->login(SRV_USER, SRV_PASS)){
								$objFtp->disconnect();
								//exit(1);
							}
							else{
								if(!$objFtp->mkdir(SRV_DIR_UPLOAD.$id_proy)){									
									$objFtp->disconnect();
								} //end if mkdir
							} //end if-else login
						}
						else{
							
							echo '<div class="alert alert-danger" role="alert">error al conectar con el SFTP</div>'; 
						} //end if-else conexion ftp
					}else{
						$error = 1;
						echo '<div class="alert alert-danger" role="alert">Ha ocurrido un error y el proyecto no se ha guardado. Si el problema 
						persiste, por favor, póngase en contacto con administrador.</div>';
					} //end if-else resultado
					// Si todo ha ido bien
					if($error === 0){
						header('Location:proyectos.php');
						exit;
					}	
				 }// if btn_alta
				 
				//Formulario alta 
				if($error != 2)
					include('resources/form_proyecto.php');
				?>
				<div class="clear"></div>
			<?php 
			}
			else{ 
				echo '<div id="error">Lo sentimos, pero no tiene permisos para acceder. <a href="index.php"><img src="img/volver.png" alt="Regresar al listado"/></a></div><br>';
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