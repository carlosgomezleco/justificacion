<?php
	
	session_start();
	require_once('lib/Db.php');
	require_once('lib/Conf.class.php');
	header("Content-Type: text/html;charset=utf-8");	
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
?>

<html>  
<head>
	<title>Intranet Justificación Proyectos</title>        
	<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">	
	<script src="js/jquery-1.11.2.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<link rel="stylesheet" href="css/styles.css"/>
	<link rel="stylesheet" href="css/bootstrap.css"/>
	<script type="text/JavaScript" language="javascript">

		function edicion(){
				window.location="editar.php?id="+<?php echo $_GET['id']; ?>;
		}
		
		function eliminar(){
			if(confirm("¿Desea eliminar el proyecto?")){
				window.location="borrar.php?id="+<?php echo $_GET['id']; ?>;
			}
		}

	</script>	
</head>	
<body>
	<div class="style-container">
		<?php
			include('resources/header.php');
		?>
	
		<section id="main">
			<article>
				<!-- menú -->
				<?php include('resources/menu.php');?>
				<!-- Fin menú -->
				<!-- Si estan definidas, hemos realizado el login correctamente -->
				<?php if(isset($_SESSION['user']) and isset($_SESSION['pass'])){ ?>
				<!-- Breadcumbs -->
				<div id="id1">
					<ol class="breadcrumb">
						<li class="active">Inicio</li>
						<li>Información del proyecto</li>
					</ol>					
				</div>
				<!-- Fin breadcrumbs -->
				<!-- Regresar al listado -->
				<div id="id3"><button class="btn btn-default" onclick="history.back()">Volver al listado</button></div>
				<div id="clear"></div>
				<!-- Bootstrap para mostrar el listado -->
				<div id="container-table">
					<!-- Fila por cada registro de la base de datos -->
					<?php
					if(isset($_GET['error']) and $_GET['error'] == '1'){
						echo '<div class="alert alert-danger" role="alert">Error al eliminar el proyecto</div>';
						unset($_GET['error']);
					}						
					/*Realizamos la conexión*/
					$bd = Db::getInstance();									
					if($bd === null){
						echo '<div class="alert alert-danger" role="alert">Se ha producido un error al conectar o abrir la base de datos. 
						Inténtelo de nuevo pasados unos minutos.</div>';
					}
					else{
						//Obtenemos la información del proyecto seleccionado
						//$sql = "SELECT * FROM jp_proyecto WHERE id=". $_GET['id'] ." ORDER BY id DESC";
						$sql = "SELECT P.id as id_p, P.expediente as expediente, P.referencia as referencia, P.titulo as titulo, 
						MP.id as id_mod
						FROM jp_proyecto as P LEFT JOIN jp_modificacion_proyecto as MP ON P.id = MP.id_proyecto
						WHERE P.id=". $_GET['id'] ." ORDER BY P.id DESC";
						//Realizamos la consulta de selección
						$resultado = $bd->ejecutar($sql);
					
						if(!$resultado){
							echo '<div class="alert alert-warning">Ha ocurrido un error al recuperar el proyecto.</div>';
						}						
						else{
							//Obtenemos el resultado en array asociativo (accedemos a los campos por sus nombres)
							$fila = $bd->obtener_fila($resultado, MYSQLI_ASSOC);							
														
							$_SESSION['info_proyecto'] = array($fila["expediente"], $fila["referencia"], $fila["titulo"]);
							
							echo '<p>Identificador: '.$fila["id_p"] .'</p>
										<p>Expediente: ' . $fila["expediente"] . '</p>
										<p>Referencia: ' . $fila["referencia"] . '</p>
										<p>Título: ' . $fila["titulo"] . '</p><br>';
							
							echo '  <ul class="nav nav-tabs">
										<li class="active"><a href="#tab1" data-toggle="tab">Modificaciones del proyecto</a></li>
										<li><a href="#tab2" data-toggle="tab">Documentación justificativa</a></li>
										<li><a href="#tab3" data-toggle="tab">Facturación</a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="tab1">';										
									if($fila["id_mod"] == null){
										echo "<p>No existen modificaciones para el proyecto. Para añadir una, haga click en añadir.</p>";
									}	
									else{
										echo "<p>tabla con las modificaciones del proyecto (solicitud y resolucion)</p>";
									}
							echo 	'<p><button class="btn btn-default">Añadir</button></p>';	
							echo	'</div>
									<div class="tab-pane" id="tab2">';
									
							echo '	</div>
									<div class="tab-pane" id="tab3">
										<p>Aqui pondriamos los documentos de las facturas con sus enlaces para descargar</p>
									</div>
										<br>
									<div align="center">
										<button name="btn_editar" id="btn_editar" type="submit" class="btn btn-default" onclick="edicion();">Editar</button>
										&nbsp;&nbsp;&nbsp;
										<button name="btn_eliminar" id="btn_eliminar" type="submit" class="btn btn-default" onclick="eliminar();">Eliminar</button>
									</div>		
								</div>'; //div del tab-content								
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
		<?php 
			include('resources/footer.php');
		?>
	</div> <!-- Div container-->
    <div id="clear"><br></div>	
</body>
</html> 