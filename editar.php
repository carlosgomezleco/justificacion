<?php
	
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	//session_start();
	set_include_path(implode(PATH_SEPARATOR, array(
        realpath(dirname(__FILE__) . '../lib/phpseclib'),
        get_include_path(),
	)));
	//include('Net/SFTP.php');
	include('lib/Db.php');
	include('lib/Conf.class.php');
	include('lib/funciones.php');
	include('lib/fechas.php');
	header("Content-Type: text/html;charset=utf-8");	
	
	$_SESSION['id'] = isset($_GET['id'])? $_GET['id'] : $_SESSION['id'];
	$_SESSION['convocatoria'] = isset ($_GET['c'])? $_GET['c'] : $_SESSION['convocatoria'];
	$_SESSION['adju']= "";
	$_SESSION['segui']= "";
	if(!isset($_SESSION['tab'])){//Si no hay ninguna, por defecto es la 1
		$_SESSION['tab'] = 1;
	}
	/*Consulta para obtener los datos del proyecto, que deben guardarse en la variable de session
	  $_SESSION['info_proyecto'][], siendo el valor 0 el expediente, el 1 la referencia y el 2 el titulo*/
	  //$consulta = "SELECT expediente,referencia, titulo, adjudicacion, seguimiento FROM jp_proyecto WHERE id =".$_SESSION['id'];
	  $consulta = "SELECT P.expediente,P.referencia, P.titulo, P.adjudicacion, P.seguimiento, C.nombre, case C.organismo
					when 0 then 'Junta de Andalucía'
					when 1 then 'Ministerio'
					end 
				as organismo FROM jp_proyecto as P INNER JOIN jp_convocatoria as C WHERE P.id =".$_SESSION['id']." and 
				C.id = P.id_convocatoria";
	  //Conexion e inserción
	  $bd = Db::getInstance();
	  $resultado = $bd->ejecutar($consulta);
	  $row = $bd->obtener_fila($resultado);
	  //Guardamos en las variables de session la información básica del proyecto
	  $_SESSION['info_proyecto'][0] = $row['expediente'];
	  $_SESSION['info_proyecto'][1] = $row['referencia'];
	  $_SESSION['info_proyecto'][2] = $row['titulo'];
	  $_SESSION['info_proyecto'][3] = $row['nombre'];
	  $_SESSION['info_proyecto'][4] = $row['organismo'];
	  $_SESSION['adju'] = $row['adjudicacion'];
	  $_SESSION['segui'] = $row['seguimiento'];
	  
?>

<html>  
<head>
	<title>Edición proyecto</title>        
	<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->	
	<script src="js/jquery-1.11.2.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/editproyecto.js"></script>
	<script src="js/jquery.datetables.min.js"></script>	
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
				
			   <?php	
			   //menú
			   include('resources/menu.php');
			   //Fin menú
			 //Si estan definidas, hemos realizado el login correctamente -->
			 if(isset($_SESSION['user']) and isset($_SESSION['pass'])){ ?>
				<!-- Breadcumbs -->
				<div id="id1">
				<ol class="breadcrumb">
					<li class="active">Inicio</li>
					<li>Editar proyecto</li>
				</ol>
				</div>
				<!-- Fin breadcrumbs -->
				<div id="clear"></div>				
				<?php 
				//Comprobamos si se ha enviado el formulario mediante el botón guardar
				if($_SERVER["REQUEST_METHOD"] == "POST"){ 
					if(isset($_POST['editar'])){ 
						//si existe un post editar, se está editando los datos básicos del proyecto
						$adjudicacion = (isset($_FILES['adjudicacion']['name']))? $_FILES['adjudicacion']['name'] : $_SESSION['adju'];
						$seguimiento = (isset($_FILES['seguimiento']['name']))? $_FILES['seguimiento']['name'] : $_SESSION['segui'];

						$actualizar = "UPDATE jp_proyecto SET expediente ='".$_POST['expediente']. "', referencia ='".$_POST['referencia']. "',
						titulo ='".$_POST['titulo']. "', adjudicacion ='".$adjudicacion. "', seguimiento ='".$seguimiento."'
						WHERE id ='".$_SESSION['id']."'";
						$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
						if($objFtp != false){
							// Login
							if (!$objFtp ->login(SRV_USER, SRV_PASS)){
								$objFtp->disconnect();
								//exit(1);
							}
							else{
								if($adjudicacion!=""){
									if(isset($_FILES['adjudicacion']['name'])){
										$strData = file_get_contents($_FILES['adjudicacion']['tmp_name']);
										//Subimos el archivo al servidor
										if(!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_SESSION['id']."/".$adjudicacion, $strData)){
											//$error = 1;
											echo '<div class="alert alert-danger" role="alert">Error al subir la resolución. Si el problema persiste, por favor, póngase
											en contacto con el Servicio de Informática (SIRIUS)</div>';											
											$objFtp->disconnect();
											//exit(1);
										}//if put adjudicacion
										else{
											//elimino la antigua
											$objFtp->delete(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/".$_SESSION['adju'], false);
										} // end else put
									} //end if($_SESSION['res_def'] != $_FILES['resolucion']['name']
								}
								if($seguimiento!=""){
									if(isset ($_FILES['seguimiento']['name'])){
										$strData = file_get_contents($_FILES['seguimiento']['tmp_name']);
										//Subimos el archivo al servidor
										if(!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/".$seguimiento, $strData)){
											//$error = 1;
											echo '<div class="alert alert-danger" role="alert">Error al subir la resolución. Si el problema persiste, por favor, póngase
											en contacto con el Servicio de Informática (SIRIUS)</div>';											
											$objFtp->disconnect();
											//exit(1);
										}
										else{
											//elimino la antigua
											$objFtp->delete(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/".$_SESSION['segui'], false);
										} // end else put
									} //end if($_SESSION['res_def'] != $_FILES['resolucion']['name']
								}
								
								//Conexion e inserción
								//$bd = Db::getInstance();
								$resultado = $bd->ejecutar($actualizar);
								//Si se ha insertado, muestra el listado y se va a la pestaña 1, la de Datos del proyecto
								if($bd->totalAfectados() != -1){
									$_SESSION['tab']=1;									
									header('Location:editar.php?id='.$_GET['id'].'&c='.$_GET['c']);
									exit;
								}else {
									echo '<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el proyecto no se ha actualizado.</div>';
								}
							}
						}	
					}else if(isset($_POST['modificacion'])){
						//Aquí se incluye todo el procesamiento necesario para introducir en la BD las modificaciones del proyecto y se va a la pestaña 2 que es la de modificaciones
						$_SESSION['tab']=2;
						include('post_modificacion.php');
						
					}else if(isset($_POST['documentacion'])){
						//Aquí se incluye todo el procesamiento necesario para introducir en la BD la documentacion del proyecto y se va a la pestaña 3 que es la de documentación
						$_SESSION['tab']=3;
						include('post_documentacion.php');
					}else if (isset($_POST['facturacion'])){
						//Aquí se incluye todo el procesamiento necesario para introducir en la Bd las facturas del proyecto y se va a la pestaña 4 que es la de facturas
						$_SESSION['tab']=4;
						include('post_factura.php');
					}else if(isset($_POST['inventario'])){
						//Aquí se incluye todo el procesamiento necesario para introducir en la BD los bienes de inventario del proyecto y se va a la pestaña 5 que es la de inventario
						$_SESSION['tab']=5;
						include('post_inventario.php');
					}else if(isset($_POST['auditoria'])){
						//Aquí se incluye todo el procesamiento necesario para introducir en la BD las auditorias  y se va a la pestaña 7 que es la de auditorias
						$_SESSION['tab']=7;
						include('post_auditoria.php');
						
					}else if(isset($_POST['justificacion'])){
						//Aquí se incluye todo el procesamiento necesario para introducir en la BD las justificaciones  y se va a la pestaña 6 que es la de justificacion
						$_SESSION['tab']=6;
						include('post_justificacion.php');
						
					}else if(isset($_POST['reintegro'])){
						//Aquí se incluye todo el procesamiento necesario para introducir en la BD los reintegros  y se va a la pestaña 6 que es la de justificacion
						include('post_reintegro.php');
						$_SESSION['tab']=6;
					}
					
				}
				if(isset($_SESSION['error_descarga']) and $_SESSION['error_descarga']!=''){
					//si existe error_descarga es que se estaba usando esa pestaña que es la 8
					$_SESSION['tab']=8;
				}
				?>				
				
				<div id="container-table">
					<?php
						//Obtenemos el campo id
						$id = isset($_GET['id'])? $_GET['id'] : $_POST['id'];						
						//comprobamos cual es la pestaña que debemos activar y que habíamos guardado anteriormente
						echo '	<ul class="nav nav-tabs">
									<li'; 
								if((isset($_SESSION['tab']) and $_SESSION['tab']==1) or (!isset($_SESSION['tab'])) ) echo ' class="active" ';
									echo '><a href="#tab1" data-toggle="tab">Datos del proyecto</a></li>';
							if($_SESSION['permisos']==0){
							 echo '<li';
							 if((isset($_SESSION['tab']) and $_SESSION['tab']==2)) echo ' class="active" ';
							 echo'><a href="#tab2" data-toggle="tab">Modificaciones</a></li>';
							}
							 echo '<li';
							 if((isset($_SESSION['tab']) and $_SESSION['tab']==3)) echo ' class="active" ';
							 echo '><a href="#tab3" data-toggle="tab">Documentación</a></li>';
							 echo '<li';
							 if((isset($_SESSION['tab']) and $_SESSION['tab']==4)) echo ' class="active" ';
							 echo '><a href="#tab4" data-toggle="tab">Facturas</a></li>';
							 echo '<li';
							 if((isset($_SESSION['tab']) and $_SESSION['tab']==5)) echo ' class="active" ';
							 echo '><a href="#tab5" data-toggle="tab">Inventario</a></li>';
							if($_SESSION['permisos']==0){  
							  echo '<li';
							  if((isset($_SESSION['tab']) and $_SESSION['tab']==6)) echo ' class="active" ';
							  echo '><a href="#tab6" data-toggle="tab">Justificación</a></li>';
							  echo '<li';
							  if((isset($_SESSION['tab']) and $_SESSION['tab']==7)) echo ' class="active" ';
							  echo '><a href="#tab7" data-toggle="tab">Auditorias</a></li>';
							  echo '<li';
							  if((isset($_SESSION['tab']) and $_SESSION['tab']==8)) echo ' class="active" ';
							  echo '><a href="#tab8" data-toggle="tab">Descarga</a></li>';
							}
							echo'</ul>
								<div class="tab-content">
									<div class="tab-pane'; 
							if((isset($_SESSION['tab']) and $_SESSION['tab']==1) or (!isset($_SESSION['tab'])) ) echo ' active'; 
								echo'" id="tab1"><br>';
							//Mostramos las pestañas. Permisos == 0 para administradores; == 1 para otros usuarios (PAS...)
							//Pestaña 1
							include('edit_proyecto.php');
					?>		
	
						</div>
						<?php 
						
							//Pestaña 2
							if($_SESSION['permisos']==0){ 
								echo '<div class="tab-pane';
								if((isset($_SESSION['tab']) and $_SESSION['tab']==2)) echo ' active';
								echo'" id="tab2">';
									//Aqui pondriamos las modificaciones del proyecto
									 include('modificaciones_proyecto.php'); ?>
								</div>
						<?php } 
							echo'<div class="tab-pane';
							//Pestaña 3
							if((isset($_SESSION['tab']) and $_SESSION['tab']==3)) echo ' active';
							echo'" id="tab3">';
									 include('documentacion_proyecto.php');
							echo'	</div>
								<div class="tab-pane';
							//Pestaña 4
							if((isset($_SESSION['tab']) and $_SESSION['tab']==4)) echo ' active';
							echo '" id="tab4">';
									include('facturas.php');
							echo'</div>
								<div class="tab-pane';
							//Pestaña 5
							if((isset($_SESSION['tab']) and $_SESSION['tab']==5)) echo ' active';
							echo'" id="tab5">';
									include('inventario.php');?>
								</div>
						<?php
							
							if($_SESSION['permisos']==0){
								echo '<div class="tab-pane';
								//Pestaña 6
								if((isset($_SESSION['tab']) and $_SESSION['tab']==6)) echo ' active';
								echo '" id="tab6">';
								include('justificacion.php');
								echo'</div>
								<div class="tab-pane';
								//Pestaña 7
								if((isset($_SESSION['tab']) and $_SESSION['tab']==7)) echo ' active';
								echo'" id="tab7">';
								include('auditorias.php');
								echo'</div>';
								echo'<div class="tab-pane';
								//Pestaña 8
								if((isset($_SESSION['tab']) and $_SESSION['tab']==8)) echo ' active';
								echo '" id="tab8">'; 
								include('eleccion_descarga.php'); 									
								echo'</div>';
							}  ?>
							</div>
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
		<?php
			include('resources/footer.php');
		?>
	</div> <!-- Div container-->
    <div id="clear"><br></div>	
</body>
</html>