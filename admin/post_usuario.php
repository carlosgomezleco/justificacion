<?php
	if(isset($_POST['actionUsu']) and $_POST['actionUsu']=='add'){
	
		$insertar = "INSERT INTO jp_usuarios (usuario,permisos) VALUES ('".$_POST['nombreUsuario']."','".$_POST['permisos']."')";
		
		$resultado = $bd->ejecutar($insertar);
		//Si se ha insertado, muestra el listado
		if($bd->totalAfectados() != 1){						
			echo '<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el usuario no se creado.</div>';
		}
	}else if(isset($_POST['actionUsu']) and $_POST['actionUsu']=='edit'){
		//Se recibe el formulario de edición del usuario
		
		
		$actualizar = "UPDATE jp_usuarios SET usuario='".$_POST['nombreUsuario']."', permisos='".$_POST['permisos']."' 
		WHERE id = '".$_POST['idUsu']."'";
		$resultado = $bd->ejecutar($actualizar);
		if($bd->totalAfectados() != 1){						
			echo '<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el usuario no se ha actualizado.</div>';			
		}
	}
?>