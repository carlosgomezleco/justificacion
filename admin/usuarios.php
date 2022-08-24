<?php
	echo '<div id="id3" class="col-lg-offset-1 col-lg-3"><button class="btn btn-default" onclick="AddUsuario();">Añadir Usuario</button></div>
		  <div id="clear"></div>
		  <br/>';
	echo "<table border='0' id='TableUsu' class='stripe hover'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th width='30'><b>ID</b></th>";
	echo "<th width='140'><b>Usuario</b></th>";
	echo "<th width='100'><b>Permisos</b></th>";
	echo "<th width='30'></th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	
	if(!isset($bd) && $bd==null){
		$bd = Db::getInstance();
	}
	$select = "SELECT id, usuario, case permisos WHEN 0 then 'Administrador' else 'Personal Administracion Servicios' end as permisosUsu
	FROM jp_usuarios ORDER BY id ASC";
	
	$c = $bd->ejecutar($select);
	while($row = $bd->obtener_fila($c, MYSQLI_ASSOC)){
		//$ruta = ($row['ruta'] != "")? "<a href='\\".SRV_DIR_DOWNLOAD.$_GET['c']."\\".$_GET['id']."\d\\".$row['ruta']."'>Descargar</a>" : "--"; ///MIRAR DONDE SE VAN A GUARDAR LAS COSAS
		
						
		echo "<tr>";
		echo "<td width='30'>".$row['id']."</td>";
		echo "<td width='140'>".$row['usuario']."</td>";
		echo "<td width='100'>".$row['permisosUsu']."</td>";
		echo "<td width='30' style='text-align: center;'><a onclick='return eliminar_Usuario(".$row['id'].");'><img src='img/cross.png' width=20/></a><a onclick='return editar_Usuario(".$row['id'].");'><img src='img/edit.png' width=20/></a></td>"; //<a href='".$urlEliminar."'><img src='img/cross.png' width=20/></a></td>";
		echo "</tr>";
	} 
	echo "</tbody>";
	echo "</table>";
	//$bd->desconectar();
	include ('add_usuario.php');
?>