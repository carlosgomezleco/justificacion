<?php
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	
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
?>

<html>  
<head>
	<title>Nuevo Proyecto</title>        
	<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">	
	<script src="js/jquery-1.11.2.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/modal.js"></script>
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
					<li>Nuevo proyecto</li>
				</ol>
				</div>
				<!-- Fin breadcrumbs -->
				<div class="clear"></div>					
				<?php 
				$error = 0;
				
				if($_SERVER["REQUEST_METHOD"] == "POST"){
					$adjudicacion = (isset($_FILES['adjudicacion']['name']))? $_FILES['adjudicacion']['name'] : "";
					$bd = Db::getInstance() ;
					$insertar = "INSERT INTO jp_proyecto (expediente, referencia, titulo, adjudicacion, id_convocatoria) 
					VALUES('" . $_POST["expediente"] . "','". $_POST["referencia"] . "','". $_POST["titulo"]."', '".$adjudicacion."',
					'".$_POST['selc']."')";
					
					$id_convocatoria=$_POST['selc'];
					$resultado = $bd->ejecutar($insertar);
					//Si se ha insertado el proyecto, muestra el listado
					if($bd->totalAfectados() == 1){
						/*Con el ID del proyecto que acabamos de insertar:
						** - si hemos asignado convocatoria, insertamos el registro en la tabla intermedia
						** - creamos una carpeta en el servidor en la que guardaremos la documentación*/
						$id_proy = $bd->lastID();					
						//$bd->desconectar();
						
						$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
						if($objFtp != false){
							// Login
							if (!$objFtp ->login(SRV_USER, SRV_PASS)){
								$objFtp->disconnect();
								//exit(1);
							}
							else{
								if(!$objFtp->mkdir(SRV_DIR_UPLOAD.$id_convocatoria."/".$id_proy)){							
									
								}else{
									if($adjudicacion != ""){
										$strData = file_get_contents($_FILES['adjudicacion']['tmp_name']);
										//Subimos el archivo al servidor
										if (!$objFtp->put(SRV_DIR_UPLOAD.$id_convocatoria."/".$id_proy."/".$adjudicacion, $strData)){
											$error = 2;										
										}
									}
								}
								$objFtp->disconnect();
								//end if mkdir
							} //end if-else login
						}
						else{
							echo '<div class="alert alert-danger" role="alert">error al conectar con el SFTP</div>'; 
						} //end if-else conexion ftp
						$insertar="";
						$fila="";
						$consultar ="SELECT organismo FROM jp_convocatoria WHERE id=".$_POST['selc'];
						$resultado = $bd->ejecutar($consultar);
						$row = $bd->obtener_fila($resultado, MYSQLI_ASSOC);
						$organismo = $row['organismo'];
						//Seleccionamos los id de todos los tipos de documento del organismo y los incluimos en las correspondientes tablas para dejar preparado el checklist
						$consultar = "SELECT id FROM jp_tipo_documento WHERE tipo='0' AND organismo='".$organismo."'";
						$resultado = $bd->ejecutar($consultar);
						while($row = $bd->obtener_fila($resultado, MYSQLI_ASSOC)){
							$insertar= "INSERT INTO jp_documentacion (id_proyecto, id_tipo_documento) VALUES ('".$id_proy."','".$row['id']."')";
							
							$insercion = $bd->ejecutar($insertar);
						}
						$consultar = "SELECT id FROM jp_tipo_documento WHERE tipo='1' AND organismo='".$organismo."'";
						$resultado = $bd->ejecutar($consultar);
						while($row = $bd->obtener_fila($resultado, MYSQLI_ASSOC)){
							$insertar= "INSERT INTO jp_facturacion (id_proyecto, id_tipo_documento) VALUES ('".$id_proy."','".$row['id']."')";
							echo $insertar;
							$insercion = $bd->ejecutar($insertar);
						}
						
						$consultar = "SELECT id FROM jp_tipo_documento WHERE tipo='2' AND organismo='".$organismo."'";
						$resultado = $bd->ejecutar($consultar);
						while($row = $bd->obtener_fila($resultado, MYSQLI_ASSOC)){
							$insertar= "INSERT INTO jp_inventario (id_proyecto, id_tipo_documento) VALUES ('".$id_proy."','".$row['id']."')";
							echo $insertar;
							$insercion = $bd->ejecutar($insertar);
						}
						
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