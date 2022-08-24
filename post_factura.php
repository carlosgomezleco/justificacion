<?php
	$factura = (isset($_FILES['factura']['name']))? $_FILES['factura']['name'] : "";
	$acreditacion_pago = (isset($_FILES['acreditacion_pago']['name']))? $_FILES['acreditacion_pago']['name'] : "";
	$peso=0;
	if(isset($_FILES['factura']['size'])){
		$peso+=$_FILES['factura']['size'];
	}
	
	if(isset($_FILES['acreditacion_pago']['size'])){
		$peso+=$_FILES['acreditacion_pago']['size'];
	}
	
	if($peso>5242880){
		echo '<div style="width:94%;" class="alerta2 alert alert-danger">Los ficheros que está intentando subir suman un peso mayor de 5MB</div>';
	}else{
		
		if(isset($_POST['actionFac']) and $_POST['actionFac']=='add'){
			
			if($_POST['fechaJusFac']==""){
				$fechaJusFac="NULL";
			}else{
				$fechaJusFac="'".validarFecha($_POST['fechaJusFac'])."'";
			}
			
			//Se recibe el formulario de documentacion para añadir	
			$insertar = "INSERT INTO jp_facturacion (id_tipo_documento, observaciones, id_proyecto, subida ) VALUES ('".$_POST['tipoFac'].
						"','".$_POST['observacionFAC']."','".$_GET['id']."', ".$fechaJusFac.")";
			
			$resultado = $bd->ejecutar($insertar);
			
			$lastID = $bd->lastID();
			if($factura!=""){
				$factura=$lastID."_F_".$factura;
			}
			if($acreditacion_pago!=""){
				$acreditacion_pago=$lastID."_P_".$acreditacion_pago;
			}
			
			//Si se ha insertado, muestra el listado
			if($bd->totalAfectados() != 1){						
				echo '<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el proyecto no se ha actualizado.</div>';
			}else{
			
				$select = "SELECT codigo, descripcion FROM jp_tipo_documento WHERE id='".$_POST['tipoFac']."'";
				$c = $bd->ejecutar($select);
				$row = $bd->obtener_fila($c, MYSQLI_ASSOC);
				
				
				$fecha_actual=getdate();
				
				$log="El usuario ".$_SESSION['user']." ha insertado la factura referid al checklist .".$row['codigo']."-".$row['descripcion']." con el numero ".$lastID
				." en el proyecto con id ".$_GET['id']." el dia ".$fecha_actual['mday']." de ".
				$fecha_actual['month']." de ".$fecha_actual['year']." a las ".$fecha_actual['hours'].":".$fecha_actual['minutes'].":".$fecha_actual['seconds']; 
				//Guardamos el registro con las modificaciones que ha hecho el usuario
				escribir($log);
			
			if($factura!="" or $acreditacion_pago!=""){
				$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
				if($objFtp != false){
					// Login
					if (!$objFtp ->login(SRV_USER, SRV_PASS)){
						$objFtp->disconnect();
						//exit(1);
					}
					else{
						//En el file_exists poner ruta relativa al script en el que se ejecuta
						if(file_exists("docs/".$_GET['c']."/".$_GET['id']."/f")==false){
							if(!$objFtp->mkdir(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/f")){							
								echo '<div class="alert alert-danger" role="alert">Error al crear el directorio de facturacion. Si el problema persiste,
								por favor, póngase en contacto con el Administrador</div>';	
							}
						}
						$actualizar = "UPDATE jp_facturacion SET factura ='".$factura. "', acreditacion_pago ='".$acreditacion_pago. "'
						WHERE id ='".$lastID."'";
						$resultado = $bd->ejecutar($actualizar);
						
						if(isset($_FILES['factura']['name']) and $_FILES['factura']['name']!=""){
						
							$strData = file_get_contents($_FILES['factura']['tmp_name']);
							//Subimos el archivo al servidor
							if(!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/f/".$factura, $strData)){
								
								$error = 1;
								echo '<div class="alert alert-danger" role="alert">Error al subir la factura. Si el problema persiste, por favor, póngase
								en contacto con el Servicio de Informática (SIRIUS)</div>';											
								$objFtp->disconnect();
								//exit(1);
							}//if put adjudicacion
							
						}
						
						if(isset($_FILES['acreditacion_pago']['name']) and $_FILES['acreditacion_pago']['name']!=""){
						
							$strData = file_get_contents($_FILES['acreditacion_pago']['tmp_name']);
							//Subimos el archivo al servidor
							if(!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/f/".$acreditacion_pago, $strData)){
								
								$error = 1;
								echo '<div class="alert alert-danger" role="alert">Error al subir la acreditacion de pago. Si el problema persiste, por favor, póngase
								en contacto con el Servicio de Informática (SIRIUS)</div>';											
								$objFtp->disconnect();
								//exit(1);
							}//if put adjudicacion
							
						}
					}
				}
			}
			}
		
		}else if(isset($_POST['actionFac']) and $_POST['actionFac']=='edit'){
			//Se recibe el formulario de edición de factura
			//insertar el código necesario para la subida de ficheros si es necesaria y guardar en una variable los set si existen fichero.
			//No olvidar borrar los ficheros antiguos.
			$sentencia="";
			
			if($factura =="" && isset($_POST['facFac'])){
				$factura = $_POST['facFac'];
			}else{
				if(isset($_FILES['factura']['name']) && $_FILES['factura']['name']!="" ){
					
					$factura=$_POST['idFac']."_F_".$_FILES['factura']['name'];
					$sentencia=" factura='".$factura."', ";
				}
			}
			
			if($acreditacion_pago=="" && isset($_POST['acpFac'])){
				$acreditacion_pago= $_POST['acpFac'];
			}else{
				if(isset($_FILES['acreditacion_pago']['name']) && $_FILES['acreditacion_pago']['name']!=""){
					
					$acreditacion_pago=$_POST['idFac']."_P_".$_FILES['acreditacion_pago']['name'];
					$sentencia.="acreditacion_pago='".$acreditacion_pago."', ";
				}
			}
			
			if($_POST['fechaJusFac']==""){
				$fechaJusFac="NULL";
			}else{
				$fechaJusFac="'".validarFecha($_POST['fechaJusFac'])."'";
			}
			
			$actualizar = "UPDATE jp_facturacion SET ".$sentencia." observaciones='".$_POST['observacionFAC']."', id_tipo_documento='".$_POST['tipoFac']."',
			subida=".($fechaJusFac)." WHERE id = '".$_POST['idFac']."'";
			
			$resultado = $bd->ejecutar($actualizar);
			if($bd->totalAfectados() == -1){						
				echo '<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y la factura no se ha actualizado.</div>';
			}else{
				
				$select = "SELECT codigo, descripcion FROM jp_tipo_documento WHERE id='".$_POST['tipoFac']."'";
				$c = $bd->ejecutar($select);
				$row = $bd->obtener_fila($c, MYSQLI_ASSOC);
				
				
				$fecha_actual=getdate();
				
				$log="El usuario ".$_SESSION['user']." ha editado la factura referida al checklist ".$row['codigo']."-".$row['descripcion']." con el numero ".$_POST['idFac']
				." en el proyecto con id ".$_GET['id']." el dia ".$fecha_actual['mday']." de ".
				$fecha_actual['month']." de ".$fecha_actual['year']." a las ".$fecha_actual['hours'].":".$fecha_actual['minutes'].":".$fecha_actual['seconds']; 
				//Guardamos el registro con las modificaciones que ha hecho el usuario
				escribir($log);
			
				if((isset($_FILES['factura']['name']) and $_FILES['factura']['name']!="") or ( isset ($_FILES['acreditacion_pago']['name']) and $_FILES['acreditacion_pago']['name']!="")){
					$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
					if($objFtp != false){
						if (!$objFtp ->login(SRV_USER, SRV_PASS)){
							$error = 1;
							echo '<div class="alert alert-danger" role="alert">Error en el login con el servidor. No se ha subido 
							la documentación de la facturacion. Por favor, póngase en contacto con el Administrador</div>';
							$objFtp->disconnect();
						}
						else{
							//En el file_exists poner ruta relativa al script en el que se ejecuta
							if(file_exists("docs/".$_GET['c']."/".$_GET['id']."/f")==false){
								if(!$objFtp->mkdir(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/f")){							
									echo '<div class="alert alert-danger" role="alert">Error al crear el directorio de facturacion. Si el problema persiste,
									por favor, póngase en contacto con el Administrador</div>';	
								}
							}else{
								
								if(isset($_FILES['factura']['name']) and $_FILES['factura']['name']!=""){
														
									//if(($_SESSION['convo'] != $_FILES['solicitud']['name'])){												
										$strData = file_get_contents($_FILES['factura']['tmp_name']);
										if (!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/f/".$factura, $strData)){
											$error = 1;
											echo '<div class="alert alert-danger" role="alert">Error al subir la factura. Si el problema persiste, por favor, póngase
											en contacto con el Servicio de Informática (SIRIUS)</div>';
											$objFtp->disconnect();
											//exit(1);
										}
										else{
											//elimino la antigua del server
											$objFtp->delete(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/f/".$_POST['facFac'], false);
										}//if put solicitud
									//}//is sesion convo != files
								}
								if(isset($_FILES['acreditacion_pago']['name']) and $_FILES['acreditacion_pago']['name']!=""){
														
									//if(($_SESSION['convo'] != $_FILES['acreditacion_pago']['name'])){												
										$strData = file_get_contents($_FILES['acreditacion_pago']['tmp_name']);
										if (!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/f/".$acreditacion_pago, $strData)){
											$error = 1;
											echo '<div class="alert alert-danger" role="alert">Error al subir la acreditacion de pago. Si el problema persiste, por favor, póngase
											en contacto con el Servicio de Informática (SIRIUS)</div>';
											$objFtp->disconnect();
											//exit(1);
										}
										else{
											//elimino la antigua del server
											$objFtp->delete(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/f/".$_POST['acpFac'], false);
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
	}
?>