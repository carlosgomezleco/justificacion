<?php
	
	require_once('lib/ldapquery.php');
	session_start();
	
    $error = null;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(comprobarUsuario($_POST['usuario'])){
			if(validar($_POST['usuario'], $_POST['password'])){				
				$_SESSION['user'] = $_POST['usuario'];
				$_SESSION['pass'] = $_POST['password'];
				header('Location: proyectos.php');
			//	exit;
			}
			else{ 
				$error = '¡Error! Su usuario y/o contraseña no son correctas.';
			}
		}
		else{
			$error = 'Lo sentimos, pero su usuario no tiene permisos para acceder a esta página.';
			
		}
	}
?>

<html>  
	<head>
		<title>Intranet Justificación Proyectos</title>        
		<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
		<link rel="stylesheet" href="css/styles.css"/>
		<link rel="stylesheet" href="css/bootstrap.css"/>
		<script src="js/bootstrap.js"></script>
	</head>

<body>
	<div class="style-container">
		<?php
			include('resources/header.php');
		?>
		<section id="main">
			<article>
				<h2>INTRANET JUSTIFICACIÓN PROYECTOS</h2>
				<div id="form">
					<p class="credenciales">Para acceder será necesario autentificarse con el usuario y contraseña de e-mail de la Universidad de Huelva</p>
					<form class="form-horizontal" role="form" method="post" action="index.php">					
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10"> 
									<label for="usuario" class="col-lg-2 control-label">Usuario</label>
									<input type="text" class="form-control filestyle" name="usuario" id="user" required autofocus value="<?php if(isset($_POST['usuario'])) echo $_POST['usuario']; ?>">
								</div>
							</div>
						  
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10"> 
									<label for="password" class="col-lg-2 control-label">Contraseña</label>
									<input type="password" class="form-control filestyle" name="password" id="pass" required>
								</div>
							</div>
						  
							<div class="form-group">
								<div class="col-lg-offset-4 col-lg-10">
									<button type="submit" class="btn btn-default">Entrar</button>
									<button type="reset" name="restablecer" class="btn btn-default">Restablecer</button>
								</div>
							</div>
					</form>
				</div> <!-- Div id form-->
				<div id="error">&nbsp;<?php echo $error; ?></div>
			</article>
		</section>
		<?php include('resources/footer.php'); ?>
	</div> <!-- Div container-->	
</body>
</html> 