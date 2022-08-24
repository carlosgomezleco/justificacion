function editar_modificacion(idm, idp, idc){
	//window.location.href = "edit_documentacion.php?idp="+idp+"&idc="+idc+"&idd="+idd;
	$.get('ajax/DatosJSON.php?info=MODIFICACION&id='+idm,function(resp){
		
		$('#tituloMod').text('Editar Modificacion');
		$('#observacionMOD').val(resp.observacion);
		$('#idMod').val(idm);
		$('#actionMod').val('edit');
		//Añadir el link con el documento si lo tiene.
		if(resp.solicitud!=""){
			$('#hrefSolMod').attr("href", "docs/"+idc+"/"+idp+"/m/"+resp.solicitud);
			$('#solMod').val(resp.solicitud);
			$('#todoChbSol').show();
			$('#chb_sol').show();
			$('#hrefSolMod').show();
			$("#solicitud").attr('disabled',true);
			$('#hrefSolMod').text('Solicitud asignada');
			$('#textChx_sol').text('Solo si desea modificarla, active la casilla.');			
			$('#EliminaSol').show();
			$('#EliminaSol').attr("onclick", "eliminar_archivo('jp_modificacion_proyecto','solicitud',"+idm+",'"+idc+"/"+idp+"/m/"+resp.solicitud+"')");
		}else{
			$("#solicitud").attr('disabled',false);
			$('#chb_sol').hide();
			$('#hrefSolMod').text('');
			$('#textChx_sol').text('');
			$('#hrefSolMod').hide();
			$('#todoChbRes').hide();		
			$('#EliminaSol').hide();
		}
		if(resp.resolucion!=""){
			$('#hrefResMod').attr("href", "docs/"+idc+"/"+idp+"/m/"+resp.resolucion);
			$('#resMod').val(resp.resolucion);
			//$('#chb_res').show();
			//$('#hrefResMod').show();
			$("#resolucion").attr('disabled',true);
			$('#hrefResMod').text('Resolucion asignada');
			$('#textChx_res').text('Solo si desea modificarla, active la casilla.');
			$('#EliminaRes').show();
			$('#EliminaRes').attr("onclick", "eliminar_archivo('jp_modificacion_proyecto','resolucion',"+idm+",'"+idc+"/"+idp+"/m/"+resp.resolucion+"')");
			$('#todoChbRes').show();
		}else{
			$("#resolucion").attr('disabled',false);
			$('#chb_res').hide();
			$('#hrefResMod').text('');
			$('#textChx_res').text('');
			$('#hrefResMod').hide();
			$('#todoChbRes').hide();
			$('#EliminaRes').hide();
		}
		$('#addModificacion').modal('show');
	});
}

function editar_documentacion(idd, idp, idc){
	//window.location.href = "edit_documentacion.php?idp="+idp+"&idc="+idc+"&idd="+idd;
	$.get('ajax/DatosJSON.php?info=DOCUMENTACION&id='+idd,function(resp){
		
		$('#tituloDoc').text('Editar Documento');
		$('#tipoDoc').val(resp.id_tipo_documento);
		$('#observacionDOC').val(resp.observaciones);
		$('#idDoc').val(idd);
		$('#actionDoc').val('edit');
		$('#fechaJusDoc').val(resp.subida);
		if(resp.ruta!=""){
			$('#hrefDoc').attr("href", "docs/"+idc+"/"+idp+"/d/"+resp.ruta);
			$('#fileDoc').val(resp.ruta);
			$('#chb_doc').show();
			$('#hrefDoc').show();
			$('#todoChbDoc').show();
			$("#documentacion_").attr('disabled',true);
			$('#hrefDoc').text('Documentacion asignada');
			$('#textChx_doc').text('Solo si desea modificarla, active la casilla.');
			$('#EliminaDoc').show();
			$('#EliminaDoc').attr("onclick", "eliminar_archivo('jp_documentacion','ruta',"+idd+",'"+idc+"/"+idp+"/d/"+resp.ruta+"')");
		}else{
			$("#documentacion_").attr('disabled',false);
			$('#chb_doc').hide();
			$('#hrefDoc').text('');
			$('#textChx_doc').text('');
			$('#hrefDoc').hide();
			$('#todoChbDoc').hide();
			$('#EliminaDoc').hide();
		}
		
		$('#addDocumentacion').modal('show');
	});
}

function editar_factura(idf, idp, idc){
	//window.location.href = "edit_documentacion.php?idp="+idp+"&idc="+idc+"&idd="+idd;
	$.get('ajax/DatosJSON.php?info=FACTURA&id='+idf,function(resp){
		
		$('#tituloFac').text('Editar Factura');
		$('#tipoFac').val(resp.id_tipo_documento);
		$('#observacionFAC').val(resp.observaciones);
		$('#idFac').val(idf);
		$('#fechaJusFac').val(resp.subida);
		$('#actionFac').val('edit');
		//Añadir el link con el documento si lo tiene.
		if(resp.factura!=""){
			$('#hrefFacFac').attr("href", "docs/"+idc+"/"+idp+"/f/"+resp.factura);
			$('#facFac').val(resp.factura);
			$('#chb_fac').show();
			$('#hrefFacFac').show();
			$("#factura").attr('disabled',true);
			$('#hrefFacFac').text('Factura asignada');
			$('#textChx_fac').text('Solo si desea modificarla, active la casilla.');
			$('#todoChbFac').show();
			$('#EliminaFac').show();
			$('#EliminaFac').attr("onclick", "eliminar_archivo('jp_facturacion','factura',"+idf+",'"+idc+"/"+idp+"/f/"+resp.factura+"')");
		}else{
			$("#factura").attr('disabled',false);
			$('#chb_fac').hide();
			$('#hrefFacFac').text('');
			$('#textChx_fac').text('');
			$('#hrefFacFac').hide();
			$('#todoChbFac').hide();
			$('#EliminaFac').hide();
		}
		if(resp.acreditacion_pago!=""){
			$('#hrefAcpFac').attr("href", "docs/"+idc+"/"+idp+"/f/"+resp.acreditacion_pago);
			$('#acpFac').val(resp.acreditacion_pago);
			$('#chb_acp').show();
			$('#hrefAcpFac').show();
			$("#acreditacion_pago").attr('disabled',true);
			$('#hrefAcpFac').text('Pago asignado');
			$('#textChx_acp').text('Solo si desea modificarla, active la casilla.');
			$('#todoChbAcp').show();
			$('#EliminaAcp').show();
			$('#EliminaAcp').attr("onclick", "eliminar_archivo('jp_facturacion','acreditacion_pago',"+idf+",'"+idc+"/"+idp+"/f/"+resp.acreditacion_pago+"')");
		}else{
			$("#acreditacion_pago").attr('disabled',false);
			$('#chb_acp').hide();
			$('#hrefAcpFac').text('');
			$('#textChx_acp').text('');
			$('#hrefAcpFac').hide();
			$('#todoChbAcp').hide();
			$('#EliminaAcp').hide();
		}
		$('#addFactura').modal('show');
	});
}

function editar_inventario(idi, idp, idc){
	//window.location.href = "edit_documentacion.php?idp="+idp+"&idc="+idc+"&idd="+idd;
	$.get('ajax/DatosJSON.php?info=INVENTARIO&id='+idi,function(resp){
		
		$('#tituloInv').text('Editar Inventario');
		$('#tipoInv').val(resp.id_tipo_documento);
		$('#observacionINV').val(resp.observaciones);
		$('#fechaJusInv').val(resp.subida);
		if(resp.imagen!=""){
			$('#hrefImgInv').attr("href", "docs/"+idc+"/"+idp+"/i/"+resp.imagen);
			$('#imgInv').val(resp.imagen);
			$('#chb_img').show();
			$('#hrefImgInv').show();
			$('#todoChbInv').show();
			$("#imagen").attr('disabled',true);
			$('#hrefImgInv').text('Imagen asignada');
			$('#textChx_img').text('Solo si desea modificarla, active la casilla.');
			$('#EliminaImg').attr("onclick", "eliminar_archivo('jp_inventario','imagen',"+idi+",'"+idc+"/"+idp+"/i/"+resp.imagen+"')");
			$('#EliminaImg').show();
			
		}else{
			$("#imagen").attr('disabled',false);
			$('#chb_img').hide();
			$('#hrefImgInv').text('');
			$('#textChx_img').text('');
			$('#hrefImgInv').hide();
			$('#todoChbInv').hide();
			$('#EliminaImg').hide();
		}
		$('#idInv').val(idi);
		$('#actionInv').val('edit');
		//Añadir el link con el documento si lo tiene.
		$('#addInventario').modal('show');
	});

}

function editar_auditoria(ida, idp, idc){
	//window.location.href = "edit_documentacion.php?idp="+idp+"&idc="+idc+"&idd="+idd;
	$.get('ajax/DatosJSON.php?info=AUDITORIA&id='+ida,function(resp){
		
		$('#tituloAud').text('Editar Auditoria');
		$('#observacionAUD').val(resp.observaciones);
		$('#idAud').val(ida);
		$('#actionAud').val('edit');
		//Añadir el link con el documento si lo tiene.
		if(resp.auditor!=""){
			$('#hrefAudAud').attr("href", "docs/"+idc+"/"+idp+"/a/"+resp.auditor);
			$('#audAud').val(resp.auditor);
			$('#todoChbAud').show();
			$('#chb_aud').show();
			$('#hrefAudAud').show();
			$("#auditor").attr('disabled',true);
			$('#hrefAudAud').text('Auditor asignado');
			$('#textChx_aud').text('Solo si desea modificarla, active la casilla.');
			$('#EliminaAud').attr("onclick", "eliminar_archivo('jp_auditoria','auditor',"+ida+",'"+idc+"/"+idp+"/a/"+resp.auditor+"')");
			$('#EliminaAud').show();
		}else{
			$("#auditor").attr('disabled',false);
			$('#chb_aud').hide();
			$('#hrefAudAud').text('');
			$('#textChx_aud').text('');
			$('#hrefAudAud').hide();
			$('#todoChbAud').hide();
			$('#EliminaAud').hide();
		}
		if(resp.doc_aportados!=""){
			$('#hrefDApAud').attr("href", "docs/"+idc+"/"+idp+"/a/"+resp.doc_aportados);
			$('#dapAud').val(resp.doc_aportados);
			//$('#chb_res').show();
			//$('#hrefResMod').show();
			$("#doc_aportados").attr('disabled',true);
			$('#hrefDApAud').text('Documentos Aportados asignados');
			$('#textChx_dap').text('Solo si desea modificarla, active la casilla.');
			$('#todoChbDAp').show();
			$('#EliminaDap').attr("onclick", "eliminar_archivo('jp_auditoria','doc_aportados',"+ida+",'"+idc+"/"+idp+"/a/"+resp.doc_aportados+"')");
			$('#EliminaDap').show();
		}else{
			$("#doc_aportados").attr('disabled',false);
			$('#chb_dap').hide();
			$('#hrefDApAud').text('');
			$('#textChx_dap').text('');
			$('#hrefDApAud').hide();
			$('#todoChbDAp').hide();
			$('#EliminaDap').hide();
		}
		if(resp.inf_final!=""){
			$('#hrefInFAud').attr("href", "docs/"+idc+"/"+idp+"/a/"+resp.inf_final);
			$('#infAud').val(resp.inf_final);
			$('#todoChbInF').show();
			$('#chb_inf').show();
			$('#hrefInFAud').show();
			$("#inf_final").attr('disabled',true);
			$('#hrefInFAud').text('Informe Final asignado');
			$('#textChx_inf').text('Solo si desea modificarla, active la casilla.');
			$('#EliminaInf').attr("onclick", "eliminar_archivo('jp_auditoria','inf_final',"+ida+",'"+idc+"/"+idp+"/a/"+resp.inf_final+"')");
			$('#EliminaInf').show();
		}else{
			$("#inf_final").attr('disabled',false);
			$('#chb_inf').hide();
			$('#hrefInFAud').text('');
			$('#textChx_inf').text('');
			$('#hrefInFAud').hide();
			$('#todoChbInF').hide();
			$('#EliminaInf').hide();
		}
		$('#addAuditoria').modal('show');
	});
}

function editar_justificacion(idj, idp, idc){
	
	$.get('ajax/DatosJSON.php?info=JUSTIFICACION&id='+idj,function(resp){
		
		$('#tituloJus').text('Editar Justificacion');
		$('#n_solicitud').val(resp.n_solicitud);
		$('#fechaJus').val(resp.fecha);
		$('#observacionJUS').val(resp.observaciones);
		$('#idJus').val(idj);
		$('#actionJus').val('edit');
		//Añadir el link con el documento si lo tiene.
		if(resp.documento!=""){
			$('#hrefDocJus').attr("href", "docs/"+idc+"/"+idp+"/j/"+resp.documento);
			$('#docJus').val(resp.documento);
			$('#todoChbJus').show();
			$('#chb_jus').show();
			$('#hrefDocJus').show();
			$("#documentacionJus").attr('disabled',true);
			$('#hrefDocJus').text('Documentacion asignada');
			$('#textChx_jus').text('Solo si desea modificarla, active la casilla.');
			$('#EliminaDJus').attr("onclick", "eliminar_archivo('jp_justificacion','documento',"+idj+",'"+idc+"/"+idp+"/j/"+resp.documento+"')");
			$('#EliminaDJus').show();
		}else{
			$("#documentacionJus").attr('disabled',false);
			$('#chb_jus').hide();
			$('#hrefDocJus').text('');
			$('#textChx_jus').text('');
			$('#hrefDocJus').hide();
			$('#todoChbJus').hide();
			$('#EliminaDJus').hide();
		}
		$('#addJustificacion').modal('show');
	});
}

function editar_reintegro(idr, idp, idc){
	//window.location.href = "edit_documentacion.php?idp="+idp+"&idc="+idc+"&idd="+idd;
	$.get('ajax/DatosJSON.php?info=REINTEGRO&id='+idr,function(resp){
		
		$('#tituloRei').text('Editar Reintegro');
		$('#observacionREI').val(resp.observaciones);
		$('#idRei').val(idr);
		$('#actionRei').val('edit');
		//Añadir el link con el documento si lo tiene.
		if(resp.solicitud!=""){
			$('#hrefSolRei').attr("href", "docs/"+idc+"/"+idp+"/j/"+resp.solicitud);
			$('#solRei').val(resp.solicitud);
			$('#chb_sor').show();
			$('#hrefSolRei').show();
			$("#solicitudRei").attr('disabled',true);
			$('#hrefSolRei').text('Solicitud asignada');
			$('#textChx_sor').text('Solo si desea modificarla, active la casilla.');
			$('#todoChbSoR').show();
			$('#EliminaSRe').attr("onclick", "eliminar_archivo('jp_reintegro','solicitud',"+idr+",'"+idc+"/"+idp+"/j/"+resp.solicitud+"')");
			$('#EliminaSRe').show();
		}else{
			$("#solicitudRei").attr('disabled',false);
			$('#chb_sor').hide();
			$('#hrefSolRei').text('');
			$('#textChx_sor').text('');
			$('#hrefSolRei').hide();
			$('#todoChbSoR').hide();
			$('#EliminaSRe').hide();
		}
		if(resp.pago!=""){
			$('#hrefPagRei').attr("href", "docs/"+idc+"/"+idp+"/j/"+resp.pago);
			$('#pagRei').val(resp.pago);
			$('#chb_par').show();
			$('#hrefPagRei').show();
			$("#pagoRei").attr('disabled',true);
			$('#hrefPagRei').text('Pago asignado');
			$('#textChx_par').text('Solo si desea modificarla, active la casilla.');
			$('#todoChbPaR').show();
			$('#EliminaPRe').attr("onclick", "eliminar_archivo('jp_reintegro','pago',"+idr+",'"+idc+"/"+idp+"/j/"+resp.pago+"')");
			$('#EliminaPRe').show();
		}else{
			$("#pagoRei").attr('disabled',false);
			$('#chb_par').hide();
			$('#hrefPagRei').text('');
			$('#textChx_par').text('');
			$('#hrefPagRei').hide();
			$('#todoChbPaR').hide();
			$('#EliminaPRe').hide();
		}
		$('#addReintegro').modal('show');
	});
}

function eliminar_modificacion(idm, idp, idc){
		if(confirm("¿Está seguro de que desea eliminar la modificacion ("+idm+")?") == true)   
			window.location.href = "eliminar.php?idm="+idm+"&idp="+idp+"&idc="+idc+"&resource=mod";
}

function eliminar_documentacion(idd, idp, idc){
		if(confirm("¿Está seguro de que desea eliminar la documentacion ("+idd+")?") == true)   
			window.location.href = "eliminar.php?idd="+idd+"&idp="+idp+"&idc="+idc+"&resource=doc";
}

function eliminar_factura(idf, idp, idc){
		if(confirm("¿Está seguro de que desea eliminar la factura ("+idf+")?") == true)   
			window.location.href = "eliminar.php?idf="+idf+"&idp="+idp+"&idc="+idc+"&resource=fac";
}

function eliminar_inventario(idi, idp, idc){
		if(confirm("¿Está seguro de que desea eliminar el elemento de inventario ("+idi+")?") == true)   
			window.location.href = "eliminar.php?idi="+idi+"&idp="+idp+"&idc="+idc+"&resource=inv";
}

function eliminar_auditoria(ida, idp, idc){
		if(confirm("¿Está seguro de que desea eliminar la auditoria ("+ida+")?") == true)   
			window.location.href = "eliminar.php?ida="+ida+"&idp="+idp+"&idc="+idc+"&resource=aud";
}

function eliminar_justificacion(idj, idp, idc){
		if(confirm("¿Está seguro de que desea eliminar la justificacion documental ("+idj+")?") == true)   
			window.location.href = "eliminar.php?idj="+idj+"&idp="+idp+"&idc="+idc+"&resource=jus";
}

function eliminar_reintegro(idr, idp, idc){
		if(confirm("¿Está seguro de que desea eliminar el reintegro ("+idr+")?") == true)   
			window.location.href = "eliminar.php?idr="+idr+"&idp="+idp+"&idc="+idc+"&resource=rei";
}

function eliminar_archivo(tabla, campo, id, rutaArchivo, idp, idc){
		
		if(confirm("¿Está seguro de que desea eliminar el archivo ?") == true){
			//PETICION AJAX
			$.get('ajax/eliminar_archivo.php?accion=ELIMINAR_ARCHIVO&tabla='+tabla+'&campo='+campo+'&id='+id+'&ruta='+rutaArchivo+'&idp='+idp+'&idc='+idc,function(resp){
				if(resp.resultado==true){
					if(tabla=='jp_auditoria'){
						editar_auditoria(id, idp, idc);
					}else if(tabla=='jp_documentacion'){
						editar_documentacion(id, idp, idc);
					}else if(tabla=='jp_facturacion'){
						editar_factura(id, idp, idc);
					}else if(tabla=='jp_inventario'){
						editar_inventario(id, idp, idc);
					}else if(tabla=='jp_justificacion'){
						editar_justificacion(id, idp, idc);
					}else if(tabla=='jp_modificacion_proyecto'){
						editar_modificacion(id, idp, idc);
					}else if(tabla=='jp_reintegro'){
						editar_reintegro(id, idp, idc);
					}
					
				}
			});
			
		}
	}

function AddModificacion(){
	$('#tituloMod').text('Añadir Modificacion');
	$('#actionMod').val('add');
	$("#solicitud").attr('disabled',false);
	$("#resolucion").attr('disabled',false);
	///////////////
	//$('#chb_sol').hide();
	//$('#hrefSolMod').text('');
	//$('#textChx_sol').text('');
	//$('#hrefSolMod').hide();
	//$('#textChx_sol').hide();
	$('#todoChbSol').hide();
	////////////////////
	//$('#chb_res').hide();
	//$('#hrefResMod').text('');
	//$('#textChx_res').text('');
	//$('#hrefResMod').hide();
	//$('#textChx_res').hide();
	$('#todoChbRes').hide();
	/////////////////////
	$('#addModificacion').modal('show');
}

function CerrarModificacion(idp,idc){ 
	$('#addModificacion').modal('hide');
	$('#form-modificacion')[0].reset();	
	location.href ='editar.php?id='+idp+'&c='+idc;
	//window.location.replace('editar.php?');
	//$('#tab2').class
}

function AddDocumentacion(){
	$('#tituloDoc').text('Añadir Documentacion');
	$('#actionDoc').val('add');
	$('#chb_doc').hide();
	$('#hrefDoc').text('');
	$('#textChx_doc').text('');
	$('#hrefDoc').hide();
	$('#todoChbDoc').hide();
	$('#addDocumentacion').modal('show');
}

function CerrarDocumentacion(idp,idc){
	$('#addDocumentacion').modal('hide');
	$('#form-documentacion')[0].reset();
	location.href ='editar.php?id='+idp+'&c='+idc;
}

function AddFactura(){
	$('#tituloFac').text('Añadir Factura');
	$('#actionFac').val('add');
	///////////////
	$('#chb_fac').hide();
	$('#hrefFacFac').text('');
	$('#textChx_fac').text('');
	$('#hrefFacFac').hide();
	$('#todoChbFac').hide();
	////////////////////
	$('#chb_acp').hide();
	$('#hrefAcpFac').text('');
	$('#textChx_acp').text('');
	$('#hrefAcpFac').hide();
	$('#todoChbAcp').hide();
	/////////////////////
	$('#addFactura').modal('show');
}

function CerrarFactura(idp,idc){
	$('#addFactura').modal('hide');
	$('#form-factura')[0].reset();
	location.href ='editar.php?id='+idp+'&c='+idc;
}

function AddInventario(){
	$('#tituloInv').text('Añadir Inventario');
	$('#actionInv').val('add');
	$('#chb_img').hide();
	$('#hrefImgInv').text('');
	$('#textChx_img').text('');
	$('#hrefImgInv').hide();
	$('#todoChbInv').hide();
	$('#addInventario').modal('show');
}

function CerrarInventario(idp,idc){
	$('#addInventario').modal('hide');
	$('#form-inventario')[0].reset();
	location.href ='editar.php?id='+idp+'&c='+idc;
}

function AddAuditoria(){
	$('#tituloAud').text('Añadir Auditoria');
	$('#actionAud').val('add');
	$('#chb_aud').hide();
	$('#chb_dap').hide();
	$('#chb_inf').hide();
	$('#hrefImgInv').text('');
	$('#textChx_img').text('');
	$('#hrefImgInv').hide();
	$('#todoChbAud').hide();
	$('#todoChbDAp').hide();
	$('#todoChbInF').hide();
	$('#addAuditoria').modal('show');
}

function CerrarAuditoria(idp,idc){
	$('#addAuditoria').modal('hide');
	$('#form-auditoria')[0].reset();
	location.href ='editar.php?id='+idp+'&c='+idc;
}

function AddJustificacion(){
	$('#tituloJus').text('Añadir Justificacion');
	$('#actionJus').val('add');
	//$('#chb_aud').hide();
	//$('#hrefImgInv').text('');
	//$('#textChx_img').text('');
	//$('#hrefImgInv').hide();
	$('#todoChbJus').hide();
	$('#addJustificacion').modal('show');
}

function CerrarJustificacion(idp,idc){
	$('#addJustificacion').modal('hide');
	$('#form-justificacion')[0].reset();
	location.href ='editar.php?id='+idp+'&c='+idc;
}

function AddReintegro(){
	$('#tituloRei').text('Añadir Reintegro');
	$('#actionRei').val('add');
	//$('#chb_aud').hide();
	//$('#hrefImgInv').text('');
	//$('#textChx_img').text('');
	//$('#hrefImgInv').hide();
	$('#todoChbSoR').hide();
	$('#todoChbPaR').hide();
	$('#addReintegro').modal('show');
}

function CerrarReintegro(idp,idc){
	$('#addReintegro').modal('hide');
	$('#form-reintegro')[0].reset();
	location.href ='editar.php?id='+idp+'&c='+idc;
}

$(function() {

	var chb_a = $("#chb_a");
	
	//Checkbox de la adjudicacion
	
	chb_a.on('click', function(){
		if ( chb_a.is(':checked') ) {
			$("#adjudicacion").attr('disabled',false);
		}
		else{
			$("#adjudicacion").attr('disabled',true);
		}
	});
	
	var chb_se = $("#chb_se");
	
	//Checkbox del seguimiento
	
	chb_se.on('click', function(){
		if ( chb_se.is(':checked') ) {
			$("#seguimiento").attr('disabled',false);
		}
		else{
			$("#seguimiento").attr('disabled',true);
		}
	});
	
	var chb_img = $("#chb_img");
	
	//Checkbox de la imagen del inventario
	
	chb_img.on('click', function(){
		if ( chb_img.is(':checked') ) {
			$("#imagen").attr('disabled',false);
		}
		else{
			$("#imagen").attr('disabled',true);
		}
	});
	
	var chb_doc = $("#chb_doc");
	
	//Checkbox de la documentacion del proyecto
	
	chb_doc.on('click', function(){
		if ( chb_doc.is(':checked') ) {
			$("#documentacion_").attr('disabled',false);
		}
		else{
			$("#documentacion_").attr('disabled',true);
		}
	});
	
	var chb_fac = $("#chb_fac");
	
	//Checkbox de la factura del proyecto
	
	chb_fac.on('click', function(){
		if ( chb_fac.is(':checked') ) {
			$("#factura").attr('disabled',false);
		}
		else{
			$("#factura").attr('disabled',true);
		}
	});
	
	var chb_acp = $("#chb_acp");
	
	//Checkbox de la acreditacion de pago de la factura del proyecto
	
	chb_acp.on('click', function(){
		if ( chb_acp.is(':checked') ) {
			$("#acreditacion_pago").attr('disabled',false);
		}
		else{
			$("#acreditacion_pago").attr('disabled',true);
		}
	});
	
	var chb_sol = $("#chb_sol");
	//Checkbox de la solicitud de modificacion del proyecto
	
	chb_sol.on('click', function(){
		if ( chb_sol.is(':checked') ) {
			$("#solicitud").attr('disabled',false);
		}
		else{
			$("#solicitud").attr('disabled',true);
		}
	});
	
	var chb_res = $("#chb_res");
	
	//Checkbox de la resolucion de modificacion del proyecto
	
	chb_res.on('click', function(){
		if ( chb_res.is(':checked') ) {
			$("#resolucion").attr('disabled',false);
		}
		else{
			$("#resolucion").attr('disabled',true);
		}
	});
	
	var chb_aud = $("#chb_aud");
	
	//Checkbox del auditor del proyecto
	
	chb_aud.on('click', function(){
		if ( chb_aud.is(':checked') ) {
			$("#auditor").attr('disabled',false);
		}
		else{
			$("#auditor").attr('disabled',true);
		}
	});
	
	var chb_dap = $("#chb_dap");
	
	//Checkbox de la acreditacion de pago de la factura del proyecto
	
	chb_dap.on('click', function(){
		if ( chb_dap.is(':checked') ) {
			$("#doc_aportados").attr('disabled',false);
		}
		else{
			$("#doc_aportados").attr('disabled',true);
		}
	});
	
	var chb_inf = $("#chb_inf");
	
	//Checkbox del informe final de auditoria del proyecto
	
	chb_inf.on('click', function(){
		if ( chb_inf.is(':checked') ) {
			$("#inf_final").attr('disabled',false);
		}
		else{
			$("#inf_final").attr('disabled',true);
		}
	});
	
	
	var chb_jus = $("#chb_jus");
	
	//Checkbox del documento de justificacion
	
	chb_jus.on('click', function(){
		if ( chb_jus.is(':checked') ) {
			$("#documentacionJus").attr('disabled',false);
		}
		else{
			$("#documentacionJus").attr('disabled',true);
		}
	});
	
	var chb_sor = $("#chb_sor");
	
	//Checkbox de la solicitud de reintegro
	
	chb_sor.on('click', function(){
		if ( chb_sor.is(':checked') ) {
			$("#solicitudRei").attr('disabled',false);
		}
		else{
			$("#solicitudRei").attr('disabled',true);
		}
	});
	
	var chb_par = $("#chb_par");
	
	//Checkbox del pago de reintegro
	
	chb_par.on('click', function(){
		if ( chb_par.is(':checked') ) {
			$("#pagoRei").attr('disabled',false);
		}
		else{
			$("#pagoRei").attr('disabled',true);
		}
	});
	
	
	$('input[type=file]').change(function () {
				if(this.files[0].size > 5242880)
					alert('¡Atención! Revise el tamaño del documento (máximo 5MB).');
		});
	
	
	$('#TableA').DataTable({ 
				
		stateSave: true, 
						
		"search": {
			"caseInsensitive": true,
		},
								
		"order": [[ 0, "desc" ]],
								
		"language": {
			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ registros",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrando registros del _START_ al _END_ (total _TOTAL_ registros)",
			"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
			"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix":    "",
			"sSearch":         "Buscar:",
			"sUrl":            "", 
			"sInfoThousands":  ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst":    "Primero",
				"sLast":     "Último",
				"sNext":     "Siguiente",
				"sPrevious": "Anterior"
			},					 
			"oAria": {
				"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}
		}						
	} );
	
	$('#TableM').DataTable({ 
				
		stateSave: true, 
						
		"search": {
			"caseInsensitive": true,
		},
								
		"order": [[ 0, "desc" ]],
								
		"language": {
			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ registros",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrando registros del _START_ al _END_ (total _TOTAL_ registros)",
			"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
			"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix":    "",
			"sSearch":         "Buscar:",
			"sUrl":            "", 
			"sInfoThousands":  ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst":    "Primero",
				"sLast":     "Último",
				"sNext":     "Siguiente",
				"sPrevious": "Anterior"
			},					 
			"oAria": {
				"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}
		}						
	} );
	
	$('#TableDoc').DataTable({ 
				
		stateSave: true, 
						
		"search": {
			"caseInsensitive": true,
		},
								
		"order": [[ 1, "asc" ]],
								
		"language": {
			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ registros",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrando registros del _START_ al _END_ (total _TOTAL_ registros)",
			"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
			"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix":    "",
			"sSearch":         "Buscar:",
			"sUrl":            "", 
			"sInfoThousands":  ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst":    "Primero",
				"sLast":     "Último",
				"sNext":     "Siguiente",
				"sPrevious": "Anterior"
			},					 
			"oAria": {
				"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}
		}						
	} );
	
	$('#TableF').DataTable({ 
				
		stateSave: true, 
						
		"search": {
			"caseInsensitive": true,
		},
								
		"order": [[ 1, "asc" ]],
								
		"language": {
			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ registros",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrando registros del _START_ al _END_ (total _TOTAL_ registros)",
			"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
			"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix":    "",
			"sSearch":         "Buscar:",
			"sUrl":            "", 
			"sInfoThousands":  ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst":    "Primero",
				"sLast":     "Último",
				"sNext":     "Siguiente",
				"sPrevious": "Anterior"
			},					 
			"oAria": {
				"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}
		}						
	} );
	
	$('#TableI').DataTable({ 
				
		stateSave: true, 
						
		"search": {
			"caseInsensitive": true,
		},
								
		"order": [[ 1, "asc" ]],
								
		"language": {
			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ registros",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrando registros del _START_ al _END_ (total _TOTAL_ registros)",
			"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
			"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix":    "",
			"sSearch":         "Buscar:",
			"sUrl":            "", 
			"sInfoThousands":  ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst":    "Primero",
				"sLast":     "Último",
				"sNext":     "Siguiente",
				"sPrevious": "Anterior"
			},					 
			"oAria": {
				"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}
		}						
	} );
	
	$('#TableJ').DataTable({ 
				
		stateSave: true, 
						
		"search": {
			"caseInsensitive": true,
		},
								
		"order": [[ 0, "desc" ]],
								
		"language": {
			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ registros",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrando registros del _START_ al _END_ (total _TOTAL_ registros)",
			"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
			"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix":    "",
			"sSearch":         "Buscar:",
			"sUrl":            "", 
			"sInfoThousands":  ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst":    "Primero",
				"sLast":     "Último",
				"sNext":     "Siguiente",
				"sPrevious": "Anterior"
			},					 
			"oAria": {
				"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}
		}						
	} );
	
	$('#TableR').DataTable({ 
				
		stateSave: true, 
						
		"search": {
			"caseInsensitive": true,
		},
								
		"order": [[ 0, "desc" ]],
								
		"language": {
			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ registros",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrando registros del _START_ al _END_ (total _TOTAL_ registros)",
			"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
			"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix":    "",
			"sSearch":         "Buscar:",
			"sUrl":            "", 
			"sInfoThousands":  ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst":    "Primero",
				"sLast":     "Último",
				"sNext":     "Siguiente",
				"sPrevious": "Anterior"
			},					 
			"oAria": {
				"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}
		}						
	} );
	
		   // Remove accented character from search input as well
      $('#myInput').keyup( function () {
        table
          .search(
            jQuery.fn.DataTable.ext.type.search.string( this.value )
          )
          .draw()
      } );
	
});