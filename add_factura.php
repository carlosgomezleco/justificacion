<?php
//PHP CON FORMULARIO PARA FACTURAS DE PROYECTOS
	echo'<div class="modal fade" id="addFactura" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document" style="width: 65%;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 id="tituloFac" name="tituloFac" class="modal-title">Añadir Factura</h5>
						<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button> -->
					</div>
				<div class="modal-body">';
	//Usamos el campo oculto facturación para identificar el formulario con el que estamos trabajando.
	//El campo idFac nos indica el ídentificador de la factura en caso de que estemos editando.
	//El campo actionFac nos indica la acción que se está realizando en el formulario añadiendo "add" o editando "edit"
	echo '<form id="form-factura" class="form-horizontal" onsubmit="mensaje()" role="form" method="POST" action="editar.php?id='.$_SESSION['id'].'&c='.$_SESSION['convocatoria'].'" ENCTYPE="multipart/form-data">';
	echo'<input type="hidden" id="facturacion" name="facturacion" value="facturacion">';
	echo'<input type="hidden" id="idFac" name="idFac" value="">';
	echo'<input type="hidden" id="actionFac" name="actionFac" value="add">';
	$bd = Db::getInstance();
	//Seleccionamos aquí el Id, el codigo y la descripción de todos los tipos de documentos que existen y cuyo tipo es 1 "factura" si fuese tipo 0 sería documentación y tipo 2 inventario.
	//Además también comprobamos que solo se carguen los tipo de documentos cuyo organismo sea el mismo de la convocatoria a la que pertenece el proyecto. 
	//Esto es necesario por el checklist que debe tener asociado el proyecto es distinto si la convocatoria depende del ministerio o de la junta de andalucía.
	//También previamente a la creación y subida de las facturas debe haberse creado en la zona de administración el tipo de factura que sea necesario.
	//Los campos ocultos facFac y acpFac los usamos para guardar el nombre del fichero de la factura y la acreditacion de pago respectivamente si existen en la BD a la hora de editar.
	//El campo de fecha fechaJusFac se usa para guardar la fecha en que la factura concreta con la que se está trabajando fue justificada en la plataforma del organismo correspondiente y que 
	//los usuarios de la aplicación deben actualizar con cada justificación
	
	$sql = "SELECT TD.id as idFac, TD.codigo as CodFac, TD.descripcion as FacDes FROM jp_tipo_documento TD WHERE TD.tipo=1 AND TD.organismo=
(SELECT C.organismo FROM jp_convocatoria C INNER JOIN jp_proyecto P on 
(C.id = P.id_convocatoria) WHERE P.id=".$_GET['id'].") ORDER BY TD.id DESC";
	$rdo = $bd->ejecutar($sql);
	if($bd->totalRegistros($rdo) == 0){
		echo '<div class="alert alert-danger">No existen documentos añadidos. Debe ponerse en contacto con el administrador,
		para que cree algún documento.</div>';
	}						
	else{
		echo '<div class="form-group">
				<label for="tipoFac" class="form-control-label">TipoDocumentacion</label>
				<!--div class="col-lg-10"-->
					<select name="tipoFac" id="tipoFac" class="form-control" required>Tipo Documentacion';
					foreach($rdo as $fila){
						echo '<option value="'.$fila['idFac'].'">'.$fila['CodFac'].'-'.$fila['FacDes'].'</option>';
					}
		echo '</select>
		      <!--/div-->
			  </div>';	
	}
	echo'<div class="checkbox" id="todoChbFac">
			<label>							
				<input type="checkbox" value="" id="chb_fac" name="chb_fac"> <div id="textChx_fac">Solo si desea modificarla, active la casilla.</div><a target="_blank" id="hrefFacFac" name="hrefFacFac" href="">Factura asignada</a>
				<a id="EliminaFac" onclick=""><img src="img/cross.png" width=20/></a>
			</label>
		</div>
		<input id="facFac" name="facFac" type="hidden" value="">
		<div class="form-group">
			<label for="factura" class="form-control-label">Factura</label>
				<!--div class="col-lg-10"-->
					<input type="file" name="factura" class="form-control filestyle" id="factura">
					<input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
				<!--/div-->
		</div>
		<div class="checkbox" id="todoChbAcp">
			<label>							
				<input type="checkbox" value="" id="chb_acp" name="chb_acp"> <div id="textChx_acp">Solo si desea modificarla, active la casilla.</div><a target="_blank" id="hrefAcpFac" name="hrefAcpFac" href="">Pago asignado</a>
				<a id="EliminaAcp" onclick=""><img src="img/cross.png" width=20/></a>
			</label>
		</div>
		<input id="acpFac" name="acpFac" type="hidden" value="">
		<div class="form-group">
			<label for="acreditacion_pago" class="form-control-label">Acreditacion de Pago</label>
				<!--div class="col-lg-10"-->
					<input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
					<input type="file" name="acreditacion_pago" class="form-control filestyle" id="acreditacion_pago">
				<!--/div-->
		</div>
		<div class="form-group">
			<label for="observacion" class="form-control-label">Observaciones</label>
			<!--div class="col-lg-10"-->
				<textarea name="observacionFAC" id="observacionFAC" placeholder="Escriba aquí las observaciones de la factura..." class="form-control" rows="5" style="width: 360px;">';
				if(isset($_POST['titulo'])) echo $_POST['titulo'];
				echo '</textarea>
			<!--/div-->
		</div>
		<div class="form-group">
			<label for="fechaJusDoc" class="form-control-label">Fecha Justificacion (dd/mm/aaaa)</label><br/>
			<input type="date" id="fechaJusFac" name="fechaJusFac" step="1" min="1900-01-01" max="3000-12-31" >
		</div>
		<!-- Botón guardar-->			
		<div class="form-group">
			<div class="col-lg-offset-1">
				<button name="btn_alta" id="btn_alta" type="submit" class="btn btn-default">Guardar</button>
				<button type="button" name="btn_cancel" id="btn_cancel" onclick="CerrarFactura('.$_SESSION['id'].','.$_SESSION['convocatoria'].');" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
			</div>
		</div>';
			
	echo '</form>';
	echo '    </div>
			</div>
		  </div>
		</div>';
		
		include('resources/modal.php');
?>