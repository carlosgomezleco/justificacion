<?php
//PHP CON FORMULARIO PARA LOGOS
	
	echo'<div class="modal fade" id="addLogo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document" style="width: 65%;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 id="tituloLog" name="tituloLog" class="modal-title">Añadir Logo</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				<div class="modal-body">';
	echo '<form id="form-logo" class="form-horizontal" onsubmit="return validaLogo();" role="form" method="POST" action="index.php" ENCTYPE="multipart/form-data">';
	echo'<input type="hidden" name="logo" value="logo">';
	echo'<input type="hidden" id="idLog" name="idLog" value="">';
	echo'<input type="hidden" id="actionLog" name="actionLog" value="add">';
		
	echo'<div class="form-group">
			<label for="nombreLogo" class="form-control-label">Nombre</label>
			<input type="text" class="form-control" size="40" name="nombreLogo" id="nombreLogo"
			title="Se necesita un nombre" placeholder="Indique un nombre de logo (máx. 40 caracteres)" required autofocus
			value="">
		</div>
		<div class="checkbox" id="todoChbLog">
			<label>							
				<input type="checkbox" value="" id="chb_i"> <div id="textChx_i">Solo si desea modificarla, active la casilla.</div><a id="hrefImagen" name="hrefImagen" href="">Imagen asignada</a>
			</label>
		</div>
		<input id="imgLog" type="hidden" value="">
		<div class="form-group">
			<label for="imagenLogo" class="form-control-label">Imagen</label>
			<input type="file" name="imagenLogo" class="form-control filestyle" id="imagenLogo">
		</div>
		<div class="form-group">
			<label for="fechaIniLogo" class="form-control-label">Fecha Inicio (dd/mm/aaaa)</label><br/>
			<input type="date" id="fechaIniLogo" name="fechaIniLogo" step="1" min="1900-01-01" max="3000-12-31" value="">
		</div>
		<div class="form-group">
			<label for="fechaFinLogo" class="form-control-label">Fecha Fin (dd/mm/aaaa)</label><br/>
			<input type="date" id="fechaFinLogo" name="fechaFinLogo" step="1" min="1900-01-01" max="3000-12-31" value="">
		</div>
		<!-- Botón guardar-->			
		<div class="form-group">
			<div class="col-lg-offset-1">
				<button name="btn_alta" id="btn_alta" type="submit" class="btn btn-default">Guardar</button>
				<button type="button" name="btn_cancel" id="btn_cancel" onclick="CerrarLogo();" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
			</div>
		</div>';
			
	echo '</form>';
	echo '		</div>
			</div>
		  </div>
		</div>';
		
		include('../resources/modal.php');
?>