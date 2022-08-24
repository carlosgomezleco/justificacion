<?php
	//include('Net/SFTP.php');
	//include('lib/Db.php');
	//include('lib/Conf.class.php');
	//include('lib/funciones.php');
	

	$solicitud = (isset($_FILES['solicitud']['name']))? $_FILES['solicitud']['name'] : "";
	$resolucion = (isset($_FILES['resolucion']['name']))? $_FILES['resolucion']['name'] : "";
	$peso=0;
	if(isset($_FILES['solicitud']['size'])){
		$peso+=$_FILES['solicitud']['size'];
	}
	
	if(isset($_FILES['resolucion']['size'])){
		$peso+=$_FILES['resolucion']['size'];
	}
	
	if($peso>5242880){
		echo '<div style="width:94%;" class="alerta2 alert alert-danger">Los ficheros que está intentando subir suman un peso mayor de 5MB</div>';
	}
	else{
		if(isset($_POST['actionMod']) and $_POST['actionMod']=='add'){
			/*$insertar = "INSERT INTO jp_modificacion_proyecto (solicitud, resolucion, observacion, id_proyecto) VALUES ('".$solicitud."','".$resolucion."','".
						$_POST['observacion']."','".$_GET['id']."')";
			
			$resultado = $bd->ejecutar($insertar);*/
			
			
			$insertar = "INSERT INTO jp_modificacion_proyecto (observacion, id_proyecto) VALUES ('".$_POST['observacionMOD']."','".$_GET['id']."')";
			$resultado = $bd->ejecutar($insertar);
			
			$lastID = $bd->lastID();
			if($solicitud!=""){
				/*$extension = substr($solicitud,strrpos($solicitud,'.'), strlen($solicitud));
				$solicitud=$lastID."_Solicitud".$extension;*/
				$solicitud=renombrar_fichero($solicitud);
			}
			if($resolucion!=""){
				$resolucion=renombrar_fichero($resolucion);
			}
			//Si se ha insertado, muestra el listado
			if($bd->totalAfectados() != 1){						
				echo '<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el proyecto no se ha actualizado.</div>';
			}
			
			$actualizar = "UPDATE jp_modificacion_proyecto SET solicitud ='".$solicitud. "', resolucion ='".$resolucion. "'
			WHERE id ='".$lastID."'";
			$resultado = $bd->ejecutar($actualizar);
			$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
			if($objFtp != false){
				// Login
				if (!$objFtp ->login(SRV_USER, SRV_PASS)){
					$objFtp->disconnect();
					//exit(1);
				}
				else{
					//En el file_exists poner ruta relativa al script en el que se ejecuta
					if(!file_exists("docs/".$_GET['c']."/".$_GET['id']."/m")){
						if(!$objFtp->mkdir(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/m")){
							echo '<div class="alert alert-danger" role="alert">Error al crear el directorio de modificaciones. Si el problema persiste,
							por favor, póngase en contacto con el Administrador</div>';	
						}
					}

					if(isset($_FILES['solicitud']['name']) and $_FILES['solicitud']['name']!=""){
					
						$strData = file_get_contents($_FILES['solicitud']['tmp_name']);
						//Subimos el archivo al servidor
						if(!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/m/".$solicitud, $strData)){
							
							$error = 1;
							echo '<div class="alert alert-danger" role="alert">Error al subir la solicitud de modificación. Si el problema persiste, por favor, póngase
							en contacto con el Servicio de Informática (SIRIUS)</div>';											
							//$objFtp->disconnect();
							//exit(1);
						}//if put adjudicacion
						
					}
					
					if(isset($_FILES['resolucion']['name']) and $_FILES['resolucion']['name']!=""){
					
						$strData = file_get_contents($_FILES['resolucion']['tmp_name']);
						//Subimos el archivo al servidor
						if(!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/m/".$resolucion, $strData)){
							
							$error = 1;
							echo '<div class="alert alert-danger" role="alert">Error al subir la resolucion de la modificación. Si el problema persiste, por favor, póngase
							en contacto con el Servicio de Informática (SIRIUS)</div>';											
							$objFtp->disconnect();
							exit(1);
						}//if put adjudicacion
						
					}
				}
			}				

		
		}else if(isset($_POST['actionMod']) and $_POST['actionMod']=='edit'){
			//insertar el código necesario para la subida de ficheros si es necesaria y guardar en una variable los set si existen fichero.
			//No olvidar borrar los ficheros antiguos.
			$sentencia="";
			
			if($solicitud=="" && isset($_POST['solMod'])){
				$solicitud= $_POST['solMod'];
			}else{
				if(isset($_FILES['solicitud']['name'])){
					
					$solicitud=renombrar_fichero($_FILES['solicitud']['name']);
					//$extension = substr($_FILES['solicitud']['name'],strrpos($_FILES['solicitud']['name'],'.'), strlen($_FILES['solicitud']['name']));
					//$solicitud=$_POST['idMod']."_Solicitud".$extension;
					$solicitud=$_POST['idMod']."_".$solicitud;
					$sentencia=" solicitud='".$solicitud."', ";
				}
			}
			
			if($resolucion=="" && isset($_POST['resMod'])){
				$resolucion= $_POST['resMod'];
			}else{
				if(isset($_FILES['resolucion']['name'])){
					
					$resolucion=renombrar_fichero($_FILES['resolucion']['name']);
					//$extension = substr($_FILES['resolucion']['name'],strrpos($_FILES['resolucion']['name'],'.'), strlen($_FILES['resolucion']['name']));
					//$resolucion=$_POST['idMod']."_Resolucion".$extension;;
					$resolucion=$_POST['idMod']."_".$resolucion;
					$sentencia.="resolucion='".$resolucion."', ";
				}
			}
			
			$actualizar = "UPDATE jp_modificacion_proyecto SET ".$sentencia."observacion='".$_POST['observacionMOD']."' WHERE id = '".$_POST['idMod']."'";
			$resultado = $bd->ejecutar($actualizar);
			if($bd->totalAfectados() == -1){						
				echo '<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y la modificacion no se ha actualizado.</div>';
			}else{
				if(isset($_FILES['solicitud']['name']) or isset ($_FILES['resolucion']['name']) ){
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
							if(file_exists("docs/".$_GET['c']."/".$_GET['id']."/m")==false){
								if(!$objFtp->mkdir(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/m")){							
									echo '<div class="alert alert-danger" role="alert">Error al crear el directorio de modificaciones. Si el problema persiste,
									por favor, póngase en contacto con el Administrador</div>';	
								}
							}
							
							if(isset($_FILES['solicitud']['name']) and $_FILES['solicitud']['name']!=""){
													
								//if(($_SESSION['convo'] != $_FILES['solicitud']['name'])){												
									$strData = file_get_contents($_FILES['solicitud']['tmp_name']);
									if (!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/m/".$solicitud, $strData)){
										$error = 1;
										echo '<div class="alert alert-danger" role="alert">Error al subir la solicitud. Si el problema persiste, por favor, póngase
										en contacto con el Servicio de Informática (SIRIUS)</div>';
										$objFtp->disconnect();
										//exit(1);
									}
									else{
										//elimino la antigua del server
										$objFtp->delete(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/m/".$_POST['solMod'], false);
									}//if put solicitud
								//}//is sesion convo != files
							}
							if(isset($_FILES['resolucion']['name']) and $_FILES['resolucion']['name']!=""){
													
								//if(($_SESSION['convo'] != $_FILES['resolucion']['name'])){												
									$strData = file_get_contents($_FILES['resolucion']['tmp_name']);
									if (!$objFtp->put(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/m/".$resolucion, $strData)){
										$error = 1;
										echo '<div class="alert alert-danger" role="alert">Error al subir la resolucion. Si el problema persiste, por favor, póngase
										en contacto con el Servicio de Informática (SIRIUS)</div>';
										$objFtp->disconnect();
										//exit(1);
									}
									else{
										//elimino la antigua del server
										$objFtp->delete(SRV_DIR_UPLOAD.$_GET['c']."/".$_GET['id']."/m/".$_POST['resMod'], false);
									}//if put resolucion
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