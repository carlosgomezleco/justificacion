function AddLogo(){
	$('#tituloLog').text('Añadir Logo');
	$('#actionLog').val('add');
	//$('#chb_i').hide();
	//$('#hrefImagen').text('');
	//$('#textChx_i').text('');
	//$('#hrefImagen').hide();
	$('#todoChbLog').hide();
	$('#addLogo').modal('show');
}

function CerrarLogo(){
	$('#addLogo').modal('hide');
	$('#form-logo')[0].reset();
}
function editar_logo(idl){
	//window.location.href = "edit_documentacion.php?idp="+idp+"&idc="+idc+"&idd="+idd;
	$.get('../ajax/DatosJSON.php?info=LOGO&id='+idl,function(resp){
		
		$('#tituloMLog').text('Editar Logo');
		$('#nombreLogo').val(resp.nombre);
		$('#fechaIniLogo').val(resp.fecha_inicio);
		$('#fechaFinLogo').val(resp.fecha_fin);
		if(resp.imagen!=""){
			$('#hrefImagen').attr("href", "/docs/logos/"+resp.imagen);
			$('#imgLog').val(resp.imagen);
			$('#chb_i').show();
			$('#hrefImagen').show();
			$('#todoChbLog').show();
			$("#imagenLogo").attr('disabled',true);
			$('#hrefImagen').text('Imagen asignada');
			$('#textChx_i').text('Solo si desea modificarla, active la casilla.');
		}else{
			//ocultar campos
		}
		$('#idLog').val(idl);
		$('#actionLog').val('edit');
		//Añadir el link con el documento si lo tiene.
		$('#addLogo').modal('show');
	});
}

function eliminar_logo(idl){
	if(confirm("¿Está seguro de que desea eliminar el logo ("+idl+")?") == true)   
			window.location.href = "eliminarAdmin.php?id="+idl+"&resource=logo";
}

function validaLogo(){
	var FechIni="";
	var FechFin="";
	if($('#fechaIniLogo').val()!=null){
		var arrayFecha=$('#fechaIniLogo').val().split("-");
		FechIni = new Date(arrayFecha[1]+"/"+arrayFecha[0]+"/"+arrayFecha[2]);	
		
	}
	if($('#fechaFinLogo').val()!=null){
		var arrayFecha=$('#fechaFinLogo').val().split("-");
		FechFin = new Date(arrayFecha[1]+"/"+arrayFecha[0]+"/"+arrayFecha[2]);	
		
	}

	if( (new Date(FechIni).getTime() > new Date(FechFin).getTime()))
	{
		alert('La fecha de inicio no puede ser posterior a la de finalizacion');
		return false;
	}
	//comprobar que fecha inicio no sea mayor que fecha fin
	mensaje();
	return true;
}

function AddChecklist(){
	$('#tituloChe').text('Añadir Checklist');
	$('#actionChe').val('add');
	$('#addChecklist').modal('show');
}

function CerrarChecklist(){
	$('#addChecklist').modal('hide');
	$('#form-logo')[0].reset();
}

function editar_Checklist(idc){
	//window.location.href = "edit_documentacion.php?idp="+idp+"&idc="+idc+"&idd="+idd;
	$.get('../ajax/DatosJSON.php?info=CHECKLIST&id='+idc,function(resp){
		
		$('#tituloChe').text('Editar Checklist');
		$('#tipoChecklist').val(resp.tipo);
		$('#codigoChecklist').val(resp.codigo);
		$('#descripcionChecklist').val(resp.descripcion);
		$('#organismoChecklist').val(resp.organismo);
		$('#idChe').val(idc);
		$('#actionChe').val('edit');
		//Añadir el link con el documento si lo tiene.
		$('#addChecklist').modal('show');
	});
}

function eliminar_Checklist(idc){
	if(confirm("¿Está seguro de que desea eliminar el tipo de documento ("+idc+")?") == true)   
			window.location.href = "eliminarAdmin.php?id="+idc+"&resource=checklist";
}

///////////////////////////////////////////////////////////////////////////////

function AddUsuario(){
	$('#tituloUsu').text('Añadir Usuario');
	$('#actionUsu').val('add');
	$('#nombreUsuario').val('');
	$('#idUsu').val('');
	$('#addUsuario').modal('show');
}

function CerrarUsuario(){
	$('#addUsuario').modal('hide');
	$('#form-usuario')[0].reset();
}

function editar_Usuario(idu){
	//window.location.href = "edit_documentacion.php?idp="+idp+"&idc="+idc+"&idd="+idd;
	$.get('../ajax/DatosJSON.php?info=USUARIO&id='+idu,function(resp){
		
		$('#tituloUsu').text('Editar Usuario');
		$('#nombreUsuario').val(resp.usuario);
		$('#permisos').val(resp.permisos);
		$('#idUsu').val(idu);
		$('#actionUsu').val('edit');
		//Añadir el link con el documento si lo tiene.
		$('#addUsuario').modal('show');
	});
}

function eliminar_Usuario(idu){
	if(confirm("¿Está seguro de que desea eliminar al usuario ("+idu+")?") == true)   
			window.location.href = "eliminarAdmin.php?id="+idu+"&resource=usuario";
}

$(function() {

	var chb_i = $("#chb_i");
	
	//Checkbox de la imagen del logo
	
	chb_i.on('click', function(){
		if ( chb_i.is(':checked') ) {
			$("#imagenLogo").attr('disabled',false);
		}
		else{
			$("#imagenLogo").attr('disabled',true);
		}
	});
	
	$('#TableCheck').DataTable({ 
				
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
	
	$('#TableLog').DataTable({ 
				
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
	
	$('#TableUsu').DataTable({ 
				
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
	} )
	
	// Remove accented character from search input as well
      $('#myInput').keyup( function () {
        table
          .search(
            jQuery.fn.DataTable.ext.type.search.string( this.value )
          )
          .draw()
      } );
	
});