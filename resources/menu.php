<?php
	echo   '<p class="logout"><a class="links" href="/vic.investigacion/justificacion/logout.php">Cerrar sesión</a></p>
			<!-- menú -->
			<nav class="navbar navbar-inverse ">
				<div class="container-fluid">
					<div class="collapse navbar-collapse" id="miMenu">
						<ul class="nav navbar-nav">
							<li><a href="/vic.investigacion/justificacion/proyectos.php">INICIO</a></li>
							<li role="presentation" class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">GESTIÓN DE CONVOCATORIAS
							<span class="caret"></span> </a>
							<ul class="dropdown-menu">';
							if(isset($_SESSION['permisos']) && $_SESSION['permisos']==0){	
							echo '	<li><a href="/vic.investigacion/justificacion/nueva_convocatoria.php">Nueva convocatoria</a></li>';
							}
							echo '<li><a href="/vic.investigacion/justificacion/convocatorias.php">Listado convocatorias</a></li>
							</ul> 
							</li>
							<li role="presentation" class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">GESTIÓN DE PROYECTOS
							<span class="caret"></span> </a>
							<ul class="dropdown-menu">';
							if(isset($_SESSION['permisos']) && $_SESSION['permisos']==0){
								echo '<li><a href="/vic.investigacion/justificacion/nuevo_proyecto.php">Nuevo proyecto</a></li>';
							}
								echo'<!-- <li><a href="modificar_proyecto.php">Modificación de proyecto</a></li> -->
								<li><a href="/vic.investigacion/justificacion/proyectos.php">Listado de proyectos</a></li>
							</ul> 
							</li>
							<!--li><a href="/vic.investigacion/justificacion/descarga.php">DESCARGA DOCUMENTACIÓN</a></li-->';
							
	if(isset($_SESSION['permisos']) && $_SESSION['permisos']==0){
		//Si el usuario tiene permisos para administrar
		echo '<li><a href="/vic.investigacion/justificacion/admin/index.php">ZONA ADMINISTRACION</a></li>';
	}
	
	echo'				</ul>
					</div>
				</div>
			</nav>';
?>			