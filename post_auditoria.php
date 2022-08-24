<?php
	$auditor = (isset($_FILES['auditor']['name']))? $_FILES['auditor']['name'] : "";
	$doc_aportados = (isset($_FILES['doc_aportados']['name']))? $_FILES['doc_aportados']['name'] : "";
	$inf_final = (isset($_FILES['inf_final']['name']))? $_FILES['inf_final']['name'] : "";
	
	$peso=0;
	if(isset($_FILES['auditor']['size'])){
		$peso+=$_FILES['auditor']['size'];
	}
	
	if(isset($_FILES['doc_aportados']['size'])){
		$peso+=$_FILES['doc_aportados']['size'];
	}
	
	if(isset($_FILES['inf_final']['size'])){
		$peso+=$_FILES['inf_final']['size'];
	}
	
	if($peso>5242880){
		echo '<div style="width:94%;" class="alerta2 alert alert-danger">Los ficheros que está intentando subir suman un peso mayor de 5MB</div>';
	}
	else{
	
		if(isset($_POST['actionAud']) and $_POST['actionAud']=='add'){
			//Se recibe el formulario de documentacion para añadir	
			$insertar = "INSERT INTO jp_auditoria (observaciones, id_proyecto) VALUES ('".$_POST['observacionAUD']."','".$_GET['id']."')";
			
			$resultado = $bd->ejecutar($insertar);
			
			$lastID = $bd->lastID();
			$auditor=$lastID."_A_".$auditor;
			$doc_aportados=$lastID."_D_".$doc_aportados;
			$inf_final=$lastID."_I_".$inf_final;
			
			//Si se ha insertado, muestra el listado
			if($bd->totalAfectados() != 1){						
				echo '<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el proyecto no se ha actualizado.</div>';
			}else if((isset($_FILES['auditor']['name']) and $_FILES['auditor']['name']!="")or
					 (isset($_FILES['doc_aportados']['name']) and  $_FILES['doc_aportados']['name']!="") or
					 (isset($_FILES['inf_final']['name']) and $_FILES['inf_final']['name']!="")){
				$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
				if($objFtp != false){
					// Login
					if (!$objFtp ->login(SRV_USER, SRV_PASS)){
						$objFtp->disconnect();
						//exit(1);
					}
					else{
						//En el file_exists poner ruta relativa al script en el que se ejecuta
						if(file_exists("docs/".$_GET['c']."/".$_GET['id']."/a")==false){
							if(!$objFtp->mkdir(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/a")){							
								echo '<div class="alert alert-danger" role="alert">Error al crear el directorio de auditoria. Si el problema persiste,
								por favor, póngase en contacto con el Administrador</div>';	
							}
						}
						$actualizar = "UPDATE jp_auditoria SET auditor ='".$auditor. "', doc_aportados ='".$doc_aportados. "', inf_final ='".$inf_final."'
						WHERE id ='".$lastID."'";
						$resultado = $bd->ejecutar($actualizar);
						
						if(isset($_FILES['auditor']['name']) and $_FILES['auditor']['name']!=""){
						
							$strData = file_get_contents($_FILES['auditor']['tmp_name']);
							//Subimos el archivo al servidor
							if(!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/a/".$auditor, $strData)){
								
								$error = 1;
								echo '<div class="alert alert-danger" role="alert">Error al subir el auditor. Si el problema persiste, por favor, póngase
								en contacto con el Servicio de Informática (SIRIUS)</div>';											
								$objFtp->disconnect();
								//exit(1);
							}//if put adjudicacion
							
						}
						
						if(isset($_FILES['doc_aportados']['name']) and  $_FILES['doc_aportados']['name']!=""){
						
							$strData = file_get_contents($_FILES['doc_aportados']['tmp_name']);
							//Subimos el archivo al servidor
							if(!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/a/".$doc_aportados, $strData)){
								
								$error = 1;
								echo '<div class="alert alert-danger" role="alert">Error al subir la acreditacion de pago. Si el problema persiste, por favor, póngase
								en contacto con el Servicio de Informática (SIRIUS)</div>';											
								$objFtp->disconnect();
								//exit(1);
							}//if put adjudicacion
							
						}
						
						if(isset($_FILES['inf_final']['name']) and $_FILES['inf_final']['name']!=""){
						
							$strData = file_get_contents($_FILES['inf_final']['tmp_name']);
							//Subimos el archivo al servidor
							if(!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/a/".$inf_final, $strData)){
								
								$error = 1;
								echo '<div class="alert alert-danger" role="alert">Error al subir el informe final. Si el problema persiste, por favor, póngase
								en contacto con el Servicio de Informática (SIRIUS)</div>';											
								$objFtp->disconnect();
								//exit(1);
							}//if put adjudicacion
						}
					}
				}
			}
		
		}else if(isset($_POST['actionAud']) and $_POST['actionAud']=='edit'){
			//Se recibe el formulario de edición de auditoria
			//insertar el código necesario para la subida de ficheros si es necesaria y guardar en una variable los set si existen fichero.
			//No olvidar borrar los ficheros antiguos.
			$sentencia="";
			
			if($auditor =="" && isset($_POST['audAud'])){
				$auditor = $_POST['audAud'];
			}else{
				if(isset($_FILES['auditor']['name']) and $_FILES['auditor']['name']!=""){
					
					$auditor=$_POST['idAud']."_A_".$_FILES['auditor']['name'];
					$sentencia=" auditor='".$auditor."', ";
				}
			}
			
			if($doc_aportados=="" && isset($_POST['dapAud'])){
				$doc_aportados= $_POST['dapAud'];
			}else{
				if(isset($_FILES['doc_aportados']['name']) and $_FILES['doc_aportados']['name']!="" ){
					
					$doc_aportados=$_POST['idAud']."_D_".$_FILES['doc_aportados']['name'];
					$sentencia.="doc_aportados='".$doc_aportados."', ";
				}
			}
			
			if($inf_final =="" && isset($_POST['infAud'])){
				$inf_final = $_POST['infAud'];
			}else{
				if(isset($_FILES['inf_final']['name']) and $_FILES['inf_final']['name']!=""){
					
					$inf_final=$_POST['idAud']."_I_".$_FILES['inf_final']['name'];
					$sentencia=" inf_final='".$inf_final."', ";
				}
			}
			
			$actualizar = "UPDATE jp_auditoria SET ".$sentencia." observaciones='".$_POST['observacionAUD']."'
			WHERE id = '".$_POST['idAud']."'";
			$resultado = $bd->ejecutar($actualizar);
			if($bd->totalAfectados() == -1){						
				echo '<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y la auditoria no se ha actualizado.</div>';
			}else{
				if((isset($_FILES['auditor']['name']) and $_FILES['auditor']['name']!="" ) or (isset ($_FILES['doc_aportados']['name']) and $_FILES['doc_aportados']['name']!="" )
					or (isset ($_FILES['inf_final']['name']) and $_FILES['inf_final']['name']!="" )){
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
							if(file_exists("docs/".$_GET['c']."/".$_GET['id']."/a")==false){
								if(!$objFtp->mkdir(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/a")){							
									echo '<div class="alert alert-danger" role="alert">Error al crear el directorio de auditoria. Si el problema persiste,
									por favor, póngase en contacto con el Administrador</div>';	
								}
							}
							if(isset($_FILES['auditor']['name']) and $_FILES['auditor']['name']!=""){
													
								//if(($_SESSION['convo'] != $_FILES['solicitud']['name'])){												
									$strData = file_get_contents($_FILES['auditor']['tmp_name']);
									if (!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/a/".$auditor, $strData)){
										$error = 1;
										echo '<div class="alert alert-danger" role="alert">Error al subir el auditor. Si el problema persiste, por favor, póngase
										en contacto con el Servicio de Informática (SIRIUS)</div>';
										$objFtp->disconnect();
										//exit(1);
									}
									else{
										//elimino la antigua del server
										$objFtp->delete(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/a/".$_POST['audAud'], false);
									}//if put solicitud
								//}//is sesion convo != files
							}
							if(isset($_FILES['doc_aportados']['name']) and $_FILES['doc_aportados']['name']!=""){
													
								//if(($_SESSION['convo'] != $_FILES['doc_aportados']['name'])){												
									$strData = file_get_contents($_FILES['doc_aportados']['tmp_name']);
									if (!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/a/".$doc_aportados, $strData)){
										$error = 1;
										echo '<div class="alert alert-danger" role="alert">Error al subir los documentos aportados. Si el problema persiste, por favor, póngase
										en contacto con el Servicio de Informática (SIRIUS)</div>';
										$objFtp->disconnect();
										//exit(1);
									}
									else{
										//elimino la antigua del server
										$objFtp->delete(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/a/".$_POST['dapAud'], false);
									}//if put doc_aportados
								//}//is sesion convo != files
							}
							if(isset($_FILES['inf_final']['name']) and $_FILES['inf_final']['name']!="" ){
													
								//if(($_SESSION['convo'] != $_FILES['solicitud']['name'])){												
									$strData = file_get_contents($_FILES['inf_final']['tmp_name']);
									if (!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/a/".$inf_final, $strData)){
										$error = 1;
										echo '<div class="alert alert-danger" role="alert">Error al subir el informe final. Si el problema persiste, por favor, póngase
										en contacto con el Servicio de Informática (SIRIUS)</div>';
										$objFtp->disconnect();
										//exit(1);
									}
									else{
										//elimino la antigua del server
										$objFtp->delete(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/a/".$_POST['infAud'], false);
									}//if put solicitud
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