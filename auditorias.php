<?php
	//Tabla con las auditorias
	echo '<div id="id3" class="col-lg-offset-1 col-lg-3"><button class="btn btn-default" onclick="AddAuditoria();">Añadir Auditoria</button></div>
		  <div id="clear"></div>
		  <br/>';
	echo "<table border='0' id='TableA' class='stripe hover'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th width='10'><b>ID</b></th>";
	echo "<th width='50'><b>Auditor</b></th>";
	echo "<th width='50'><b>Documentos Aportados</b></th>";
	echo "<th width='50'><b>Informe Final</b></th>";
	echo "<th width='120'><b>Observaciones</b></th>";
	echo "<th width='20'></th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	
	$bd = Db::getInstance();
	
	$select = "SELECT *  FROM jp_auditoria WHERE id_proyecto='".$_GET['id']."' ORDER BY id DESC";
	$c = $bd->ejecutar($select);
	while($row = $bd->obtener_fila($c, MYSQLI_ASSOC)){
		$auditor = ($row['auditor'] != "")? "<a target='_blank' href='".SRV_DIR_DOWNLOAD.$_GET['c']."/".$_GET['id']."/a/".$row['auditor']."'>Descargar</a>" : "--";
		$doc_aportados = ($row['doc_aportados'] != "")? "<a target='_blank' href='".SRV_DIR_DOWNLOAD.$_GET['c']."/".$_GET['id']."/a/".$row['doc_aportados']."'>Descargar</a>" : "--";
		$inf_final = ($row['inf_final'] != "")? "<a target='_blank' href='".SRV_DIR_DOWNLOAD.$_GET['c']."/".$_GET['id']."/a/".$row['inf_final']."'>Descargar</a>" : "--";
						
		echo "<tr>";
		echo "<td width='10'>".$row['id']."</td>";
		echo "<td width='50'>".$auditor."</td>";
		echo "<td width='50'>".$doc_aportados."</td>";
		echo "<td width='50'>".$inf_final."</td>";
		echo "<td width='120'>".$row['observaciones']."</td>";
		echo "<td width='20' style='text-align: center;'>";
		echo "<a onclick='return eliminar_auditoria(".$row['id'].", ".$_GET['id'].",".$_GET['c'].");'><img src='img/cross.png' width=20/></a>";
		echo "<a onclick='return editar_auditoria(".$row['id'].", ".$_GET['id'].",".$_GET['c'].");'><img src='img/edit.png' width=20/></a>";
		//<a href='convocatoria.php?id=".$row['id']."'><img src='img/edit.png' width=20/></a>
		echo "</td>"; //<a href='".$urlEliminar."'><img src='img/cross.png' width=20/></a></td>";
		echo "</tr>";
	} 
	echo "</tbody>";
	echo "</table>";
	include ('add_auditoria.php');
	//$bd->desconectar();
?>