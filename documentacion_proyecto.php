<?php
	//Si el usuario tiene los permisos 0=>'Administrador' se le muestra el boton para añadir en caso de que tenga permisos 0 =>'PAS' no se le muestra
	if($_SESSION['permisos']==0){
	echo '<div id="id3" class="col-lg-offset-1 col-lg-3"><button class="btn btn-default" onclick="AddDocumentacion();">Añadir Documentacion</button></div>';
	}
	echo '<div id="clear"></div>
		  <br/>';
	echo "<table border='0' id='TableDoc' class='stripe hover'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th width='10'><b>ID</b></th>";
	echo "<th width='40'><b>Tipo Documento</b></th>";
	echo "<th width='50'><b>Documento</b></th>";
	echo "<th width='20'><b>Justificacion</b></th>";
	echo "<th width='150'><b>Observaciones</b></th>";
	echo "<th width='30'></th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	
	if($bd==null){
		$bd = Db::getInstance();
	}
	$select = "SELECT D.id as id, TD.codigo as cod, TD.descripcion as des, D.ruta as ruta, D.observaciones as obs, 
	DATE_FORMAT(D.subida, '%d/%m/%Y') as sub
	FROM jp_documentacion D INNER JOIN jp_tipo_documento TD
	on (D.id_tipo_documento=TD.id) WHERE D.id_proyecto='".$_GET['id']."' ORDER BY TD.codigo ASC";
	
	$c = $bd->ejecutar($select);
	while($row = $bd->obtener_fila($c, MYSQLI_ASSOC)){
		$ruta = ($row['ruta'] != "")? "<a target='_blank' href='".SRV_DIR_DOWNLOAD.$_GET['c']."/".$_GET['id']."/d/".$row['ruta']."'>Descargar</a>" : "--"; ///MIRAR DONDE SE VAN A GUARDAR LAS COSAS
		
		/*$urlEliminar = "eliminar.php?id=".$row['id']."&resource=convocatoria&documents=";
				
		if($solicitud == "--" and $resolucion == "--")
			$urlEliminar .= "no";
		else
			$urlEliminar .= "yes";*/
				
		echo "<tr>";
		echo "<td width='10'>".$row['id']."</td>";
		echo "<td width='40'>".$row['cod']."-".$row['des']."</td>";
		echo "<td width='50'>".$ruta."</td>";
		echo "<td width='20'>".$row['sub']."</td>";
		echo "<td width='150'>".$row['obs']."</td>";
		echo "<td width='30' style='text-align: center;'>";
		//Si el usuario tiene los permisos 0=>'Administrador' se le permite eliminar en caso de que tenga permisos 0 =>'PAS' no se le permite
		if($_SESSION['permisos']==0){
		echo "<a onclick='return eliminar_documentacion(".$row['id'].", ".$_GET['id'].",".$_GET['c'].");'><img src='img/cross.png' width=20/></a>";
		}
		echo "<a onclick='return editar_documentacion(".$row['id'].", ".$_GET['id'].",".$_GET['c'].");'><img src='img/edit.png' width=20/></a></td>"; //<a href='".$urlEliminar."'><img src='img/cross.png' width=20/></a></td>";
		echo "</tr>";
	} 
	echo "</tbody>";
	echo "</table>";
	//$bd->desconectar();
	include ('add_documentacion.php');
?>