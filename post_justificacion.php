<?php
	$documento = (isset($_FILES['documentacionJus']['name']))? $_FILES['documentacionJus']['name'] : "";
	
	if(isset($_FILES['documentacionJus']['error']) and $_FILES['documentacionJus']['error']== 2){
		echo "<div class='alert alert-danger' role='alert'>Ha subido un fichero de más de 5 MB</div>";
	}else{
	
		if(isset($_POST['actionJus']) and $_POST['actionJus']=='add'){
			//Se recibe el formulario de documentacion para añadir	
			
			if($_POST['fechaJus']==""){
				$fechaJus="NULL";
			}else{
				$fechaJus="'".validarFecha($_POST['fechaJus'])."'";
			}
			
			$insertar = "INSERT INTO jp_justificacion_documental (fecha, n_solicitud, observaciones, id_proyecto) VALUES (".
			$fechaJus.",'".$_POST['n_solicitud']."','".$_POST['observacionJUS']."','".$_GET['id']."')";
			
			$resultado = $bd->ejecutar($insertar);
			
			$lastID = $bd->lastID();
			if($documento!=""){
				$documento=$lastID."_D_".$documento;
			}
			
			//Si se ha insertado, muestra el listado
			if($bd->totalAfectados() != 1){						
				echo '<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el proyecto no se ha actualizado.</div>';
			}else if(isset($_FILES['documentacionJus']['name']) and ($_FILES['documentacionJus']['name'])!=""){
				$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
				if($objFtp != false){
					// Login
					if (!$objFtp ->login(SRV_USER, SRV_PASS)){
						$objFtp->disconnect();
						//exit(1);
					}
					else{ 
						//En el file_exists poner ruta relativa al script en el que se ejecuta
						if(file_exists("docs/".$_GET['c']."/".$_GET['id']."/j")==false){
							if(!$objFtp->mkdir(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/j")){							
								echo '<div class="alert alert-danger" role="alert">Error al crear el directorio de justificacion. Si el problema persiste,
								por favor, póngase en contacto con el Administrador</div>';	
							}
						}
						$actualizar = "UPDATE jp_justificacion_documental SET documento ='".$documento. "' WHERE id ='".$lastID."'";
						$resultado = $bd->ejecutar($actualizar);
						$strData = file_get_contents($_FILES['documentacionJus']['tmp_name']);
						//Subimos el archivo al servidor
						if(!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/j/".$documento, $strData)){
							
							$error = 1;
							echo '<div class="alert alert-danger" role="alert">Error al subir la documentacion. Si el problema persiste, por favor, póngase
							en contacto con el Servicio de Informática (SIRIUS)</div>';											
							$objFtp->disconnect();
							//exit(1);
						}//if put adjudicacion
					}
				}
			}
		
		}else if(isset($_POST['actionJus']) and $_POST['actionJus']=='edit'){
			//Se recibe el formulario de edición de justificacion
			$sentencia="";
			
			if($documento =="" && isset($_POST['docJus'])){
				$documento = $_POST['docJus'];
			}else{
				if(isset($_FILES['documentacionJus']['name'])){
					
					$documento=$_POST['idJus']."_D_".$_FILES['documentacionJus']['name'];
					$sentencia=" documento='".$documento."', ";
				}
			}
			
			if($_POST['fechaJus']==""){
				$fechaJus="NULL";
			}else{
				$fechaJus="'".validarFecha($_POST['fechaJus'])."'";
			}
			
			$actualizar = "UPDATE jp_justificacion_documental SET ".$sentencia." fecha=".$fechaJus.", 
			n_solicitud='".$_POST['n_solicitud']."', observaciones='".$_POST['observacionJUS']."' WHERE id = '".$_POST['idJus']."'";
			$resultado = $bd->ejecutar($actualizar);
			if($bd->totalAfectados() == -1){						
				echo '<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y la justificacion no se ha actualizado.</div>';
			}else{
				if(isset($_FILES['documentacionJus']['name']) and $_FILES['documentacionJus']['name']!=""){
					$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
					if($objFtp != false){
						if (!$objFtp ->login(SRV_USER, SRV_PASS)){
							$error = 1;
							echo '<div class="alert alert-danger" role="alert">Error en el login con el servidor. No se ha subido 
							la documentación de la justificacion. Por favor, póngase en contacto con el Administrador</div>';
							$objFtp->disconnect();
						}
						else{
							//En el file_exists poner ruta relativa al script en el que se ejecuta
							if(file_exists("docs/".$_GET['c']."/".$_GET['id']."/j")==false){
								if(!$objFtp->mkdir(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/j")){							
									echo '<div class="alert alert-danger" role="alert">Error al crear el directorio de justificacion. Si el problema persiste,
									por favor, póngase en contacto con el Administrador</div>';	
								}
							}
							$strData = file_get_contents($_FILES['documentacionJus']['tmp_name']);
							if (!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/j/".$documento, $strData)){
								$error = 1;
								echo '<div class="alert alert-danger" role="alert">Error al subir la documentacion de justificacion. Si el problema persiste, por favor, póngase
								en contacto con el Servicio de Informática (SIRIUS)</div>';
								$objFtp->disconnect();
								//exit(1);
							}
							else{
								//elimino la antigua del server
								$objFtp->delete(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/j/".$_POST['docJus'], false);
							}						
							$objFtp->disconnect();
						}
									
					}
				}
			}
		}
	}
?>