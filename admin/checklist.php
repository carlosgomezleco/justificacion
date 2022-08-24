<?php
	echo '<div id="id3" class="col-lg-offset-1 col-lg-3"><button class="btn btn-default" onclick="AddChecklist();">Añadir Checklist</button></div>
		  <div id="clear"></div>
		  <br/>';
	echo "<table border='0' id='TableCheck' class='stripe hover'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th width='10'><b>ID</b></th>";
	echo "<th width='80'><b>Tipo</b></th>";
	echo "<th width='80'><b>Codigo</b></th>";
	echo "<th width='50'><b>Descripcion</b></th>";
	echo "<th width='50'><b>Organismo</b></th>";
	echo "<th width='30'></th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	
	$bd = Db::getInstance();
	
	$select = "SELECT id,codigo,descripcion, case organismo WHEN 0 then 'Junta de Andalucia' else 'Ministerio' end as organismoR, 
	case tipo WHEN 0 then 'Documento' WHEN 1 then 'Factura' else 'Inventario' end  as tipoR
	FROM jp_tipo_documento ORDER BY id ASC";
	
	$c = $bd->ejecutar($select);
	while($row = $bd->obtener_fila($c, MYSQLI_ASSOC)){
		//$ruta = ($row['ruta'] != "")? "<a href='\\".SRV_DIR_DOWNLOAD.$_GET['c']."\\".$_GET['id']."\d\\".$row['ruta']."'>Descargar</a>" : "--"; ///MIRAR DONDE SE VAN A GUARDAR LAS COSAS
		
						
		echo "<tr>";
		echo "<td width='10'>".$row['id']."</td>";
		echo "<td width='80'>".$row['tipoR']."</td>";
		echo "<td width='80'>".$row['codigo']."</td>";
		echo "<td width='50'>".$row['descripcion']."</td>";
		echo "<td width='50'>".$row['organismoR']."</td>";
		echo "<td width='30' style='text-align: center;'><a onclick='return eliminar_Checklist(".$row['id'].");'><img src='img/cross.png' width=20/></a><a onclick='return editar_Checklist(".$row['id'].");'><img src='img/edit.png' width=20/></a></td>"; //<a href='".$urlEliminar."'><img src='img/cross.png' width=20/></a></td>";
		echo "</tr>";
	} 
	echo "</tbody>";
	echo "</table>";
	//$bd->desconectar();
	include ('add_checklist.php');
?>