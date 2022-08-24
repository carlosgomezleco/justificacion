<?php
//PHP CON FORMULARIO PARA DOCUMENTOS DE PROYECTOS
	
	echo'<div class="modal fade" id="addDocumentacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document" style="width: 65%;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 id="tituloDoc" name="tituloDoc" class="modal-title">Añadir Documentacion</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				<div class="modal-body">';
	//Usamos el campo oculto documentación para identificar el formulario con el que estamos trabajando.
	//El campo idDoc nos indica el identificador de la documentacion en caso de que estemos editando.
	//El campo actionDoc nos indica la acción que se está realizando en el formulario añadiendo "add" o editando "edit"
	echo '<form id="form-documentacion" class="form-horizontal" onsubmit="mensaje()" role="form" method="POST" action="editar.php?id='.$_SESSION['id'].'&c='.$_SESSION['convocatoria'].'" ENCTYPE="multipart/form-data">';
	echo'<input type="hidden" id="documentacion" name="documentacion" value="documentacion">';
	echo'<input type="hidden" id="idDoc" name="idDoc" value="">';
	echo'<input type="hidden" id="actionDoc" name="actionDoc" value="add">';
	//Seleccionamos aquí el Id, el codigo y la descripción de todos los tipos de documentos que existen y cuyo tipo es 0 "documentación" si fuese tipo 1 sería factura y tipo 2 inventario.
	//Además también comprobamos que solo se carguen los tipo de documentos cuyo organismo sea el mismo de la convocatoria a la que pertenece el proyecto. 
	//Esto es necesario por el checklist que debe tener asociado el proyecto es distinto si la convocatoria depende del ministerio o de la junta de andalucía.
	//También previamente a la creación y subida de documentación debe haberse creado en la zona de administración el tipo de documento que sea necesario.
	//El campo oculto fileDoc lo usamos para guardar el nombre del documento si existe en la BD a la hora de editar.
	//El campo de fecha fechaJusDoc se usa para guardar la fecha en que el documento concreto con el que se está trabajando fue justificado en la plataforma del organismo correspondiente y que 
	//los usuarios de la aplicación deben actualizar con cada justificación
	
	$bd = Db::getInstance();
	$sql = "SELECT TD.id as ID, TD.codigo as COD, TD.descripcion as DES FROM jp_tipo_documento TD WHERE TD.tipo=0 AND TD.organismo=
(SELECT C.organismo FROM jp_convocatoria C INNER JOIN jp_proyecto P on 
(C.id = P.id_convocatoria) WHERE P.id='".$_GET['id']."') ORDER BY TD.id DESC";
	$rdo = $bd->ejecutar($sql);
	
	if($bd->totalRegistros($rdo) == 0){
		echo '<div class="alert alert-danger">No existen documentos añadidos. Debe ponerse en contacto con el administrador,
		para que cree algún documento.</div>';
	}						
	else{
		echo '<div class="form-group">
				<label for="tipoDoc" class="form-control-label">TipoDocumentacion</label>
				<!--div class="col-lg-10"-->
					<select name="tipoDoc" id="tipoDoc" class="form-control" required ';
				if($_SESSION['permisos']!=0){
					echo 'disabled';
				}
				echo'>TipoDocumentacion';
					foreach($rdo as $fila){
						echo '<option value="'.$fila['ID'].'">'.$fila['COD'].'-'.$fila['DES'].'</option>';
					}
		echo '</select>
		      <!--/div-->
			  </div>';	
	}	
	echo'<div class="checkbox" id="todoChbDoc">
			<label>							
				<input type="checkbox" value="" id="chb_doc" name="chb_doc"> <div id="textChx_doc">Solo si desea modificarla, active la casilla.</div><a target="_blank" id="hrefDoc" name="hrefDoc" href="">Documentacion asignada</a>
				<a id="EliminaDoc" onclick=""><img src="img/cross.png" width=20/></a>
			</label>
		</div>
		<input id="fileDoc" name="fileDoc" type="hidden" value="">
		<div class="form-group">
			<label for="documentacion" class="form-control-label">documentacion</label>
				<!--div class="col-lg-10"-->
					<input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
					<input type="file" name="documentacion" class="form-control filestyle" id="documentacion_">
				<!--/div-->
		</div>
		<div class="form-group">
			<label for="observacion" class="form-control-label">Observaciones</label>
			<!--div class="col-lg-10"-->
				<textarea name="observacionDOC" placeholder="Escriba aquí las observaciones de la documentacion..." id="observacionDOC" class="form-control" rows="5" style="width: 360px;">';
				if(isset($_POST['titulo'])) echo $_POST['titulo'];
				echo '</textarea>
			<!--/div-->
		</div>
		<div class="form-group">
			<label for="fechaJusDoc" class="form-control-label">Fecha Justificacion (dd/mm/aaaa)</label><br/>
			<input type="date" id="fechaJusDoc" name="fechaJusDoc" step="1" min="1900-01-01" max="3000-12-31" value="">
		</div>
		<!-- Botón guardar-->			
		<div class="form-group">
			<div class="col-lg-offset-1">
				<button name="btn_alta" id="btn_alta" type="submit" class="btn btn-default">Guardar</button>
				<button type="button" name="btn_cancel" id="btn_cancel" onclick="CerrarDocumentacion('.$_SESSION['id'].','.$_SESSION['convocatoria'].');" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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