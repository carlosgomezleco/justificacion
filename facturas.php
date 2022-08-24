<?php
	//Si el usuario tiene los permisos 0=>'Administrador' se le muestra el boton para añadir en caso de que tenga permisos 0 =>'PAS' no se le muestra
	if($_SESSION['permisos']==0){
	echo '<div id="id3" class="col-lg-offset-1 col-lg-3"><button class="btn btn-default" onclick="AddFactura();">Añadir Factura</button></div>';
	}
	echo '<div id="clear"></div>
		  <br/>';
	echo "<table border='0' id='TableF' class='stripe hover'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th width='10'><b>ID</b></th>";
	echo "<th width='50'><b>Tipo Doc</b></th>";
	echo "<th width='40'><b>Factura</b></th>";
	echo "<th width='40'><b>Pago</b></th>";
	echo "<th width='90'><b>Justificacion</b></th>";
	echo "<th width='40'><b>Observaciones</b></th>";
	echo "<th width='30'></th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	
	$bd = Db::getInstance();
	$select = "SELECT f.id as idf, td.codigo as cod, td.descripcion as des, f.factura as fac, f.acreditacion_pago as acp, f.observaciones as obs, 
	DATE_FORMAT(f.subida, '%d/%m/%Y') as sub FROM jp_facturacion f INNER JOIN 
	jp_tipo_documento td on (id_tipo_documento=td.id) WHERE id_proyecto='".$_GET['id']."' ORDER BY idf DESC";
	
	$c = $bd->ejecutar($select);
	while($row = $bd->obtener_fila($c, MYSQLI_ASSOC)){
		$factura = ($row['fac'] != "")? "<a target='_blank' href='".SRV_DIR_DOWNLOAD.$_GET['c']."/".$_GET['id']."/f/".$row['fac']."'>Descargar</a>" : "--";
		$pago = ($row['acp'] != "")? "<a target='_blank' href='".SRV_DIR_DOWNLOAD.$_GET['c']."/".$_GET['id']."/f/".$row['acp']."'>Descargar</a>" : "--";
		/*$urlEliminar = "eliminar.php?id=".$row['id']."&resource=convocatoria&documents=";
				
		if($solicitud == "--" and $resolucion == "--")
			$urlEliminar .= "no";
		else
			$urlEliminar .= "yes";*/
				
		echo "<tr>";
		echo "<td width='10'>".$row['idf']."</td>";
		echo "<td width='50'>".$row['cod']."-".$row['des']."</td>";
		echo "<td width='40'>".$factura."</td>";
		echo "<td width='40'>".$pago."</td>";
		echo "<td width='90'>".$row['sub']."</td>";
		echo "<td width='40'>".$row['obs']."</td>";
		echo "<td width='30' style='text-align: center;'>";
		//Si el usuario tiene los permisos 0=>'Administrador' se le permite eliminar en caso de que tenga permisos 0 =>'PAS' no se le permite
		if($_SESSION['permisos']==0){
		echo "<a onclick='return eliminar_factura(".$row['idf'].", ".$_GET['id'].",".$_GET['c'].");'><img src='img/cross.png' width=20/></a>";
		}
		echo "<a onclick='return editar_factura(".$row['idf'].", ".$_GET['id'].",".$_GET['c'].");'><img src='img/edit.png' width=20/></a>";
		echo "</td>"; //<a href='".$urlEliminar."'><img src='img/cross.png' width=20/></a></td>";
		echo "</tr>";
	} 
	echo "</tbody>";
	echo "</table>";
	
	include ('add_factura.php');
?>