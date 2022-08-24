<?php
	echo '<h1 class="titulo_pest">Justificaciones</h1>';
	echo '<div id="id3" class="col-lg-offset-1 col-lg-3"><button class="btn btn-default" onclick="AddJustificacion();">Añadir Justificacion</button></div>
		  <div id="clear"></div>
		  <br/>';
	echo "<table border='0' id='TableJ' class='stripe hover'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th width='10'><b>ID</b></th>";
	echo "<th width='50'><b>N Solicitud</b></th>";
	echo "<th width='50'><b>Documento</b></th>";
	echo "<th width='50'><b>Fecha</b></th>";
	echo "<th width='120'><b>Observaciones</b></th>";
	echo "<th width='20'></th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
			
	if(isset($bd)==false){
		$bd = Db::getInstance();
	}
	$select = "SELECT *, date_format(fecha,'%d-%m-%Y') as fecha  FROM jp_justificacion_documental WHERE id_proyecto='".$_GET['id']."' ORDER BY id DESC";
	$c = $bd->ejecutar($select);
	while($row = $bd->obtener_fila($c, MYSQLI_ASSOC)){
		$documento = ($row['documento'] != "")? "<a target='_blank' href='".SRV_DIR_DOWNLOAD.$_GET['c']."/".$_GET['id']."/j/".$row['documento']."'>Descargar</a>" : "--";
		
						
		echo "<tr>";
		echo "<td width='10'>".$row['id']."</td>";
		echo "<td width='50'>".$row['n_solicitud']."</td>";
		echo "<td width='50'>".$documento."</td>";
		echo "<td width='50'>".$row['fecha']."</td>";
		echo "<td width='120'>".$row['observaciones']."</td>";
		echo "<td width='20' style='text-align: center;'>";
		echo "<a onclick='return eliminar_justificacion(".$row['id'].", ".$_GET['id'].",".$_GET['c'].");'><img src='img/cross.png' width=20/></a>";
		echo "<a onclick='return editar_justificacion(".$row['id'].", ".$_GET['id'].",".$_GET['c'].");'><img src='img/edit.png' width=20/></a>";
		//<a href='convocatoria.php?id=".$row['id']."'><img src='img/edit.png' width=20/></a>
		echo "</td>"; //<a href='".$urlEliminar."'><img src='img/cross.png' width=20/></a></td>";
		echo "</tr>";
	} 
	echo "</tbody>";
	echo "</table><br/><br/>";
	echo '<h1 class="titulo_pest">Reintegros</h1>';
	echo '<div id="id3" class="col-lg-offset-1 col-lg-3"><button class="btn btn-default" onclick="AddReintegro();">Añadir Reintegro</button></div>
		  <div id="clear"></div>
		  <br/>';
	echo "<table border='0' id='TableR' class='stripe hover'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th width='20'><b>ID</b></th>";
	echo "<th width='70'><b>Notificacion</b></th>";
	echo "<th width='70'><b>Respuesta</b></th>";
	echo "<th width='120'><b>Observaciones</b></th>";
	echo "<th width='20'></th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
			
	$select = "SELECT *  FROM jp_reintegro WHERE id_proyecto='".$_GET['id']."' ORDER BY id DESC";
	$c = $bd->ejecutar($select);
	while($row = $bd->obtener_fila($c, MYSQLI_ASSOC)){
		$solicitud = ($row['solicitud'] != "")? "<a target='_blank' href='".SRV_DIR_DOWNLOAD.$_GET['c']."/".$_GET['id']."/j/".$row['solicitud']."'>Descargar</a>" : "--";
		$pago = ($row['pago'] != "")? "<a target='_blank' href='".SRV_DIR_DOWNLOAD.$_GET['c']."/".$_GET['id']."/j/".$row['pago']."'>Descargar</a>" : "--";
						
		echo "<tr>";
		echo "<td width='20'>".$row['id']."</td>";
		echo "<td width='70'>".$solicitud."</td>";
		echo "<td width='70'>".$pago."</td>";
		echo "<td width='120'>".$row['observaciones']."</td>";
		echo "<td width='20' style='text-align: center;'>";
		echo "<a onclick='return eliminar_reintegro(".$row['id'].", ".$_GET['id'].",".$_GET['c'].");'><img src='img/cross.png' width=20/></a>";
		echo "<a onclick='return editar_reintegro(".$row['id'].", ".$_GET['id'].",".$_GET['c'].");'><img src='img/edit.png' width=20/></a>";
		//<a href='convocatoria.php?id=".$row['id']."'><img src='img/edit.png' width=20/></a>
		echo "</td>"; //<a href='".$urlEliminar."'><img src='img/cross.png' width=20/></a></td>";
		echo "</tr>";
	} 
	echo "</tbody>";
	echo "</table>";
	include ('add_justificacion.php');
	include ('add_reintegro.php');
	//$bd->desconectar();
?>