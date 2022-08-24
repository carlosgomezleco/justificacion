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
	<script src="js/datatableProy.js"></script>
	<link rel="stylesheet" href="css/styles.css"/>
	<link rel="stylesheet" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css" media="screen" />

	<script language="Javascript">
	function preguntar(idp, idc){
		if(confirm("¿Desea eliminar el proyecto con ID "+idp+"? Se eliminará toda la documentación asociada al mismo.") == true)   
			window.location.href = "eliminar.php?id="+idp+"&idc="+idc+"&resource=proyecto";
	}
</script>

	
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
				echo '<li>Listado general de proyectos</li>';
				echo '</ol>';
				echo '</div>';
				echo '<div id="container-table">';
				include('resources/listado_proyectos.php');	
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