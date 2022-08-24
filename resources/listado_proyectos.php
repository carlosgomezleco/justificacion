<?php
	echo "<table border='0' id='TableProy' class='stripe hover'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th><b>ID</b></th>";
	echo "<th><b>EXPEDIENTE</b></th>";
	echo "<th><b>REFERENCIA</b></th>";
	echo "<th><b>TÍTULO</b></th>";
	echo "<th></th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
			
	$bd = Db::getInstance();
	$select = "SELECT id_convocatoria as id_con,id,expediente,referencia,titulo, adjudicacion
	FROM jp_proyecto "; 
	$c = $bd->ejecutar($select);
	while($row = $bd->obtener_fila($c, MYSQLI_ASSOC)){
		$urlEliminar = "eliminar.php?id=".$row['id']."&idc=".$row['id_con']."&resource=proyecto";
				
		echo "<tr>";
		echo "<td>".$row['id']."</td>";
		echo "<td>".$row['expediente']."</td>";
		echo "<td>".$row['referencia']."</td>";
		echo "<td>".$row['titulo']."</td>";
		echo "<td>";
		//echo "<a href='info.php?id=".$row['id']."&c=".$row['id_con']."'><img src='img/lupa.png' width=20/></a>";
		echo "<a href='editar.php?id=".$row['id']."&c=".$row['id_con']."'><img src='img/edit.png' width=20/></a>";
		if($_SESSION['permisos']==0){
		echo "<a onclick='return preguntar(".$row['id'].",".$row['id_con'].");'><img src='img/cross.png' width=20/></a>";
		}
		echo "</td>";
		echo "</tr>";
	} 
	echo "</tbody>";
	echo "</table>";
	//$bd->desconectar();
?>