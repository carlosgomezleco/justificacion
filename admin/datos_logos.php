<?php
	echo '<div id="id3" class="col-lg-offset-1 col-lg-3"><button class="btn btn-default" onclick="AddLogo();">Añadir Logo</button></div>
		  <div id="clear"></div>
		  <br/>';
	echo "<table border='0' id='TableLog' class='stripe hover'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th width='10'><b>ID</b></th>";
	echo "<th width='80'><b>Nombre</b></th>";
	echo "<th width='80'><b>Imagen</b></th>";
	echo "<th width='50'><b>Fecha Inicio</b></th>";
	echo "<th width='50'><b>Fecha Fin</b></th>";
	echo "<th width='30'></th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	
	if($bd==null){
		$bd = Db::getInstance();
	}
	$select = "SELECT *, date_format(fecha_inicio,'%d-%m-%Y') as fecha_inicio, date_format(fecha_fin,'%d-%m-%Y') as fecha_fin  
	FROM jp_logo ORDER BY id ASC";
	
	$c = $bd->ejecutar($select);
	while($row = $bd->obtener_fila($c, MYSQLI_ASSOC)){
		$ruta=SRV_DIR_DOWNLOAD;
		$imagen = ($row['imagen'] != "")? "<a target='_blank' href='../".$ruta."logos/".$row['imagen']."'>Descargar</a>" : "--"; ///MIRAR DONDE SE VAN A GUARDAR LAS COSAS
		
		/*$urlEliminar = "eliminar.php?id=".$row['id']."&resource=convocatoria&documents=";
				
		if($solicitud == "--" and $resolucion == "--")
			$urlEliminar .= "no";
		else
			$urlEliminar .= "yes";*/
				
		echo "<tr>";
		echo "<td width='10'>".$row['id']."</td>";
		echo "<td width='80'>".$row['nombre']."</td>";
		echo "<td width='80'>".$imagen."</td>";
		echo "<td width='50'>".$row['fecha_inicio']."</td>";
		echo "<td width='50'>".$row['fecha_fin']."</td>";
		echo "<td width='30' style='text-align: center;'><a onclick='return eliminar_logo(".$row['id'].");'><img src='img/cross.png' width=20/></a><a onclick='return editar_logo(".$row['id'].");'><img src='img/edit.png' width=20/></a></td>"; //<a href='".$urlEliminar."'><img src='img/cross.png' width=20/></a></td>";
		echo "</tr>";
	} 
	echo "</tbody>";
	echo "</table>";
	//$bd->desconectar();
	include ('add_logo.php');
?>