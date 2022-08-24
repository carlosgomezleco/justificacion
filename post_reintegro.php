<?php
	$solicitud = (isset($_FILES['solicitudRei']['name']))? $_FILES['solicitudRei']['name'] : "";
	$pago = (isset($_FILES['pagoRei']['name']))? $_FILES['pagoRei']['name'] : "";
	$peso=0;
	if(isset($_FILES['solicitudRei']['size'])){
		$peso+=$_FILES['solicitudRei']['size'];
	}
	
	if(isset($_FILES['pagoRei']['size'])){
		$peso+=$_FILES['pagoRei']['size'];
	}
	
	if($peso>5242880){
		echo '<div style="width:94%;" class="alerta2 alert alert-danger">Los ficheros que está intentando subir suman un peso mayor de 5MB</div>';
	}
	else{
		if(isset($_POST['actionRei']) and $_POST['actionRei']=='add'){
			//Se recibe el formulario de documentacion para añadir	
			$insertar = "INSERT INTO jp_reintegro (observaciones, id_proyecto) VALUES ('".$_POST['observacionREI']."','".$_GET['id']."')";
			
			$resultado = $bd->ejecutar($insertar);
			
			$lastID = $bd->lastID();
			
			
			//Si se ha insertado, muestra el listado
			if($bd->totalAfectados() < 0){						
				echo '<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el proyecto no se ha actualizado.</div>';
			}else if($solicitud!="" or $pago!= ""){
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
						if($solicitud!=""){
							$solicitud=$lastID."_S_".$solicitud;
						}
						if($pago!= ""){
							$pago=$lastID."_P_".$pago;
						}
						
						$actualizar = "UPDATE jp_reintegro SET solicitud ='".$solicitud. "', pago ='".$pago. "'
						WHERE id ='".$lastID."'";
						$resultado = $bd->ejecutar($actualizar);
						
						if(isset($_FILES['solicitudRei']['name']) and $_FILES['solicitudRei']['name']!=""){
						
							$strData = file_get_contents($_FILES['solicitudRei']['tmp_name']);
							//Subimos el archivo al servidor
							if(!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/j/".$solicitud, $strData)){
								
								$error = 1;
								echo '<div class="alert alert-danger" role="alert">Error al subir la solicitud. Si el problema persiste, por favor, póngase
								en contacto con el Servicio de Informática (SIRIUS)</div>';											
								$objFtp->disconnect();
								//exit(1);
							}//if put adjudicacion
							
						}
						
						if(isset($_FILES['pagoRei']['name']) and $_FILES['pagoRei']['name']!=""){
						
							$strData = file_get_contents($_FILES['pagoRei']['tmp_name']);
							//Subimos el archivo al servidor
							if(!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/j/".$pago, $strData)){
								
								$error = 1;
								echo '<div class="alert alert-danger" role="alert">Error al subir el pago. Si el problema persiste, por favor, póngase
								en contacto con el Servicio de Informática (SIRIUS)</div>';											
								$objFtp->disconnect();
								//exit(1);
							}//if put adjudicacion
							
						}
					}
				}
			}
		
		}else if(isset($_POST['actionRei']) and $_POST['actionRei']=='edit'){
			//Se recibe el formulario de edición de factura
			//insertar el código necesario para la subida de ficheros si es necesaria y guardar en una variable los set si existen fichero.
			//No olvidar borrar los ficheros antiguos.
			$sentencia="";
			
			if($solicitud =="" && isset($_POST['solRei'])){
				$solicitud = $_POST['solRei'];
			}else{
				if(isset($_FILES['solicitudRei']['name'])){
					
					$solicitud=$_POST['idRei']."_S_".$_FILES['solicitudRei']['name'];
					$sentencia=" solicitud='".$solicitud."', ";
				}
			}
			
			if($pago=="" && isset($_POST['pagRei'])){
				$pago= $_POST['pagRei'];
			}else{
				if(isset($_FILES['pagoRei']['name'])){
					
					$pago=$_POST['idRei']."_P_".$_FILES['pagoRei']['name'];
					$sentencia.="pago='".$pago."', ";
				}
			}
			$actualizar = "UPDATE jp_reintegro SET ".$sentencia." observaciones='".$_POST['observacionREI']."' WHERE id = '".$_POST['idRei']."'";
			$resultado = $bd->ejecutar($actualizar);
			if($bd->totalAfectados() == -1){						
				echo '<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y la modificacion no se ha actualizado.</div>';
			}else{
				if(isset($_FILES['solicitudRei']['name']) or isset ($_FILES['pagoRei']['name']) ){
					$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
					if($objFtp != false){
						if (!$objFtp ->login(SRV_USER, SRV_PASS)){
							$error = 1;
							echo '<div class="alert alert-danger" role="alert">Error en el login con el servidor. No se ha subido 
							la documentación de la modificacion. Por favor, póngase en contacto con el Administrador</div>';
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
							if(isset($_FILES['solicitudRei']['name']) and $_FILES['solicitudRei']['name']!=""){
													
								//if(($_SESSION['convo'] != $_FILES['solicitud']['name'])){												
									$strData = file_get_contents($_FILES['solicitudRei']['tmp_name']);
									if (!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/j/".$solicitud, $strData)){
										$error = 1;
										echo '<div class="alert alert-danger" role="alert">Error al subir la factura. Si el problema persiste, por favor, póngase
										en contacto con el Servicio de Informática (SIRIUS)</div>';
										$objFtp->disconnect();
										//exit(1);
									}
									else{
										//elimino la antigua del server
										$objFtp->delete(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/j/".$_POST['solRei'], false);
									}//if put solicitud
								//}//is sesion convo != files
							}
							if(isset($_FILES['pagoRei']['name']) and $_FILES['pagoRei']['name']!=""){
													
								//if(($_SESSION['convo'] != $_FILES['acreditacion_pago']['name'])){												
									$strData = file_get_contents($_FILES['pagoRei']['tmp_name']);
									if (!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/j/".$pago, $strData)){
										$error = 1;
										echo '<div class="alert alert-danger" role="alert">Error al subir la acreditacion de pago. Si el problema persiste, por favor, póngase
										en contacto con el Servicio de Informática (SIRIUS)</div>';
										$objFtp->disconnect();
										//exit(1);
									}
									else{
										//elimino la antigua del server
										$objFtp->delete(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/j/".$_POST['pagRei'], false);
									}//if put acreditacion_pago
								//}//is sesion convo != files
							}
							$objFtp->disconnect();
						}				
					}
				}
			}
		}
	}
?>