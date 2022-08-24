<?php
	$documentacion = (isset($_FILES['documentacion']['name']))? $_FILES['documentacion']['name'] : "";
	
	if(isset($_FILES['documentacion']['error']) and $_FILES['documentacion']['error']== 2){
		echo "<div class='alert alert-danger' role='alert'>Ha subido un fichero de más de 5 MB</div>";
	}else{
	
		if(isset($_POST['actionDoc']) and $_POST['actionDoc']=='add'){
			//Se recibe el formulario de documentacion para añadir
			if($_POST['fechaJusDoc']==""){
				$fechaJusDoc="NULL";
			}else{
				$fechaJusDoc="'".validarFecha($_POST['fechaJusDoc'])."'";
			}
			
			$insertar = "INSERT jp_documentacion (id_tipo_documento, observaciones, id_proyecto, subida) VALUES ('".$_POST['tipoDoc']."','".
						$_POST['observacionDOC']."','".$_GET['id']."', ".$fechaJusDoc.")";
			$resultado = $bd->ejecutar($insertar);
			//Si se ha insertado, muestra el listado
			if($bd->totalAfectados() != 1){						
				echo '<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el proyecto no se ha actualizado.</div>';
			}else{ 
			$lastID = $bd->lastID();
			$select = "SELECT codigo, descripcion FROM jp_tipo_documento WHERE id='".$_POST['tipoDoc']."'";
				$c = $bd->ejecutar($select);
				$row = $bd->obtener_fila($c, MYSQLI_ASSOC);
				
				
				$fecha_actual=getdate();
				
				$log="El usuario ".$_SESSION['user']." ha insertado la documentacion referida al checklist ".$row['codigo']."-".$row['descripcion']." con el numero ".$lastID.
				" en el proyecto con id ".$_GET['id']." el dia ".$fecha_actual['mday']." de ".$fecha_actual['month']." de ".$fecha_actual['year'].
				" a las ".$fecha_actual['hours'].":".$fecha_actual['minutes'].":".$fecha_actual['seconds']; 
				//Guardamos el registro con las modificaciones que ha hecho el usuario
				escribir($log);
				if ($documentacion!=""){
					$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
					if($objFtp != false){
						// Login
						if (!$objFtp ->login(SRV_USER, SRV_PASS)){
							$objFtp->disconnect();
							//exit(1);
						}
						else{
							//En el file_exists poner ruta relativa al script en el que se ejecuta
							if(file_exists("docs/".$_GET['c']."/".$_GET['id']."/d")==false){
								if(!$objFtp->mkdir(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/d")){							
									echo '<div class="alert alert-danger" role="alert">Error al crear el directorio de documentacion. Si el problema persiste,
									por favor, póngase en contacto con el Administrador</div>';	
								}
							}
					
							$documentacion=$lastID."_".$documentacion;
							$actualizar = "UPDATE jp_documentacion SET ruta ='".$documentacion. "'
							WHERE id ='".$lastID."'";
							$resultado = $bd->ejecutar($actualizar);
							
							
							if(isset($_FILES['documentacion']['name']) and $_FILES['documentacion']['name']!=""){
							
								$strData = file_get_contents($_FILES['documentacion']['tmp_name']);
								//Subimos el archivo al servidor
								if(!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/d/".$documentacion, $strData)){
									
									$error = 1;
									echo '<div class="alert alert-danger" role="alert">Error al subir la documentacion. Si el problema persiste, por favor, póngase
									en contacto con el Servicio de Informática (SIRIUS)</div>';											
									$objFtp->disconnect();
									//exit(1);
								}//if put adjudicacion
								
							}
						}
					}
				}
			}
		}else if(isset($_POST['actionDoc']) and $_POST['actionDoc']=='edit'){
			//Se recibe el formulario de edición de documentacion
			//insertar el código necesario para la subida de ficheros si es necesaria y guardar en una variable los set si existen fichero.
			//No olvidar borrar los ficheros antiguos.
			$sentencia="";
			if($documentacion =="" && isset($_POST['fileDoc'])){
				$documentacion= $_POST['fileDoc'];
			}else{
				if(isset($_FILES['documentacion']['name']) && $_FILES['documentacion']['name']!=""){
					
					$documentacion =$_POST['idDoc']."_".$_FILES['documentacion']['name'];
					$sentencia=" ruta='".$documentacion."', ";
				}
			}
			$fechaJusDoc="";
			$sentenciaFecha="subida=";
			if($_POST['fechaJusDoc']==""){
				$fechaJusDoc="NULL";
				$sentenciaFecha.=$fechaJusDoc;
				
			}else{
				$fechaJusDoc=$_POST['fechaJusDoc'];
				//echo 'el valor de la fecha es '.$fechaJusDoc;
				$sentenciaFecha.="'".validarFecha($fechaJusDoc)."'";
				
			}
					
			$actualizar = "UPDATE jp_documentacion SET ".$sentencia."observaciones='".$_POST['observacionDOC']."', id_tipo_documento='".$_POST['tipoDoc']."',
			".$sentenciaFecha." WHERE id = '".$_POST['idDoc']."'";
			$resultado = $bd->ejecutar($actualizar);
			if($bd->totalAfectados() == -1){						
				echo '<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y la modificacion no se ha actualizado.</div>';
			}else{
				
				$select = "SELECT codigo, descripcion FROM jp_tipo_documento WHERE id='".$_POST['tipoDoc']."'";
				$c = $bd->ejecutar($select);
				$row = $bd->obtener_fila($c, MYSQLI_ASSOC);
				
				
				$fecha_actual=getdate();
				
				$log="El usuario ".$_SESSION['user']." ha editado la documentacion referida al checklist ".$row['codigo']."-".$row['descripcion']." con el numero "
				.$_POST['idDoc']." en el proyecto con id ".$_GET['id']." el dia ".$fecha_actual['mday']." de ".$fecha_actual['month']." de ".$fecha_actual['year'].
				" a las ".$fecha_actual['hours'].":".$fecha_actual['minutes'].":".$fecha_actual['seconds'];  
				//Guardamos el registro con las modificaciones que ha hecho el usuario
				escribir($log);
				
				if(isset($_FILES['documentacion']['name']) and $_FILES['documentacion']['name']!=""){
					$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
					if($objFtp != false){
						if (!$objFtp ->login(SRV_USER, SRV_PASS)){
							$error = 1;
							echo '<div class="alert alert-danger" role="alert">Error en el login con el servidor. No se ha subido 
							la documentación. Por favor, póngase en contacto con el Administrador</div>';
							$objFtp->disconnect();
						}
						else{
							//En el file_exists poner ruta relativa al script en el que se ejecuta
							if(file_exists("docs/".$_GET['c']."/".$_GET['id']."/d")==false){
								if(!$objFtp->mkdir(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/d")){							
									echo '<div class="alert alert-danger" role="alert">Error al crear el directorio de documentacion. Si el problema persiste,
									por favor, póngase en contacto con el Administrador</div>';	
								}
							}
							$strData = file_get_contents($_FILES['documentacion']['tmp_name']);
							if (!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/d/".$documentacion, $strData)){
								$error = 1;
								echo '<div class="alert alert-danger" role="alert">Error al subir la documentacion. Si el problema persiste, por favor, póngase
								en contacto con el Servicio de Informática (SIRIUS)</div>';
								$objFtp->disconnect();
								//exit(1);
							}
							else{
								//elimino la antigua del server
								$objFtp->delete(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/d/".$_POST['fileDoc'], false);
							}//if put solicitud
								//}//is sesion convo != files
						}
						$objFtp->disconnect();
					}
				}
			}
		}
	}
?>