<?php
	session_start();
	set_include_path(implode(PATH_SEPARATOR, array(
        realpath(dirname(__FILE__) . '/phpseclib'),
        get_include_path(),
	)));
	require_once('Conf.class.php');
	//require_once('lib/config.php');
	require_once('Db.php');
	include_once('Net/SFTP.php');
	
	
	/*Funciones para el borrado*/
	function eliminarConvocatoria($id, $doc){
		//echo "<p>Entrando en eliminar convocatoria</p>";
		$error = "";		
		if($doc == "yes"){		
			//Realizamos la conexión y login con el servidor		
			$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
			if($objFtp != false){
				if (!$objFtp ->login(SRV_USER, SRV_PASS)){
					$error = "Error en el login con el servidor. No se ha eliminado 
					la documentación. Por favor, póngase en contacto con el Servicio de Informática (SIRIUS)</div>";
					$objFtp->disconnect();
				}
				else{					
					$bd = Db::getInstance();
					$sql = "SELECT convocatoria, resolucion FROM jp_convocatoria WHERE id='".$id."';";
					$resultado = $bd->ejecutar($sql);
					if($bd->totalRegistros() == 1){
						while($fila = $bd->obtener_fila($resultado)){
							//Eliminamos el fichero del servidor
							if($fila['convocatoria'] != "") 
								if(!$objFtp->delete(SRV_DIR_UPLOAD.$id."/".$fila['convocatoria'], false))
									$error .= "El documento de la convocatoria no se ha eliminado<br>";
							if($fila['resolucion'] != "") 
								if(!$objFtp->delete(SRV_DIR_UPLOAD.$id."/".$fila['resolucion'], false))
									$error .= "El documento de la resolución no se ha eliminado";									
						}//end while
					}//if resultado
					$objFtp->disconnect();	
				}// end else !$objFtp ->login			
			} //if $objFtp !== false
			else{
				$error = "<div class='alert alert-danger' role='alert'>Error al conectar con el servidor. No se ha guardado la 
					documentación asociada. Si el problema persiste, por favor, póngase en contacto con el Servicio de Informática
					(SIRIUS)</div>";	
			} // end else $objFtp !== false
		}// if doc != none
		$bd = Db::getInstance();		
		$sql = "DELETE FROM c, cv USING jp_convocatoria AS c LEFT JOIN jp_conv_proyectos AS cv ON c.id=cv.id_convocatoria WHERE c.id=".$id;
		$resultado = $bd->ejecutar($sql);
		if($bd->totalAfectados() == 0) $error=1;
		$bd->desconectar();		
		return $error;
	}
	
	function eliminarProyecto($id, $idc){		
		//echo "<p>Entrando en eliminar recurso</p>";
		$error = "";
		$aTablas= array("jp_auditoria", "jp_documentacion", "jp_facturacion", "jp_justificacion_documental", 
				"jp_modificacion_proyecto", "jp_reintegro");
		$bd = Db::getInstance();
		for($i = 0; $i < count($aConsultas);$i++){
			$sql="DELETE FROM ".$aTablas[$i]." WHERE id_proyecto=".$id;
			$resultado = $bd->ejecutar($sql);
			if($bd->totalAfectados() == -1){
				//error al eliminar de la BD mandar correo y mostrar mensaje de error
				mail("mcarmen.garcia@sc.uhu.es, carlos.gomez@sc.uhu.es","Error en intranet justificacion","Se ha producido un error al eliminar de ".$aTablas[$i]. " del poryecto con id=".$id);
				
			}
		}
		
		$sql ="DELETE FROM jp_proyecto WHERE id=".$id;
		$resultado = $bd->ejecutar($sql);
		if($bd->totalAfectados() == 1){
			//Eliminar la información del resto de tablas: jp_conv_proyectos, jp_modificacion_proyecto
			//Dependiendo las tablas, eliminar documentación si la hubiere
			//Ejecutamos la consulta
			$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
			if($objFtp != false){
				if($objFtp ->login(SRV_USER, SRV_PASS)){
					if($objFtp->file_exists(SRV_DIR_UPLOAD.$idc."/".$id)){
						//delete con parámetro a true o sin segundo parámetro elimina recursivamente, si pones false no.
						if($objFtp->delete(SRV_DIR_UPLOAD.$idc."/".$id, true)){
							header('Location:proyectos.php');
							exit;
						}else{
							//Enviar correo a mcarmen.garcia@sc.uhu.es y a carlos.gomez@sc.uhu.es
							mail("mcarmen.garcia@sc.uhu.es, carlos.gomez@sc.uhu.es","Error en intranet justificacion","Se ha producido un error al eliminar la carpeta del proyecto con de id=".$id." del servidor");
						}	
					}	
				}
			}
		}
		else{
			$error = "Ha ocurrido un error";
			mail("mcarmen.garcia@sc.uhu.es, carlos.gomez@sc.uhu.es","Error en intranet justificacion","Se ha producido un error al eliminar de la tabla jp_proyecto el de id=".$id);
		}
		$bd->desconectar();
		//echo "<p>Saliendo de function eliminarProyecto</p>";
		return $error;
	}
	
	function eliminarLogo($idl, $idc){		
		//echo "<p>Entrando en eliminar recurso</p>";
		$error = "";
		$bd = Db::getInstance();
		$sql ="DELETE FROM jp_logo_convocatoria WHERE id_logo=".$idl." AND id_convocatoria=".$idc;
		$resultado = $bd->ejecutar($sql);
		//$bd->desconectar();
		if($bd->totalAfectados() != 1){
			$error = "Ha ocurrido un error";
		}
		$bd->desconectar();
		//echo "<p>Saliendo de function eliminarLogo</p>";
		header('Location:convocatoria.php?id='.$idc);
		return $error;
	}
	
	function eliminarModificacion($idm, $idp, $idc){		
		//echo "<p>Entrando en eliminar modificacion</p>";
		$error = "";
		$bd = Db::getInstance();
		$sql ="SELECT solicitud, resolucion FROM jp_modificacion_proyecto WHERE id=".$idm;
		$resultado = $bd->ejecutar($sql);
		$row = $bd->obtener_fila($resultado);
		$solicitud = $row['solicitud'];
		$resolucion = $row['resolucion'];
		
		$sql ="DELETE FROM jp_modificacion_proyecto WHERE id=".$idm;
		$resultado = $bd->ejecutar($sql);
		//$bd->desconectar();
		if($bd->totalAfectados() == 1){
			//Eliminar la información del resto de tablas: jp_conv_proyectos, jp_modificacion_proyecto
			//Dependiendo las tablas, eliminar documentación si la hubiere
			//Ejecutamos la consulta
			$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
			if($objFtp != false){
				if($objFtp ->login(SRV_USER, SRV_PASS)){
					if($objFtp->file_exists(SRV_DIR_UPLOAD.$idc."/".$idp."/m/".$solicitud)){
						
						if(!$objFtp->delete(SRV_DIR_UPLOAD.$idc."/".$idp."/m/".$solicitud, true)){
							$error = "Ha ocurrido un error";
						}	
					}
					if($objFtp->file_exists(SRV_DIR_UPLOAD.$idc."/".$idp."/m/".$resolucion)){
						
						if($objFtp->delete(SRV_DIR_UPLOAD.$idc."/".$idp."/m/".$resolucion, true)&& $error==""){
							header('Location:editar.php?id='.$idp.'&c='.$idc);
							exit;
						}	
					}
				}
			}
		}
		else $error = "Ha ocurrido un error";
		$bd->desconectar();
		//echo "<p>Saliendo de function eliminarModificacion</p>";
		return $error;
	}
	
	function eliminarDocumentacion($idd, $idp, $idc){		
		//echo "<p>Entrando en eliminar documentación</p>";
		$error = "";
		$bd = Db::getInstance();
		$sql ="SELECT ruta FROM jp_documentacion WHERE id=".$idd;
		$resultado = $bd->ejecutar($sql);
		$row = $bd->obtener_fila($resultado, MYSQLI_ASSOC);
		$ruta = $row['ruta'];
		
		$sql ="DELETE FROM jp_documentacion WHERE id=".$idd;
		$resultado = $bd->ejecutar($sql);
		
		if($bd->totalAfectados() == 1){
			//Ejecutamos la consulta
			$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
			if($objFtp != false){
				if($objFtp ->login(SRV_USER, SRV_PASS)){
					
					if($objFtp->file_exists(SRV_DIR_UPLOAD.$idc."/".$idp."/d/".$ruta)){
						
						if($objFtp->delete(SRV_DIR_UPLOAD.$idc."/".$idp."/d/".$ruta, true)){
							header('Location:editar.php?id='.$idp.'&c='.$idc);
							exit;
						}	
					}
				}
			}
		}
		else $error = "Ha ocurrido un error";
		$bd->desconectar();
		//echo "<p>Saliendo de function eliminarDocumentacion</p>";
		return $error;
	}
	
	function eliminarFactura($idf, $idp, $idc){		
		//echo "<p>Entrando en eliminar factura</p>";
		$error = "";
		$bd = Db::getInstance();
		$sql ="SELECT factura, acreditacion_pago FROM jp_facturacion WHERE id=".$idf;
		$resultado = $bd->ejecutar($sql);
		$row = $bd->obtener_fila($resultado);
		$factura = $row['factura'];
		$acreditacion_pago = $row['acreditacion_pago'];
		
		$sql ="DELETE FROM jp_facturacion WHERE id=".$idf;
		$resultado = $bd->ejecutar($sql);
		//$bd->desconectar();
		if($bd->totalAfectados() == 1){
			//Ejecutamos la consulta
			$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
			if($objFtp != false){
				if($objFtp ->login(SRV_USER, SRV_PASS)){
					if($objFtp->file_exists(SRV_DIR_UPLOAD.$idc."/".$idp."/f/".$factura)){
						
						if(!$objFtp->delete(SRV_DIR_UPLOAD.$idc."/".$idp."/f/".$factura, true)){
							$error = "Ha ocurrido un error";
						}	
					}
					if($objFtp->file_exists(SRV_DIR_UPLOAD.$idc."/".$idp."/f/".$acreditacion_pago)){
						
						if($objFtp->delete(SRV_DIR_UPLOAD.$idc."/".$idp."/f/".$acreditacion_pago, true)&& $error==""){
							header('Location:editar.php?id='.$idp.'&c='.$idc);
							exit;
						}	
					}
				}
			}
		}
		else $error = "Ha ocurrido un error";
		$bd->desconectar();
		//echo "<p>Saliendo de function eliminarFactura</p>";
		return $error;
	}
	
	function eliminarInventario($idi, $idp, $idc){		
		//echo "<p>Entrando en eliminar inventario</p>";
		$error = "";
		$bd = Db::getInstance();
		$sql ="SELECT imagen FROM jp_inventario WHERE id=".$idi;
		$resultado = $bd->ejecutar($sql);
		$row = $bd->obtener_fila($resultado, MYSQLI_ASSOC);
		$imagen = $row['imagen'];
		
		$sql ="DELETE FROM jp_inventario WHERE id=".$idi;
		$resultado = $bd->ejecutar($sql);
		
		if($bd->totalAfectados() == 1){
			//Ejecutamos la consulta
			$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
			if($objFtp != false){
				if($objFtp ->login(SRV_USER, SRV_PASS)){
					
					if($objFtp->file_exists(SRV_DIR_UPLOAD.$idc."/".$idp."/i/".$imagen)){
						
						if($objFtp->delete(SRV_DIR_UPLOAD.$idc."/".$idp."/i/".$imagen, true)){
							header('Location:editar.php?id='.$idp.'&c='.$idc);
							exit;
						}	
					}
				}
			}
		}
		else $error = "Ha ocurrido un error";
		$bd->desconectar();
		//echo "<p>Saliendo de function eliminarInventario</p>";
		return $error;
	}
	
	function eliminarAuditoria($ida, $idp, $idc){		
		
		$error = "";
		$bd = Db::getInstance();
		$sql ="SELECT auditor, doc_aportados, inf_final FROM jp_auditoria WHERE id=".$ida;
		$resultado = $bd->ejecutar($sql);
		$row = $bd->obtener_fila($resultado, MYSQLI_ASSOC);
		$auditor = $row['auditor'];
		$doc_aportados = $row['doc_aportados'];
		$inf_final = $row['inf_final'];
		
		$sql ="DELETE FROM jp_auditoria WHERE id=".$ida;
		$resultado = $bd->ejecutar($sql);
		
		if($bd->totalAfectados() == 1){
			//Ejecutamos la consulta
			$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
			if($objFtp != false){
				if($objFtp ->login(SRV_USER, SRV_PASS)){
					
					if($objFtp->file_exists(SRV_DIR_UPLOAD.$idc."/".$idp."/a/".$auditor)){
						
						if(!$objFtp->delete(SRV_DIR_UPLOAD.$idc."/".$idp."/a/".$auditor, true)){
							$error = "Ha ocurrido un error al eliminar auditor";
						}	
					}
					if($objFtp->file_exists(SRV_DIR_UPLOAD.$idc."/".$idp."/a/".$doc_aportados)){
						
						if($objFtp->delete(SRV_DIR_UPLOAD.$idc."/".$idp."/a/".$doc_aportados, true)){
							$error = "Ha ocurrido un error al Documentos aportados";
						}	
					}
					if($objFtp->file_exists(SRV_DIR_UPLOAD.$idc."/".$idp."/a/".$inf_final)){
						
						if($objFtp->delete(SRV_DIR_UPLOAD.$idc."/".$idp."/a/".$inf_final, true)){
							header('Location:editar.php?id='.$idp.'&c='.$idc);
							exit;
						}else{
							$error = "Ha ocurrido un error al eliminar informe final";
						}
					}
				}
			}
		}
		else $error = "Ha ocurrido un error";
		$bd->desconectar();
		//echo "<p>Saliendo de function eliminarAuditoria</p>";
		return $error;
	}
	
	function eliminarJustificacion($idj, $idp, $idc){		
		//echo "<p>Entrando en eliminar justificacion</p>";
		$error = "";
		$bd = Db::getInstance();
		$sql ="SELECT documento FROM jp_justificacion_documental WHERE id=".$idj;
		$resultado = $bd->ejecutar($sql);
		$row = $bd->obtener_fila($resultado, MYSQLI_ASSOC);
		$documento = $row['documento'];
		
		$sql ="DELETE FROM jp_justificacion_documental WHERE id=".$idj;
		$resultado = $bd->ejecutar($sql);
		
		if($bd->totalAfectados() == 1){
			//Ejecutamos la consulta
			$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
			if($objFtp != false){
				if($objFtp ->login(SRV_USER, SRV_PASS)){
					
					if($objFtp->file_exists(SRV_DIR_UPLOAD.$idc."/".$idp."/j/".$documento)){
						
						if($objFtp->delete(SRV_DIR_UPLOAD.$idc."/".$idp."/j/".$documento, true)){
							header('Location:editar.php?id='.$idp.'&c='.$idc);
							exit;
						}	
					}
				}
			}
		}
		else $error = "Ha ocurrido un error";
		$bd->desconectar();
		//echo "<p>Saliendo de function eliminarJustificacion</p>";
		return $error;
	}
	
	function eliminarReintegro($idr, $idp, $idc){		
		//echo "<p>Entrando en eliminar factura</p>";
		$error = "";
		$bd = Db::getInstance();
		$sql ="SELECT solicitud, pago FROM jp_reintegro WHERE id=".$idr;
		$resultado = $bd->ejecutar($sql);
		$row = $bd->obtener_fila($resultado, MYSQLI_ASSOC);
		$solicitud = $row['solicitud'];
		$pago = $row['pago'];
		
		$sql ="DELETE FROM jp_reintegro WHERE id=".$idr;
		$resultado = $bd->ejecutar($sql);
		//$bd->desconectar();
		if($bd->totalAfectados() == 1){
			//Ejecutamos la consulta
			$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
			if($objFtp != false){
				if($objFtp ->login(SRV_USER, SRV_PASS)){
					if($objFtp->file_exists(SRV_DIR_UPLOAD.$idc."/".$idp."/j/".$solicitud)){
						
						if(!$objFtp->delete(SRV_DIR_UPLOAD.$idc."/".$idp."/j/".$solicitud, true)){
							$error = "Ha ocurrido un error";
						}	
					}
					if($objFtp->file_exists(SRV_DIR_UPLOAD.$idc."/".$idp."/j/".$pago)){
						
						if($objFtp->delete(SRV_DIR_UPLOAD.$idc."/".$idp."/j/".$pago, true)&& $error==""){
							header('Location:editar.php?id='.$idp.'&c='.$idc);
							exit;
						}	
					}
				}
			}
		}
		else $error = "Ha ocurrido un error";
		$bd->desconectar();
		//echo "<p>Saliendo de function eliminarReintegro</p>";
		return $error;
	}
	
	function eliminarBasesConvo($idc){		
		$_SESSION['error_convo'] = "";
		$bd = Db::getInstance();
		$sql ="UPDATE jp_convocatoria SET bases = '' WHERE  id=".$idc;
		$resultado = $bd->ejecutar($sql);
		//Ejecutamos la consulta
		if($bd->totalAfectados() != 1){						
			$_SESSION['error_convo']='<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y las bases no se ha eliminado de la BD.</div>';
		}else{
			$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
			if($objFtp != false){
				if($objFtp ->login(SRV_USER, SRV_PASS)){
					$objFtp->chdir(SRV_DIR_UPLOAD);
					if($objFtp->file_exists($idc."/".$_SESSION['bas'])){
						
						if(!$objFtp->delete($idc."/".$_SESSION['bas'], false)){ 
							$_SESSION['error_convo']='<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el fichero de bases no se ha eliminado.</div>';
							$sql ="UPDATE jp_convocatoria SET bases = '".$_SESSION['bas']."' WHERE  id=".$idc;
							$resultado = $bd->ejecutar($sql);
							if($bd->totalAfectados() != 1){						
								$_SESSION['error_convo'].='<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y las bases no se ha eliminado de la BD.</div>';
							}
						}	
					}else{
						$_SESSION['error_convo']='<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el fichero de bases no se ha encontrado ni eliminado.</div>';
						$sql ="UPDATE jp_convocatoria SET bases = '".$_SESSION['bas']."' WHERE  id=".$idc;
						$resultado = $bd->ejecutar($sql);
						if($bd->totalAfectados() != 1){						
							$_SESSION['error_convo'].='<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y las bases se ha eliminado solo de la BD.</div>';
						}
					}
				}else{
					$_SESSION['error_convo']='<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error de login con el servidor y el fichero de bases no se ha eliminado.</div>';
					$sql ="UPDATE jp_convocatoria SET bases = '".$_SESSION['bas']."' WHERE  id=".$idc;
					$resultado = $bd->ejecutar($sql);
					if($bd->totalAfectados() != 1){						
						$_SESSION['error_convo'].='<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y las bases no se ha eliminado de la BD.</div>';
					}
				}
			}else{
				$_SESSION['error_convo']='<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error de conexión con el servidor y el fichero de bases no se ha eliminado.</div>';
			}
		}
		$bd->desconectar();
		
		header('Location:convocatoria.php?id='.$idc);
		
	}
	
	function eliminarDocConvo($idc){		
		$_SESSION['error_convo'] ="";
		$bd = Db::getInstance();
		$sql ="UPDATE jp_convocatoria SET convocatoria = '' WHERE  id=".$idc;
		$resultado = $bd->ejecutar($sql);
		if($bd->totalAfectados() != 1){						
			$_SESSION['error_convo']='<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el fichero de convocatoria no se ha eliminado de la BD.</div>';
		}else{
			//Ejecutamos la consulta
			$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
			if($objFtp != false){
				if($objFtp ->login(SRV_USER, SRV_PASS)){
					$objFtp->chdir(SRV_DIR_UPLOAD);
					if($objFtp->file_exists($idc."/".$_SESSION['convo'])){
						
						if(!$objFtp->delete($idc."/".$_SESSION['convo'], false)){
							$_SESSION['error_convo']='<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el fichero de convocatoria no se ha eliminado.</div>';
							$sql ="UPDATE jp_convocatoria SET convocatoria = '".$_SESSION['convo']."' WHERE  id=".$idc;
							$resultado = $bd->ejecutar($sql);
							if($bd->totalAfectados() != 1){						
								$_SESSION['error_convo'].='<br/><div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el fichero de convocatoria no se ha eliminado de la BD.</div>';
							}
						}
					}else{
						$_SESSION['error_convo']='<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el fichero de convocatoria no se ha encontrado ni eliminado.</div>';
						$sql ="UPDATE jp_convocatoria SET convocatoria = '".$_SESSION['convo']."' WHERE  id=".$idc;
						$resultado = $bd->ejecutar($sql);
						if($bd->totalAfectados() != 1){						
							$_SESSION['error_convo'].='<br/><div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el fichero de convocatoria se ha eliminado solo de la BD.</div>';
						}
					}					
				}else{
					$_SESSION['error_convo']='<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error de login con el servidor y el fichero de la convocatoria no se ha eliminado.</div>';
					$sql ="UPDATE jp_convocatoria SET convocatoria = '".$_SESSION['convo']."' WHERE  id=".$idc;
					$resultado = $bd->ejecutar($sql);
					if($bd->totalAfectados() != 1){						
						$_SESSION['error_convo'].='<br/><div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el fichero de convocatoria no se ha eliminado de la BD.</div>';
					}
				}
			}else{
				$_SESSION['error_convo']='<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error de conexión con el servidor y el fichero de la convocatoria no se ha eliminado.</div>';
			}
		}
		
		$bd->desconectar();
		
		//echo "<p>Saliendo de function eliminarBasesConvo</p>";
		header('Location:convocatoria.php?id='.$idc);
	}
	
	function eliminarResConvo($idc){		
		$_SESSION['error_convo'] = "";
		$bd = Db::getInstance();
		$sql ="UPDATE jp_convocatoria SET resolucion = '' WHERE  id=".$idc;
		$resultado = $bd->ejecutar($sql);
		if($bd->totalAfectados() != 1){						
			$_SESSION['error_convo']='<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y la resolución no se ha eliminado de la BD.</div>';
		}else{
			//Ejecutamos la consulta
			$objFtp = new Net_SFTP(SRV_NAME, SRV_PORT);
			if($objFtp != false){
				if($objFtp ->login(SRV_USER, SRV_PASS)){
					$objFtp->chdir(SRV_DIR_UPLOAD);
					if($objFtp->file_exists($idc."/".$_SESSION['res_def'])){
						
						if(!$objFtp->delete($idc."/".$_SESSION['res_def'], false)){
							$_SESSION['error_convo']='<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el fichero de resolución no se ha eliminado.</div>';
							$sql ="UPDATE jp_convocatoria SET resolucion = '".$_SESSION['res_def']."' WHERE  id=".$idc;
							$resultado = $bd->ejecutar($sql);
							if($bd->totalAfectados() != 1){						
								$_SESSION['error_convo'].='<br/><div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y la resolución solo se ha eliminado de la BD.</div>';
							}
						}	
					}else{
						$_SESSION['error_convo']='<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y el fichero de resolución no se ha encontrado ni eliminado.</div>';
						$sql ="UPDATE jp_convocatoria SET resolucion = '".$_SESSION['res_def']."' WHERE  id=".$idc;
						$resultado = $bd->ejecutar($sql);
						if($bd->totalAfectados() != 1){						
							$_SESSION['error_convo'].='<br/><div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y la resolución solo se ha eliminado de la BD.</div>';
						}
					}
				}else{
					$_SESSION['error_convo']='<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error de login con el servidor y el fichero de resolución no se ha eliminado.</div>';
					$sql ="UPDATE jp_convocatoria SET resolucion = '".$_SESSION['res_def']."' WHERE  id=".$idc;
					$resultado = $bd->ejecutar($sql);
					if($bd->totalAfectados() != 1){						
						$_SESSION['error_convo'].='<br/><div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error y la resolución solo se ha eliminado de la BD.</div>';
					}
				}
			}else{
				$_SESSION['error_convo']='<div style="width:94%;" class="alerta2 alert alert-danger">Ha ocurrido un error de conexión con el servidor y el fichero de resolución no se ha eliminado.</div>';
			}
		}
		$bd->desconectar();
		//echo "<p>Saliendo de function eliminarResConvo</p>";
		header('Location:convocatoria.php?id='.$idc);
	}
	
	function escribir($texto){
		$bd = Db::getInstance();
		$actualizar = "UPDATE jp_registro SET texto = CONCAT(texto, '<p>".$texto."</p><br/>') WHERE id =1";
		$resultado = $bd->ejecutar($actualizar);
		
		//Desde aquí se mandaría el correo electrónico para informar de los cambios que se hayan hecho.
	}

	function borrarRegistro(){
		/*$archivo = fopen("../docs/registro.txt", "w+");
		fputs($archivo, "");
		fclose($archivo);*/
		$bd = Db::getInstance();
		$actualizar = "UPDATE jp_registro SET texto = '' WHERE id =1";
		$resultado = $bd->ejecutar($actualizar);
	}
?>