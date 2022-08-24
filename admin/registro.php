<?php

	session_start();
	$_SESSION['adtab'] = 4;
	echo '<form id="form-logo" class="form-horizontal" onsubmit="mensaje()" role="form" method="POST" action="post_registro.php">';
	echo '<div id="id3" class="col-lg-offset-1 col-lg-3"><button type="submit" class="btn btn-default" >Limpiar Registro</button></div>';
	echo '</form><br>';

	$bd = Db::getInstance();
	$sql ="SELECT texto FROM jp_registro WHERE id=1";
	$resultado = $bd->ejecutar($sql);
	$row = $bd->obtener_fila($resultado); 
	if($row['texto'] != null) echo "<p>Acciones realizadas.</p>".$row['texto'];
	else echo "<p>El registro está vacío.</p>";

?>