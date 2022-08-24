<?php
//PHP CON FORMULARIO PARA MODIFCACIONES DE PROYECTOS
	echo'<div class="modal fade" id="addModificacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document" style="width: 65%;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 id="tituloMod" name="tituloMod" class="modal-title">Añadir Modificacion</h5>
						<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button> -->
					</div>
				<div class="modal-body">';
	//Usamos el campo oculto modificacion para indicar el formulario con el que estamos trabajando
	//El campo oculto idMod indica en caso que estemos editando el identificador de la modificación en la BD
	//El campo actionMod indica la acción que estamos realizando con el formulario añadir "add" o editar "edit"
	echo '<form id="form-modificacion" class="form-horizontal" onsubmit="mensaje()" role="form" method="POST" action="editar.php?id='.$_SESSION['id'].'&c='.$_SESSION['convocatoria'].'" ENCTYPE="multipart/form-data">';
	echo'<input type="hidden" id="modificacion" name="modificacion" value="modificacion">';
	echo'<input type="hidden" id="idMod" name="idMod" value="">';
	echo'<input type="hidden" id="actionMod" name="actionMod" value="add">';
	
	//El campo solMod y resMod lo usamos para guardar en caso de que existan el nombre del fichero de la solicitud y la resolucion de la modificacion respectivamente
	echo'<div class="checkbox" id="todoChbSol">
			<label>							
				<input type="checkbox" value="" id="chb_sol"> <div id="textChx_sol">Solo si desea modificarla, active la casilla.</div><a target="_blank" id="hrefSolMod" name="hrefSolMod" href="">Solicitud asignada</a>
				<a id="EliminaSol" onclick=""><img src="img/cross.png" width=20/></a>
			</label>
		</div>
		<input id="solMod" name="solMod" type="hidden" value="">
		<div class="form-group">
			<label for="solicitud" class="form-control-label">Solicitud</label>
				<!--div class="col-lg-10"-->
					<input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
					<input type="file" name="solicitud" class="form-control filestyle" id="solicitud" >
				<!--/div-->
		</div>
		<div class="checkbox" id="todoChbRes">
			<label>							
				<input type="checkbox" value="" id="chb_res"> <div id="textChx_res">Solo si desea modificarla, active la casilla.</div><a target="_blank" id="hrefResMod" name="hrefResMod" href="">Resolucion asignada</a>
				<a id="EliminaRes" onclick=""><img src="img/cross.png" width=20/></a>
			</label>
		</div>
		<input id="resMod" name="resMod" type="hidden" value="">
		<div class="form-group">
			<label for="resolucion" class="form-control-label">Resolucion</label>
				<!--div class="col-lg-10"-->
					<input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
					<input type="file" name="resolucion" class="form-control filestyle" id="resolucion">
				<!--/div-->
		</div>
		<!-- Título -->
		<div class="form-group">
			<label for="observacion" class="form-control-label">Observaciones</label>
			<!--div class="col-lg-10"-->
				<textarea id="observacionMOD" name="observacionMOD" placeholder="Escriba aquí las observaciones de la modificacion..." class="form-control" rows="5" style="width: 360px;">';
				if(isset($_POST['titulo'])) echo $_POST['titulo'];
				echo '</textarea>
			<!--/div-->
		</div>
		<!-- Botón guardar-->			
		<div class="form-group">
			<div class="col-lg-offset-1">
				<button name="btn_mod" id="btn_mod" type="submit" class="btn btn-default">Guardar</button>
				<button type="button" name="btn_cancel" id="btn_cancel" onclick="CerrarModificacion('.$_SESSION['id'].','.$_SESSION['convocatoria'].');" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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