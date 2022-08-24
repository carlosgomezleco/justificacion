<?php
	echo "<table border='0' id='TableC' class='stripe hover'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th width='20'><b>ID</b></th>";
	echo "<th width='80'><b>Título</b></th>";
	echo "<th width='60'><b>Bases</b></th>";
	echo "<th width='60'><b>Convocatoria</b></th>";
	echo "<th width='60'><b>Resolución</b></th>";
	echo "<th width='80'><b>Organismo</b></th>";
	echo "<th width='40'></th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
			
	$bd = Db::getInstance();
	$select = "SELECT *, case organismo when 0 then 'Junta de Andalucia' 
                    else 'Ministerio' end as organismo FROM jp_convocatoria  ORDER BY id DESC";
	$c = $bd->ejecutar($select);
	while($row = $bd->obtener_fila($c, MYSQLI_ASSOC)){
		$bases = ($row['bases'] != "")? "<a href='".SRV_DIR_DOWNLOAD.$row['id']."/".$row['bases']."'>Descargar</a>" : "--";
		$convocatoria = ($row['convocatoria'] != "")? "<a href='".SRV_DIR_DOWNLOAD.$row['id']."/".$row['convocatoria']."'>Descargar</a>" : "--";
		$resolucion = ($row['resolucion'] != "")? "<a href='".SRV_DIR_DOWNLOAD.$row['id']."/".$row['resolucion']."'>Descargar</a>" : "--";			
		$urlEliminar = "eliminar.php?id=".$row['id']."&resource=convocatoria&documents=";
				
		if($convocatoria == "--" and $resolucion == "--")
			$urlEliminar .= "no";
		else
			$urlEliminar .= "yes";
				
		echo "<tr>";
		echo "<td width='20'>".$row['id']."</td>";
		echo "<td width='80'>".$row['nombre']."</td>";
		echo "<td width='60'>".$bases."</td>";
		echo "<td width='60'>".$convocatoria."</td>";
		echo "<td width='60'>".$resolucion."</td>";
		echo "<td width='80'>".$row['organismo']."</td>";
		echo "<td width='40' style='text-align: center;'><a href='convocatoria.php?id=".$row['id']."'><img src='img/edit.png' width=20/></a></td>"; //<a href='".$urlEliminar."'><img src='img/cross.png' width=20/></a></td>";
		echo "</tr>";
	} 
	echo "</tbody>";
	echo "</table>";
	$bd->desconectar();
?>