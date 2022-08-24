<?php
	
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	session_start();
	set_include_path(implode(PATH_SEPARATOR, array(
        realpath(dirname(__FILE__) . '../lib/phpseclib'),
        get_include_path(),
	)));
	//include('Net/SFTP.php');
	include('../lib/Db.php');
	include('../lib/Conf.class.php');
	include('../lib/funciones.php');
	include('../lib/fechas.php');
	header("Content-Type: text/html;charset=utf-8");	
	
	  //Conexion a la base de datos
	  $bd = Db::getInstance();
		  
?>

<html>  
<head>
	<title>Administacion Proyecto</title>        
	<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->	
	<script src="../js/jquery-1.11.2.min.js"></script>
	<script src="../js/bootstrap.js"></script>
	<script src="../js/admin.js"></script> <!-- cambiar -->
	<script src="../js/jquery.datetables.min.js"></script>
	
	<link rel="stylesheet" href="../css/styles.css"/>
	<link rel="stylesheet" href="../css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.css" media="screen" />

	
</head>	
<body>
	<div class="style-container">
		<?php
			include('../resources/header.php');
		?>
	
		<section id="main">
			<article>	
			   <?php	
			   //menú
			   include('../resources/menu.php');
			   //Fin menú
			 //Si estan definidas, hemos realizado el login correctamente -->
			 if(isset($_SESSION['user']) and isset($_SESSION['pass']) and $_SESSION['permisos']==0){  ?> 
				<!-- Breadcumbs -->
				<div id="id1">
				<ol class="breadcrumb">
					<li class="active">Inicio</li>
					<li>Zona  Administracion</li>
				</ol>
				</div>
				<!-- Fin breadcrumbs -->
				<div id="clear"></div>				
				<?php 
				//Comprobamos si se ha enviado el formulario mediante el botón guardar
				if($_SERVER["REQUEST_METHOD"] == "POST"){ 
					if(isset($_POST['logo'])){
						include('post_logo.php');
						$_SESSION['adtab']=1;
						
					}else if(isset($_POST['checklist'])){
						//Aquí se incluye todo el procesamiento necesario para introducir en la BD los bienes de inventario del proyecto.
						include('post_checklist.php');
						$_SESSION['adtab']=2;
						
					}else if(isset($_POST['usuarios'])){
						//Aquí se incluye todo el procesamiento necesario para dar permisos a los ususarios en la web.
						include('post_usuario.php');
						$_SESSION['adtab']=3;
					}
				}
				?>				
				
				<div id="container-table">
					<ul class="nav nav-tabs">
						<li <?php if((isset($_SESSION['adtab']) and $_SESSION['adtab']==1) or (!isset($_SESSION['adtab'])) ) echo ' class="active" '; ?> >
						<a href="#tab1" data-toggle="tab">Logos</a></li>
						<li <?php if((isset($_SESSION['adtab']) and $_SESSION['adtab']==2)) echo ' class="active" '; ?>><a href="#tab2" data-toggle="tab">Checklist</a></li>
						<li <?php if((isset($_SESSION['adtab']) and $_SESSION['adtab']==3)) echo ' class="active" '; ?>><a href="#tab3" data-toggle="tab">Usuarios</a></li>
						<li <?php if((isset($_SESSION['adtab']) and $_SESSION['adtab']==4)) echo ' class="active" '; ?>><a href="#tab4" data-toggle="tab">Registro actividad</a></li>						
					</ul>
					<div class="tab-content">
						<div class="tab-pane <?php if((isset($_SESSION['adtab']) and $_SESSION['adtab']==1) or (!isset($_SESSION['adtab'])) ) echo ' active';  ?>" id="tab1"><br>
						<?php include('datos_logos.php');?>
						</div>
						<div class="tab-pane<?php if((isset($_SESSION['adtab']) and $_SESSION['adtab']==2)) echo ' active'; ?>" id="tab2"><br/>
							<!--p>Aqui pondriamos el checklist del proyecto</p-->
							<?php include('checklist.php');?>
						</div>
						<div class="tab-pane<?php if((isset($_SESSION['adtab']) and $_SESSION['adtab']==3)) echo ' active'; ?>" id="tab3"><br/>
							
							<?php include('usuarios.php');?>
						</div>
						<div class="tab-pane<?php if((isset($_SESSION['adtab']) and $_SESSION['adtab']==4)) echo ' active'; ?>" id="tab4"><br/>
							
							<?php include('registro.php');?>
						</div>
					</div>
				</div> <!-- div id="container-table"-->	
			<?php 
			 }
				else{ 
					echo '<div id="error">Lo sentimos, pero no tiene permisos para acceder. <a href="../index.php"><img src="img/volver.png" alt="Regresar al listado"/></a></div><br>';
				}
			?>
			<br>	
			</article>
		</section>
		<?php
			include('../resources/footer.php');
		?>
	</div> <!-- Div container-->
    <div id="clear"><br></div>	
</body>
</html> 