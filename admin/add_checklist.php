<?php
//PHP CON FORMULARIO PARA CHECKLIST
	
	echo'<div class="modal fade" id="addChecklist" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document" style="width: 65%;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 id="tituloChe" name="tituloChe" class="modal-title">Añadir Checklist</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				<div class="modal-body">';
	echo '<form id="form-checklist" class="form-horizontal" onsubmit="mensaje()" role="form" method="POST" action="index.php" ENCTYPE="multipart/form-data">';
	echo'<input type="hidden" name="checklist" value="checklist">';
	echo'<input type="hidden" id="idChe" name="idChe" value="">';
	echo'<input type="hidden" id="actionChe" name="actionChe" value="add">';
		
	echo'<div class="form-group">
			<label for="tipoChecklist" class="form-control-label">Tipo</label>
				
					<select id="tipoChecklist" name="tipoChecklist" class="form-control" required>
					<option value="0">Documento</option>
					<option value="1">Factura</option>
					<option value="2">Inventario</option>
					</select>
				
		</div>
		<div class="form-group">
			<label for="codigoChecklist" class="form-control-label">Codigo</label>
			<input type="text" class="form-control" size="15" name="codigoChecklist" id="codigoChecklist"
			title="Se necesita un nombre" placeholder="Indique el codigo (máx. 15 caracteres)" required autofocus
			value="">
		</div>
		<div class="form-group">
			<label for="descripcionChecklist" class="form-control-label">Descripcion</label>
			<input type="text" class="form-control" size="100" name="descripcionChecklist" id="descripcionChecklist"
			title="Se necesita un nombre" placeholder="Indique la descripcion (máx. 100 caracteres)" required autofocus
			value="">
		</div>
		<div class="form-group">
			<label for="organismoChecklist" class="form-control-label">Organismo</label>
				<select id="organismoChecklist" name="organismoChecklist" class="form-control" required>
				<option value="0">Junta de Andalucía</option>
				<option value="1">Ministerio</option>
				</select>
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