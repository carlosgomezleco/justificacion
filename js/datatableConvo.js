$(document).ready(function(){
	$('#TableC').DataTable({ 
				
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