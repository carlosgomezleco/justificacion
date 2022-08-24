<?php
	$imagen = (isset($_FILES['imagen']['name']))? $_FILES['imagen']['name'] : "";
	
	if(isset($_FILES['imagen']['error']) and $_FILES['imagen']['error']== 2){
		echo "<div class='alert alert-danger' role='alert'>Ha subido un fichero de más de 5 MB</div>";
	}else{
	
		if(isset($_POST['actionInv']) and $_POST['actionInv']=='add'){
		
			if($_POST['fechaJusInv']==""){
				$fechaJusInv="NULL";
			}else{
				$fechaJusInv="'".validarFecha($_POST['fechaJusInv'])."'";
			}
			
			$insertar = "INSERT INTO jp_inventario (id_tipo_documento, observaciones, id_proyecto, subida) VALUES ('".$_POST['tipoInv'].
			"','".$_POST['observacionINV']."','".$_GET['id']."', ".$fechaJusInv.")";
			
			$resultado = $bd->ejecutar($insertar);
			//Si se ha insertado, muestra el listado
			if($bd->totalAfectados() != 1){						
				echo '<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el proyecto no se ha actualizado.</div>';
			}else{ 
				$select = "SELECT codigo, descripcion FROM jp_tipo_documento WHERE id='".$_POST['tipoInv']."'";
				$c = $bd->ejecutar($select);
				$row = $bd->obtener_fila($c, MYSQLI_ASSOC);
				
				
				$fecha_actual=getdate();
				$lastID = $bd->lastID();
				$log="El usuario ".$_SESSION['user']." ha insertado el inventario referido al checklist .".$row['codigo']."-".$row['descripcion']." con el numero ".$lastID.
				" en el proyecto con id ".$_GET['id']." el dia ".$fecha_actual['mday']." de ".
				$fecha_actual['month']." de ".$fecha_actual['year']." a las ".$fecha_actual['hours'].":".$fecha_actual['minutes'].":".$fecha_actual['seconds']; 
				//Guardamos el registro con las modificaciones que ha hecho el usuario
				escribir($log);
			if ($imagen!=""){
				$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
				if($objFtp != false){
					// Login
					if (!$objFtp ->login(SRV_USER, SRV_PASS)){
						$objFtp->disconnect();
						//exit(1);
					}
					else{
						//En el file_exists poner ruta relativa al script en el que se ejecuta
						if(file_exists("docs/".$_GET['c']."/".$_GET['id']."/i")==false){
							if(!$objFtp->mkdir(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/i")){							
								echo '<div class="alert alert-danger" role="alert">Error al crear el directorio de inventario. Si el problema persiste,
								por favor, póngase en contacto con el Administrador</div>';	
							}
						}
						
						$imagen=$lastID."_".$imagen;
						$actualizar = "UPDATE jp_inventario SET imagen ='".$imagen. "'
						WHERE id ='".$lastID."'";
						$resultado = $bd->ejecutar($actualizar);
						
						if(isset($_FILES['imagen']['name'])){
						
							$strData = file_get_contents($_FILES['imagen']['tmp_name']);
							//Subimos el archivo al servidor
							if(!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/i/".$imagen, $strData)){
								
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
			}
		}else if(isset($_POST['actionInv']) and $_POST['actionInv']=='edit'){
			//Se recibe el formulario de edición del inventario
			//insertar el código necesario para la subida de ficheros si es necesaria y guardar en una variable los set si existen fichero.
			//No olvidar borrar los ficheros antiguos.
			$sentencia="";
			if($imagen =="" && isset($_POST['imgInv'])){
				$imagen= $_POST['imgInv'];
			}else{
				if(isset($_FILES['imagen']['name']) and $_FILES['imagen']['name']!=""){
					
					$imagen =$_POST['idInv']."_".$_FILES['imagen']['name'];
					$sentencia=" imagen ='".$imagen."', ";
				}
			}
			
			if($_POST['fechaJusInv']==""){
				$fechaJusInv="NULL";
			}else{
				$fechaJusInv="'".validarFecha($_POST['fechaJusInv'])."'";
			}
			
			$actualizar = "UPDATE jp_inventario SET ".$sentencia."observaciones='".$_POST['observacionINV']."', id_tipo_documento='".$_POST['tipoInv']."',
			subida=".($fechaJusInv)." WHERE id = '".$_POST['idInv']."'";
			$resultado = $bd->ejecutar($actualizar);
			if($bd->totalAfectados() == -1){						
				echo '<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y la modificacion no se ha actualizado.</div>';
			}else{
				
				$select = "SELECT codigo, descripcion FROM jp_tipo_documento WHERE id='".$_POST['tipoInv']."'";
				$c = $bd->ejecutar($select);
				$row = $bd->obtener_fila($c, MYSQLI_ASSOC);
				
				
				$fecha_actual=getdate();
				
				$log="El usuario ".$_SESSION['user']." ha editado el inventario referido al checklist .".$row['codigo']."-".$row['descripcion']." con el numero ".$_POST['idInv']." en el proyecto con id ".$_GET['id']
				." el dia ".$fecha_actual['mday']." de ".
				$fecha_actual['month']." de ".$fecha_actual['year']." a las ".$fecha_actual['hours'].":".$fecha_actual['minutes'].":".$fecha_actual['seconds']; 
				//Guardamos el registro con las modificaciones que ha hecho el usuario
				escribir($log);
				
				if(isset($_FILES['imagen']['name'])and $_FILES['imagen']['name']!=""){
					$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
					if($objFtp != false){
						if (!$objFtp ->login(SRV_USER, SRV_PASS)){
							$error = 1;
							echo '<div class="alert alert-danger" role="alert">Error en el login con el servidor. No se ha subido 
							la imagen del inventario. Por favor, póngase en contacto con el Administrador</div>';
							$objFtp->disconnect();
						}
						else{
							//En el file_exists poner ruta relativa al script en el que se ejecuta
							if(file_exists("docs/".$_GET['c']."/".$_GET['id']."/i")==false){
								if(!$objFtp->mkdir(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/i")){							
									echo '<div class="alert alert-danger" role="alert">Error al crear el directorio de inventario. Si el problema persiste,
									por favor, póngase en contacto con el Administrador</div>';	
								}
							}
							$strData = file_get_contents($_FILES['imagen']['tmp_name']);
							if (!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/i/".$imagen, $strData)){
								$error = 1;
								echo '<div class="alert alert-danger" role="alert">Error al subir la imagen del inventario. Si el problema persiste, por favor, póngase
								en contacto con el Administrador</div>';
								$objFtp->disconnect();
								//exit(1);
							}
							else{
								//elimino la antigua del server
								$objFtp->delete(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/i/".$_POST['imgInv'], false);
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