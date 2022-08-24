<?php
//PHP CON FORMULARIO PARA JUSTIFICACIÓN DOCUMENTAL
	echo'<div class="modal fade" id="addJustificacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document" style="width: 65%;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 id="tituloJus" name="tituloJus" class="modal-title">Añadir Justificación</h5>
						<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button> -->
					</div>
				<div class="modal-body">';
	//Usamos el campo oculto jsutificación para indicar el formulario con el que estamos trabajando 
	//El campo idJus contiene en caso de que estemos editanto el id de la justificación
	//El campo actionJus nos indica la acción que estamos realizando con el formulario añadir "add" o editar "edit"
	echo '<form id="form-justificacion" class="form-horizontal" onsubmit="mensaje()" role="form" method="POST" action="editar.php?id='.$_SESSION['id'].'&c='.$_SESSION['convocatoria'].'" ENCTYPE="multipart/form-data">';
	echo'<input type="hidden" id="justificacion" name="justificacion" value="justificacion">';
	echo'<input type="hidden" id="idJus" name="idJus" value="">';
	echo'<input type="hidden" id="actionJus" name="actionJus" value="add">';
	
	//El campo oculto docJus contiene en el caso de que exista el nombre dek documento de justificacion
	//El campo de fecha fechaJus indicará la fecha exacta en la que se hizo la justificacion
	echo'<div class="form-group">
			<label for="observacion" class="form-control-label">Numero_Solicitud</label>
			<input type="text" class="form-control" name="n_solicitud" id="n_solicitud"
						placeholder="Numero Solicitud" required value="">
		</div>
		<div class="checkbox" id="todoChbJus">
			<label>							
				<input type="checkbox" value="" id="chb_jus"> <div id="textChx_jus">Solo si desea modificarla, active la casilla.</div><a id="hrefDocJus" name="hrefDocJus" href="">Auditor asignado</a>
			</label>
		</div>
		<input id="docJus" name="docJus" type="hidden" value="">
		<div class="form-group">
			<label for="documentacionJus" class="form-control-label">Documentacion</label>
				<!--div class="col-lg-10"-->
					<input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
					<input type="file" name="documentacionJus" class="form-control filestyle" id="documentacionJus" >
				<!--/div-->
		</div>
		<div class="form-group">
			<label for="fechaJus" class="form-control-label">Fecha</label><br/>
			<input type="date" id="fechaJus" name="fechaJus" step="1" min="1900-01-01" max="3000-12-31" value="">
		</div>
		<!-- Observaciones -->
		<div class="form-group">
			<label for="observacion" class="form-control-label">Observaciones</label>
			<!--div class="col-lg-10"-->
				<textarea id="observacionJUS" name="observacionJUS" placeholder="Escriba aquí las observaciones de la justificacion documental..." class="form-control" rows="5" style="width: 360px;"></textarea>
			<!--/div-->
		</div>
		<!-- Botón guardar-->			
		<div class="form-group">
			<div class="col-lg-offset-1">
				<button name="btn_mod" id="btn_mod" type="submit" class="btn btn-default">Guardar</button>
				<button type="button" name="btn_cancel" id="btn_cancel" onclick="CerrarAuditoria();" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
			</div>
		</div>';
			
	echo '</form>';
	echo '<!--div class="modal-footer">
        <button type="button" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div-->
				</div>
			</div>
		  </div>
		</div>';
		
		include('resources/modal.php');
?>