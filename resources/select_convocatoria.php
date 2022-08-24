<?php

	require_once('lib/Db.php');
	require_once('lib/Conf.class.php');	
	
	$bd = Db::getInstance();
	$sql = "SELECT id, nombre FROM jp_convocatoria ORDER BY id DESC";
	$rdo = $bd->ejecutar($sql);
	$existe = 0;
	
	if($bd->totalRegistros($rdo) == 0){
		echo '<div class="alert alert-danger">No existen convocatorias. Debe ir al menú <a href="nueva_convocatoria.php"><i>Gestión de Convocatorias/Nueva convocatoria</i></a>,
		añadir una nueva y luego crear el proyecto.</div>';
	}						
	else{
		$existe = 1;
		echo '<div class="form-group">
				<label for="selc" class="col-lg-2 control-label">Convocatoria</label>
				<div class="col-lg-10">
					<select name="selc" id="selc" class="form-control" required>Convocatoria
						<option value="">Seleccione una convocatoria...</option>';
					foreach($rdo as $fila){
						echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
					}
		echo '</select>
		      </div>
			  </div>';	
	}
?>