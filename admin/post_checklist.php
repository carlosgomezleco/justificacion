<?php
	if(isset($_POST['actionChe']) and $_POST['actionChe']=='add'){
	
		$insertar = "INSERT INTO jp_tipo_documento (tipo, codigo, descripcion, organismo) VALUES ('".$_POST['tipoChecklist'].
		"','".$_POST['codigoChecklist']."','".$_POST['descripcionChecklist']."','".$_POST['organismoChecklist']."')";
		
		$resultado = $bd->ejecutar($insertar);
		//Si se ha insertado, muestra el listado
		if($bd->totalAfectados() != 1){						
			echo '<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el tipo de documento no se creado.</div>';
		}
	}else if(isset($_POST['actionChe']) and $_POST['actionChe']=='edit'){
		//Se recibe el formulario de edición del inventario
		//insertar el código necesario para la subida de ficheros si es necesaria y guardar en una variable los set si existen fichero.
		//No olvidar borrar los ficheros antiguos.
		
		$actualizar = "UPDATE jp_tipo_documento SET tipo='".$_POST['tipoChecklist']."', codigo='".$_POST['codigoChecklist']
		."', descripcion='".$_POST['descripcionChecklist']."', organismo='".$_POST['organismoChecklist']."' WHERE id = '".$_POST['idChe']."'";
		$resultado = $bd->ejecutar($actualizar);
		if($bd->totalAfectados() != 1){						
			echo '<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el tipo de documento no se ha actualizado.</div>';			
		}
	}
?>