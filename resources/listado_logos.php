<?php
	echo "<table border='0' id='TableL' class='stripe hover'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th width='10'><b>ID</b></th>";
	echo "<th width='170'><b>Organismo</b></th>";
	echo "<th width='50'><b>Imagen</b></th>";
	echo "<th width='50'><b>Fecha Inicio</b></th>";
	echo "<th width='50'><b>Fecha Fin</b></th>";
	echo "<th width='20'></th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
			
	$bd = Db::getInstance();
	$select = "SELECT id,nombre, imagen,
	DATE_FORMAT(fecha_inicio, '%d/%m/%Y') AS fechaIni, DATE_FORMAT(fecha_fin, '%d/%m/%Y') AS fechaFin  FROM jp_logo
	INNER JOIN jp_logo_convocatoria lg on (id=lg.id_logo) WHERE lg.id_convocatoria=".$_GET['id']." ORDER BY id DESC";
	$c = $bd->ejecutar($select);
	while($row = $bd->obtener_fila($c, MYSQLI_ASSOC)){
		$imagen = ($row['imagen'] != "")? "<a href='".SRV_DIR_DOWNLOAD."logos/".$row['imagen']."'>Descargar</a>" : "--";
		
				
		echo "<tr>";
		echo "<td width='10'>".$row['id']."</td>";
		echo "<td width='170'>".$row['nombre']."</td>";
		echo "<td width='50'>".$imagen."</td>";
		echo "<td width='50'>".$row['fechaIni']."</td>";
		echo "<td width='50'>".$row['fechaFin']."</td>";
		//Añadir aquí el checkbox para seleccionar los logos a eliminar
		//echo "<td width='20' style='text-align: center;'><input type='checkbox' value='' id='eliminar_".$row['id']."'></td>";
		echo "<td width='20' style='text-align: center;'>";
		if($_SESSION['permisos']==0){
			echo "<a onclick='return eliminar_logo(".$row['id'].", ".$_GET['id'].");'><img src='img/cross.png' width=20/></a>";
		}
		echo "</td>";
		echo "</tr>";
	} 
	echo "</tbody>";
	echo "</table>";
	//$bd->desconectar();
?>