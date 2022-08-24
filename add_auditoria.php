<?php
//PHP CON FORMULARIO PARA AUDITORIAS DE PROYECTOS
	echo'<div class="modal fade" id="addAuditoria" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document" style="width: 65%;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 id="tituloMod" name="tituloAud" class="modal-title">Añadir Auditoria</h5>
						<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button> -->
					</div>
				<div class="modal-body">';
	//Usamos el campo oculto auditoría para indicar con la tabla con la que estamos trabajando
	//El campo IdAud para guardar cuando estamos editando el Id de la auditoría
	//El campo actionAud nos indica si la acción que vamos a realizar es añadir "add" o editar "edit"
	//El campo audAud lo usamos para guardar en caso de que exista en la BD el nombre del fichero de auditor.
	//El campo dapAud lo usamos para guardar en caso de que exista en la BD el nombre del fichero de los documentos aportados.
	//El campo infAud lo usamos para guardar en caso de que exista en la BD el nombre del fichero de informe final
	
	echo '<form id="form-auditoria" class="form-horizontal" onsubmit="mensaje()" role="form" method="POST" action="editar.php?id='.$_SESSION['id'].'&c='.$_SESSION['convocatoria'].'" ENCTYPE="multipart/form-data">';
	echo'<input type="hidden" name="auditoria" value="auditoria">';
	echo'<input type="hidden" id="idAud" name="idAud" value="">';
	echo'<input type="hidden" id="actionAud" name="actionAud" value="add">';
	
	echo'<div class="checkbox" id="todoChbAud">
			<label>							
				<input type="checkbox" value="" id="chb_aud"> <div id="textChx_aud">Solo si desea modificarla, active la casilla.</div><a id="hrefAudAud" name="hrefAudAud" href="">Auditor asignado</a>
				<a id="EliminaAud" onclick=""><img src="img/cross.png" width=20/></a>
			</label>
		</div>
		<input id="audAud" name="audAud" type="hidden" value="">
		<div class="form-group">
			<label for="auditor" class="form-control-label">Auditor</label>
				<!--div class="col-lg-10"-->
					<input type="file" name="auditor" class="form-control filestyle" id="auditor" >
				<!--/div-->
		</div>
		<div class="checkbox" id="todoChbDAp">
			<label>							
				<input type="checkbox" value="" id="chb_dap"> <div id="textChx_dap">Solo si desea modificarla, active la casilla.</div><a id="hrefDapAud" name="hrefDapAud" href="">Documentos Aportados asignados</a>
				<a id="EliminaDap" onclick=""><img src="img/cross.png" width=20/></a>
			</label>
		</div>
		<input id="dapAud" name="dapAud" type="hidden" value="">
		<div class="form-group">
			<label for="doc_aportados" class="form-control-label">Documentos_Aportados</label>
				<!--div class="col-lg-10"-->
					<input type="file" name="doc_aportados" class="form-control filestyle" id="doc_aportados">
				<!--/div-->
		</div>
		<div class="checkbox" id="todoChbInF">
			<label>							
				<input type="checkbox" value="" id="chb_inf"> <div id="textChx_inf">Solo si desea modificarla, active la casilla.</div><a id="hrefInFAud" name="hrefInFAud" href="">Informe Final asignado</a>
				<a id="EliminaInf" onclick=""><img src="img/cross.png" width=20/></a>
			</label>
		</div>
		<input id="infAud" name="infAud" type="hidden" value="">
		<div class="form-group">
			<label for="inf_final" class="form-control-label">Informe_Final</label>
				<!--div class="col-lg-10"-->
					<input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
					<input type="file" name="inf_final" class="form-control filestyle" id="inf_final">
				<!--/div-->
		</div>
		<!-- Observaciones -->
		<div class="form-group">
			<label for="observacion" class="form-control-label">Observaciones</label>
			<!--div class="col-lg-10"-->
				<textarea id="observacionAUD" name="observacionAUD" placeholder="Escriba aquí las observaciones de la auditoria..." class="form-control" rows="5" style="width: 360px;"></textarea>
			<!--/div-->
		</div>
		<!-- Botón guardar-->			
		<div class="form-group">
			<div class="col-lg-offset-1">
				<button name="btn_mod" id="btn_mod" type="submit" class="btn btn-default">Guardar</button>
				<button type="button" name="btn_cancel" id="btn_cancel" onclick="CerrarAuditoria('.$_SESSION['id'].','.$_SESSION['convocatoria'].');" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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