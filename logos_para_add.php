<?php
	echo "<table border='0' id='TableLAdd' class='stripe hover'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th width='10'><b>ID</b></th>";
	echo "<th width='170'><b>Nombre</b></th>";
	echo "<th width='50'><b>Imagen</b></th>"; 
	echo "<th width='50'><b>Fecha Inicio</b></th>";
	echo "<th width='50'><b>Fecha Fin</b></th>";
	echo "<th width='20'><b>Seleccionar</b></th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
			
	$bd = Db::getInstance();
	$select = "SELECT id, nombre, imagen, DATE_FORMAT(fecha_inicio, '%d/%m/%Y') AS fechaIni, DATE_FORMAT(fecha_fin, '%d/%m/%Y') AS fechaFin FROM jp_logo WHERE id NOT IN 
		(SELECT id_logo FROM jp_logo INNER JOIN jp_logo_convocatoria WHERE id_convocatoria = ".$_SESSION['id'].")";
	$c = $bd->ejecutar($select);	
	
	if($bd->totalRegistros($c) > 0){ 
		while($row = $bd->obtener_fila($c)){ 
			$imagen = ($row['imagen'] != "")? "<a href='".SRV_DIR_DOWNLOAD."logos/".$row['imagen']."'>Descargar</a>" : "--";
					
			echo "<tr>";
			echo "<td width='10'>".$row['id']."</td>";
			echo "<td width='170'>".$row['nombre']."</td>";
			echo "<td width='50'>".$imagen."</td>";
			echo "<td width='50'>".$row['fechaIni']."</td>";
			echo "<td width='50'>".$row['fechaFin']."</td>";
			echo "<td width='20' style='text-align: center;'><input type='checkbox' value='".$row['id']."' id='chb_".$row['id']."' name= 'Logo'></td>"; //<a href='".$urlEliminar."'><img src='img/cross.png' width=20/></a></td>";
			echo "</tr>";
		} 
		echo "</tbody>";
		echo "</table>";
		
		/////AÑADIR CONTROL PARA QUE LOS CHECKBOX SELECCIONADOS SE INSERTEN EN LA BD, PARA ELLO GUARDAREMOS EN UN CAMPO OCULTO LOS 
		/////ID DE LOS LOGOS SELECCIONADOS SEPARADOS POR ;
		// En datatableLogo.js esta definida la función asociada al botón guardar que procesa la elección de los logos
		/////EL POST DEBERÁ IR A add_logos.php
		echo '<form class="form-horizontal" role="form" method="POST" action="add_logos.php">';
		echo'<div class="form-group">
					<div class="col-lg-offset-1 col-lg-3">
						<button name="btn_guardar" id="btn_guardar" type="submit" class="btn btn-default">Guardar</button>
						<button type="button" name="btn_cancel" id="btn_cancel" onclick="history.back();" class="btn btn-default">Cancelar</button>
						<input id="AddLogos" name="AddLogos" type="hidden" value=""/>
					</div>
				</div>';
		echo '</form>';
	} else{
		echo "</tbody>";
		echo "</table>";
	}
	include('resources/modal.php');
	//$bd->desconectar();
?>