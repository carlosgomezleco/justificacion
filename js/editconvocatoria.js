
function eliminar_logo(idl, idc){
		if(confirm("¿Está seguro de que desea eliminar el logo ("+idl+")?") == true)   
			window.location.href = "eliminar.php?idl="+idl+"&idc="+idc+"&resource=logo";
}

function eliminar_bases(idc){
		if(confirm("¿Está seguro de que desea eliminar las bases de la convocatoria?") == true)   
			window.location.href = "eliminar.php?idc="+idc+"&resource=basesConvo";
}

function eliminar_doc_convocatoria(idc){
		if(confirm("¿Está seguro de que desea eliminar el documento de la convocatoria?") == true)   
			window.location.href = "eliminar.php?idc="+idc+"&resource=docConvo";
}

function eliminar_resolucion(idc){
		if(confirm("¿Está seguro de que desea eliminar la resolución de la convocatoria?") == true)   
			window.location.href = "eliminar.php?idc="+idc+"&resource=resConvo";
}

$(function() {
	
	var convo;	
				
	//Evento para los checkboxs asociados a los input file
	var chb_c = $("#chb_c");
	var chb_r = $("#chb_r");
	var chb_b = $("#chb_b");
	
	//Checkbox de la convocatoria
	chb_c.on('click', function(){
		if ( chb_c.is(':checked') ) {
			$("#convocatoria").attr('disabled',false);
		}
		else{
			$("#convocatoria").attr('disabled',true);
		}
	});
			
	//Checkbox de la resolución
	chb_r.on('click', function(){
		if ( chb_r.is(':checked') ) {
			$("#resolucion").attr('disabled',false);
		}
		else{
			$("#resolucion").attr('disabled',true);
		}
	});
	
	//Checkbox de las bases
	chb_b.on('click', function(){
		if ( chb_b.is(':checked') ) {
			$("#bases").attr('disabled',false);
		}
		else{
			$("#bases").attr('disabled',true);
		}
	});
	
	//Si ya había convocatoria asignada, no habilitamos el input file
	if($("#convo").val() !== undefined){
		$("#convocatoria").attr('disabled',true);
	}	
			
	if($("#resol").val() !== undefined){
		$("#resolucion").attr('disabled',true);
	}
	
	if($("#bas").val() !== undefined){
		$("#bases").attr('disabled',true);
	}
	
	$('#TableL').DataTable({ 
				
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