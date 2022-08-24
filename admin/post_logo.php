<?php
	$imagen = (isset($_FILES['imagenLogo']['name']))? $_FILES['imagenLogo']['name'] : "";
	
	if(isset($_POST['actionLog']) and $_POST['actionLog']=='add'){
		
		if($_POST['fechaIniLogo']==""){
			$fechaIniLogo="NULL";
		}else{
			$fechaIniLogo="'".validarFecha($_POST['fechaIniLogo'])."'";
		}
		
		if($_POST['fechaFinLogo']==""){
			$fechaFinLogo="NULL";
		}else{
			$fechaFinLogo="'".validarFecha($_POST['fechaFinLogo'])."'";
		}
		
		$insertar = "INSERT INTO jp_logo (nombre, fecha_inicio, fecha_fin) VALUES ('".$_POST['nombreLogo'].
		"',".$fechaIniLogo.",".$fechaFinLogo.")";
		
		$resultado = $bd->ejecutar($insertar);
		//Si se ha insertado, muestra el listado
		if($bd->totalAfectados() != 1){						
			echo '<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el proyecto no se ha actualizado.</div>';
		}else{
			
			$lastID = $bd->lastID();
			$imagen=$lastID."_".$imagen;
			$actualizar = "UPDATE jp_logo SET imagen ='".$imagen. "'
			WHERE id ='".$lastID."'";
			$resultado = $bd->ejecutar($actualizar);
			
			if(isset($_FILES['imagenLogo']['name']) and $_FILES['imagenLogo']['name']!=""){
				$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
				if($objFtp != false){
					if (!$objFtp ->login(SRV_USER, SRV_PASS)){
						$error = 1;
						echo '<div class="alert alert-danger" role="alert">Error en el login con el servidor. No se ha subido 
						la documentación. Por favor, póngase en contacto con el Administrador</div>';
						$objFtp->disconnect();
					}
					else{
						$strData = file_get_contents($_FILES['imagenLogo']['tmp_name']);
						//Subimos el archivo al servidor
						if(!$objFtp->put(SRV_DIR_UPLOAD."logos/".$imagen, $strData)){
							
							$error = 1;
							echo '<div class="alert alert-danger" role="alert">Error al subir la imagen. Si el problema persiste, por favor, póngase
							en contacto con el Servicio de Informática (SIRIUS)</div>';											
							$objFtp->disconnect();
							//exit(1);
						}//if put adjudicacion
					}
				}
			}
		
		}
	}else if(isset($_POST['actionLog']) and $_POST['actionLog']=='edit'){
	
		//Se recibe el formulario de edición del inventario
		//insertar el código necesario para la subida de ficheros si es necesaria y guardar en una variable los set si existen fichero.
		//No olvidar borrar los ficheros antiguos.
		$sentencia="";
		if($imagen =="" && isset($_POST['imgLog'])){
			$imagen= $_POST['imgLog'];
		}else{
			if(isset($_FILES['imagenLogo']['name'])){
				
				$imagen =$_POST['idLog']."_".$_FILES['imagenLogo']['name'];
				$sentencia=" imagen='".$imagen."', ";
			}
		}
		
		if($_POST['fechaIniLogo']==""){
			$fechaIniLogo="NULL";
		}else{
			$fechaIniLogo="'".validarFecha($_POST['fechaIniLogo'])."'";
		}
		
		if($_POST['fechaFinLogo']==""){
			$fechaFinLogo="NULL";
		}else{
			$fechaFinLogo="'".validarFecha($_POST['fechaFinLogo'])."'";
		}
		
		$actualizar = "UPDATE jp_logo SET ".$sentencia."nombre='".$_POST['nombreLogo']."', fecha_inicio=".($fechaIniLogo)
		.", fecha_fin=".($fechaFinLogo)." WHERE id = '".$_POST['idLog']."'";
		$resultado = $bd->ejecutar($actualizar);
		
		if($bd->totalAfectados() != 1){						
			echo '<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y la modificacion no se ha actualizado.</div>';
		}else{
			if(isset($_FILES['imagenLogo']['name'])){
				$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
				if($objFtp != false){
					if (!$objFtp ->login(SRV_USER, SRV_PASS)){
						$error = 1;
						echo '<div class="alert alert-danger" role="alert">Error en el login con el servidor. No se ha subido 
						la documentación. Por favor, póngase en contacto con el Administrador</div>';
						$objFtp->disconnect();
					}
					else{						
						$strData = file_get_contents($_FILES['imagenLogo']['tmp_name']);
						if (!$objFtp->put(SRV_DIR_UPLOAD."logos/".$imagen, $strData)){
							$error = 1;
							echo '<div class="alert alert-danger" role="alert">Error al subir la imagen. Si el problema persiste, por favor, póngase
							en contacto con el Servicio de Informática (SIRIUS)</div>';
							$objFtp->disconnect();
							//exit(1);
						}
						else{
							//elimino la antigua del server
							$objFtp->delete(SRV_DIR_UPLOAD."logos/".$_POST['imgLog'], false);
						}//if put solicitud
							//}//is sesion convo != files
					}
					$objFtp->disconnect();
				}
			}
		}
	}
?>