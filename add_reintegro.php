<?php
//PHP CON FORMULARIO PARA Reintegros
	echo'<div class="modal fade" id="addReintegro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document" style="width: 65%;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 id="tituloRei" name="tituloRei" class="modal-title">Añadir Reintegro</h5>
						<!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>-->
					</div>
				<div class="modal-body">';
	//Usamos el campo oculto reintegro para indicar el formulario con el que estamos trabajando
	//El campo idRei indica en caso de que estemos editando el identificador del reintegro en la BD
	//El campo actionRei indica la acción que se está realizando con el formulario añadir "add" o editar "edit"
	echo '<form id="form-reintegro" class="form-horizontal" onsubmit="mensaje()" role="form" method="POST" action="editar.php?id='.$_SESSION['id'].'&c='.$_SESSION['convocatoria'].'" ENCTYPE="multipart/form-data">';
	echo'<input type="hidden" name="reintegro" value="reintegro">';
	echo'<input type="hidden" id="idRei" name="idRei" value="">';
	echo'<input type="hidden" id="actionRei" name="actionRei" value="add">';
	//Los campos ocultos solRei y pagRei contienen si existe el nombre del fichero de Notificacion y de Respuesta respectivamente
	echo'<div class="checkbox" id="todoChbSoR">
			<label>							
				<input type="checkbox" value="" id="chb_sor"> <div id="textChx_sor">Solo si desea modificarla, active la casilla.</div><a id="hrefSolRei" name="hrefSolRei" href="">Solicitud asignada</a>
				<a id="EliminaSRe" onclick=""><img src="img/cross.png" width=20/></a>
			</label>
		</div>
		<input id="solRei" name="solRei" type="hidden" value="">
		<div class="form-group">
			<label for="solicitudRei" class="form-control-label">Notificacion</label>
				<!--div class="col-lg-10"-->
					<input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
					<input type="file" name="solicitudRei" class="form-control filestyle" id="solicitudRei">
				<!--/div-->
		</div>
		<div class="checkbox" id="todoChbPaR">
			<label>							
				<input type="checkbox" value="" id="chb_par"> <div id="textChx_par">Solo si desea modificarla, active la casilla.</div><a id="hrefPagRei" name="hrefPagRei" href="">Pago asignado</a>
				<a id="EliminaPRe" onclick=""><img src="img/cross.png" width=20/></a>
			</label>
		</div>
		<input id="pagRei" name="pagRei" type="hidden" value="">
		<div class="form-group">
			<label for="pagoRei" class="form-control-label">Respuesta</label>
				<!--div class="col-lg-10"-->
					<input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
					<input type="file" name="pagoRei" class="form-control filestyle" id="pagoRei">
				<!--/div-->
		</div>
		<div class="form-group">
			<label for="observacion" class="form-control-label">Observaciones</label>
			<!--div class="col-lg-10"-->
				<textarea name="observacionREI" id="observacionREI" placeholder="Escriba aquí las observaciones del reintegro..." class="form-control" rows="5" style="width: 360px;"></textarea>
			<!--/div-->
		</div>
		<!-- Botón guardar-->			
		<div class="form-group">
			<div class="col-lg-offset-1">
				<button name="btn_alta" id="btn_alta" type="submit" class="btn btn-default">Guardar</button>
				<button type="button" name="btn_cancel" id="btn_cancel" onclick="CerrarReintegro('.$_SESSION['id'].','.$_SESSION['convocatoria'].');" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
			</div>
		</div>';
			
	echo '</form>';
	echo '    </div>
			</div>
		  </div>
		</div>';
		
		include('resources/modal.php');
?>