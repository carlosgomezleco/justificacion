<?php
	echo '<div id="id3" class="col-lg-offset-1 col-lg-3"><button class="btn btn-default" onclick="AddModificacion();">Añadir Modificacion</button></div>
		  <div id="clear"></div>
		  <br/>';
	echo "<table border='0' id='TableM' class='stripe hover'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th width='10'><b>ID</b></th>";
	echo "<th width='50'><b>Solicitud</b></th>";
	echo "<th width='50'><b>Resolución</b></th>";
	echo "<th width='170'><b>Observaciones</b></th>";
	echo "<th width='20'></th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
			
	if(isset($bd)==false){
		$bd = Db::getInstance();
	}
	$select = "SELECT *  FROM jp_modificacion_proyecto WHERE id_proyecto='".$_GET['id']."' ORDER BY id DESC";
	$c = $bd->ejecutar($select);
	while($row = $bd->obtener_fila($c, MYSQLI_ASSOC)){
		$solicitud = ($row['solicitud'] != "")? "<a target='_blank' href='".SRV_DIR_DOWNLOAD.$_GET['c']."/".$_GET['id']."/m/".$row['solicitud']."'>Descargar</a>" : "--";
		$resolucion = ($row['resolucion'] != "")? "<a target='_blank' href='".SRV_DIR_DOWNLOAD.$_GET['c']."/".$_GET['id']."/m/".$row['resolucion']."'>Descargar</a>" : "--";			
		/*$urlEliminar = "eliminar.php?id=".$row['id']."&resource=convocatoria&documents=";
				
		if($solicitud == "--" and $resolucion == "--")
			$urlEliminar .= "no";
		else
			$urlEliminar .= "yes";*/
				
		echo "<tr>";
		echo "<td width='10'>".$row['id']."</td>";
		echo "<td width='50'>".$solicitud."</td>";
		echo "<td width='50'>".$resolucion."</td>";
		echo "<td width='170'>".$row['observacion']."</td>";
		echo "<td width='20' style='text-align: center;'>";
		echo "<a onclick='return eliminar_modificacion(".$row['id'].", ".$_GET['id'].",".$_GET['c'].");'><img src='img/cross.png' width=20/></a>";
		echo "<a onclick='return editar_modificacion(".$row['id'].", ".$_GET['id'].",".$_GET['c'].");'><img src='img/edit.png' width=20/></a>";
		//<a href='convocatoria.php?id=".$row['id']."'><img src='img/edit.png' width=20/></a>
		echo "</td>"; //<a href='".$urlEliminar."'><img src='img/cross.png' width=20/></a></td>";
		echo "</tr>";
	} 
	echo "</tbody>";
	echo "</table>";
	include ('add_Modificacion.php');
	//$bd->desconectar();
?>