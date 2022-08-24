<?php
//PHP CON FORMULARIO PARA INVENTARIOS DE PROYECTOS
	echo'<div class="modal fade" id="addInventario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document" style="width: 65%;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 id="tituloInv" name="tituloInv" class="modal-title">Añadir Inventario</h5>
						<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button> -->
					</div>
				<div class="modal-body">';
	//Usamos el campo oculto inventario para identificar el formulario con el que estamos trabajando.
	//El campo idInv nos indica el ídentificador del inventario en caso de que estemos editando.
	//El campo actionInv nos indica la acción que se está realizando en el formulario añadiendo "add" o editando "edit"
	echo '<form id="form-inventario" class="form-horizontal" onsubmit="mensaje()" role="form" method="POST" action="editar.php?id='.$_SESSION['id'].'&c='.$_SESSION['convocatoria'].'" ENCTYPE="multipart/form-data">';
	echo'<input type="hidden" id="inventario" name="inventario" value="inventario">';
	echo'<input type="hidden" id="idInv" name="idInv" value="">';
	echo'<input type="hidden" id="actionInv" name="actionInv" value="add">';
	$bd = Db::getInstance();
	
	//Seleccionamos aquí el Id, el codigo y la descripción de todos los tipos de documentos que existen y cuyo tipo es 2 "inventario" si fuese tipo 0 sería documentación y tipo 1 factura.
	//Además también comprobamos que solo se carguen los tipo de documentos cuyo organismo sea el mismo de la convocatoria a la que pertenece el proyecto. 
	//Esto es necesario por el checklist que debe tener asociado el proyecto es distinto si la convocatoria depende del ministerio o de la junta de andalucía.
	//También previamente a la creación y subida del inventario debe haberse creado en la zona de administración el tipo de inventario que sea necesario.
	//El campo oculto imgInv los usamos para guardar el nombre del fichero de la hoja de inventario si existe en la BD a la hora de editar.
	//El campo de fecha fechaJusInv se usa para guardar la fecha en que el inventario concreto con el que se está trabajando fue justificado en la plataforma del organismo correspondiente y que 
	//los usuarios de la aplicación deben actualizar con cada justificación
	$sql = "SELECT TD.id as idInv, TD.codigo as CodInv, TD.descripcion as DesInv FROM jp_tipo_documento TD WHERE TD.tipo=2 AND TD.organismo=
(SELECT C.organismo FROM jp_convocatoria C INNER JOIN jp_proyecto P on 
(C.id = P.id_convocatoria) WHERE P.id=".$_GET['id'].") ORDER BY TD.id DESC";
	$rdo = $bd->ejecutar($sql);
	if($bd->totalRegistros($rdo) == 0){
		echo '<div class="alert alert-danger">No existen documentos añadidos. Debe ponerse en contacto con el administrador,
		para que cree algún documento.</div>';
	}						
	else{
		echo '<div class="form-group">
				<label for="tipoInv" class="form-control-label">TipoDocumentacion</label>
				<select name="tipoInv" id="tipoInv" class="form-control" required>Tipo Documentacion';
				foreach($rdo as $fila){
					echo '<option value="'.$fila['idInv'].'">'.$fila['CodInv'].'-'.$fila['DesInv'].'</option>';
				}
		echo '</select>
		      </div>';	
	}
	echo'<div class="checkbox" id="todoChbInv">
			<label>							
				<input type="checkbox" value="" id="chb_img" name="chb_img"> <div id="textChx_img">Solo si desea modificarla, active la casilla.</div><a id="hrefImgInv" name="hrefImgInv" href="">Imagen asignada</a>
				<a id="EliminaImg" onclick=""><img src="img/cross.png" width=20/></a>
			</label>
		</div>
		<input id="imgInv" name="imgInv" type="hidden" value="">
		<div class="form-group">
			<label for="imagen" class="form-control-label">Imagen</label>
				<!--div class="col-lg-10"-->
					<input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
					<input type="file" name="imagen" class="form-control filestyle" id="imagen">
				<!--/div-->
		</div>
		<div class="form-group">
			<label for="observacion" class="form-control-label">Observaciones</label>
			<!--div class="col-lg-10"-->
				<textarea id="observacionINV" name="observacionINV" placeholder="Escriba aquí las observaciones del inventario..."  class="form-control" rows="5" style="width: 360px;">';
				if(isset($_POST['titulo'])) echo $_POST['titulo'];
				echo '</textarea>
			<!--/div-->
		</div>
		<div class="form-group">
			<label for="fechaJusDoc" class="form-control-label">Fecha Justificacion (dd/mm/aaaa)</label><br/>
			<input type="date" id="fechaJusInv" name="fechaJusInv" step="1" min="1900-01-01" max="3000-12-31" value="">
		</div>
		<!-- Botón guardar-->			
		<div class="form-group">
			<div class="col-lg-offset-1">
				<button name="btn_alta" id="btn_alta" type="submit" class="btn btn-default">Guardar</button>
				<button type="button" name="btn_cancel" id="btn_cancel" onclick="CerrarInventario('.$_SESSION['id'].','.$_SESSION['convocatoria'].');" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
			</div>
		</div>';
			
	echo '</form>';
	echo '    </div>
			</div>
		  </div>
		</div>';
		
		include('resources/modal.php');
?>