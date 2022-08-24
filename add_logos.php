<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	session_start();
	require_once('lib/Db.php');
	require_once('lib/Conf.class.php');
	header("Content-Type: text/html;charset=utf-8");
?>

<html>  
<head>
	<title>Listado proyectos</title>        
	<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">	
	<script src="js/jquery-1.11.2.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/jquery.datetables.min.js"></script>
	<script src="js/datatableLogo.js"></script>
	<link rel="stylesheet" href="css/styles.css"/>
	<link rel="stylesheet" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css" media="screen" />
	
</head>	
<body>
	<div class="style-container">
		<?php 
			include('resources/header.php'); 	
			echo '<section id="main">';
			echo '<article>';
			include('resources/menu.php');
			if(isset($_SESSION['user']) and isset($_SESSION['pass'])){
				echo '<div id="id1">';
				echo '<ol class="breadcrumb">';
				echo '<li class="active">Inicio</li>';
				echo '<li>Editar convocatoria</li>';
				echo '<li>Añadir logos</li>';
				echo '</ol>';
				echo '</div>';
				
				if($_SERVER["REQUEST_METHOD"] == "POST"){
					//echo $_POST['btn_guardar'];??
					//Al elegir los logos que se desean añadir, se separan los id de los logos por ; 
					//a la hora de insertarlos en la base de datos separamos por ; y obtenemos un 
					//array con cada uno de los ids de los logos que se van a añadir
					$Logos=$_POST['AddLogos'];
					$Log=explode(";",$Logos);
					$bd = Db::getInstance() ;
					foreach($Log as $valor){
						$insertar="INSERT INTO jp_logo_convocatoria (id_logo,id_convocatoria) VALUES ('"
							.$valor."',".$_SESSION['id'].")";
						$resultado = $bd->ejecutar($insertar);
						if($bd->totalAfectados() < 0){
							$error = 1;
							echo '<div class="alert alert-danger" role="alert">Ha ocurrido un error y el logo no se ha guardado. Si el problema 
							persiste, por favor, póngase en contacto con administrador.</div>';
						}
					}
					//$bd->desconectar();
				}
				echo '<!-- Regresar al listado -->';
				echo '<div id="id3"><a href="convocatoria.php?id='.$_SESSION['id'].'"><button class="btn btn-default">Volver a convocatoria</button></a></div>';
				echo '<div id="clear"></div>';
				echo '<div id="container-table">';
				include('logos_para_add.php');	
				echo '</div>'; //div id="container-table"
			}
			else{ 
				echo '<div id="error">Lo sentimos, pero no tiene permisos para acceder. <a href="index.php"><img src="img/volver.png" alt="Regresar al listado"/></a></div><br>';
			}
			echo '<br>';
			echo '</article>';
			echo '</section>';
			include('resources/footer.php');				
		?>
	</div> <!-- Div container-->	
	<div id="clear"><br></div>
</body>
</html>