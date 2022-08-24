<?php
	//Si el usuario tiene los permisos 0=>'Administrador' se le muestra el boton para añadir en caso de que tenga permisos 0 =>'PAS' no se le muestra
	if($_SESSION['permisos']==0){
	echo '<div id="id3" class="col-lg-offset-1 col-lg-3"><button class="btn btn-default" onclick="AddInventario();">Añadir Inventario</button></div>';
	}
	echo '<div id="clear"></div>
		  <br/>';
	echo "<table border='0' id='TableI' class='stripe hover'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th width='20'><b>ID</b></th>";
	echo "<th width='50'><b>Tipo Doc</b></th>";
	echo "<th width='80'><b>Imagen</b></th>";
	echo "<th width='50'><b>Justificacion</b></th>";
	echo "<th width='80'><b>Observaciones</b></th>";
	echo "<th width='20'></th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	
	if($bd==null){
		$bd = Db::getInstance();
	}
	$select = "SELECT i.id as idi, td.codigo as cod, td.descripcion as des, i.imagen as img, i.observaciones as obs, i.subida as sub 
	FROM jp_inventario i INNER JOIN jp_tipo_documento td on (i.id_tipo_documento=td.id) 
	WHERE i.id_proyecto='".$_GET['id']."' ORDER BY i.id DESC";
	
	$c = $bd->ejecutar($select);
	while($row = $bd->obtener_fila($c, MYSQLI_ASSOC)){
		$imagen = ($row['img'] != "")? "<a target='_blank' href='".SRV_DIR_DOWNLOAD.$_GET['c']."/".$_GET['id']."/i/".$row['img']."'>Descargar</a>" : "--";
		/*$urlEliminar = "eliminar.php?id=".$row['id']."&resource=convocatoria&documents=";
				
		if($solicitud == "--" and $resolucion == "--")
			$urlEliminar .= "no";
		else
			$urlEliminar .= "yes";*/
				
		echo "<tr>";
		echo "<td width='20'>".$row['idi']."</td>";
		echo "<td width='50'>".$row['cod']."-".$row['des']."</td>";
		echo "<td width='80'>".$imagen."</td>";
		echo "<td width='50'>".$row['sub']."</td>";
		echo "<td width='80'>".$row['obs']."</td>";
		echo "<td width='20' style='text-align: center;'>";
		//Si el usuario tiene los permisos 0=>'Administrador' se le permite eliminar en caso de que tenga permisos 0 =>'PAS' no se le permite
		if($_SESSION['permisos']==0){
		echo "<a onclick='return eliminar_inventario(".$row['idi'].", ".$_GET['id'].",".$_GET['c'].");'><img src='img/cross.png' width=20/></a>";
		}
		echo "<a onclick='return editar_inventario(".$row['idi'].", ".$_GET['id'].",".$_GET['c'].");'><img src='img/edit.png' width=20/></a>";
		echo "</td>"; //<a href='".$urlEliminar."'><img src='img/cross.png' width=20/></a></td>";
		echo "</tr>";
	} 
	echo "</tbody>";
	echo "</table>";
	
	include ('add_inventario.php');
?>