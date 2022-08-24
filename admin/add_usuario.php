<?php
//PHP CON FORMULARIO PARA USUARIOS
	
	echo'<div class="modal fade" id="addUsuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document" style="width: 65%;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 id="tituloChe" name="tituloChe" class="modal-title">Añadir Usuario</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				<div class="modal-body">';
	echo '<form id="form-usuario" class="form-horizontal" onsubmit="mensaje()" role="form" method="POST" action="index.php" ENCTYPE="multipart/form-data">';
	echo'<input type="hidden" name="usuarios" value="usuarios">';
	echo'<input type="hidden" id="idUsu" name="idUsu" value="">';
	echo'<input type="hidden" id="actionUsu" name="actionUsu" value="add">';
		
	echo'<div class="form-group">
			<label for="nombreUsuario" class="form-control-label">Usuario</label>
			<input type="text" class="form-control" size="70" name="nombreUsuario" id="nombreUsuario"
			title="Se necesita un nombre" placeholder="Indique el nombre de usuario" required autofocus
			value="">
		</div>
		<div class="form-group">
			<label for="permisos" class="form-control-label">Tipo</label>
				
					<select id="permisos" name="permisos" class="form-control" required>
					<option value="0">Administrador</option>
					<option value="1">Personal Administracion Servicios</option>
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