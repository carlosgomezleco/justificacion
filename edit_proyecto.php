<?php
	 //$_SESSION['adju'] =""; 	
	//Usamos el campo oculto editar para indicar el formulario con el que estamos trabajando
	//El campo id indica el id del proyecto que estamos editando
	echo '<form class="form-horizontal" role="form" method="POST" action="editar.php?id='.$_SESSION['id'].'&c='.$_SESSION['convocatoria'].'" ENCTYPE="multipart/form-data">							
			<div class="form-group">
				<label for="convocatoria" class="col-lg-2 control-label">Convocatoria</label>
				<div class="col-lg-7">
					<input type="text" class="form-control" name="convocatoria" id="convocatoria" required 
					value="'.$_SESSION['info_proyecto'][3].'" disabled>
				</div>
			</div>
			<div class="form-group">
				<label for="Organismo" class="col-lg-2 control-label">Organismo</label>
				<div class="col-lg-7">
					<input type="text" class="form-control" name="organismo" id="organismo" required 
					value="'.$_SESSION['info_proyecto'][4].'" disabled>
				</div>
			</div>
			<!-- mandamos el id del proyecto en un campo oculto -->
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<input type="hidden" name="editar" value="editar">
			<!-- Convocatoria -->
			
			<!-- Expediente -->
			<div class="form-group">
				<label for="expediente" class="col-lg-2 control-label">Expediente</label>
				<div class="col-lg-7">
					<input type="text" class="form-control" name="expediente" id="expediente"
						placeholder="Expediente" value="'; echo $_SESSION['info_proyecto'][0];
					echo '">
				</div>
			</div>
			<!-- Referencia -->
			<div class="form-group">
				<label for="referencia" class="col-lg-2 control-label">Referencia</label>
				<div class="col-lg-7">
					<input type="text" name="referencia" class="form-control" id="referencia"
						placeholder="Referencia" required value="'; echo $_SESSION['info_proyecto'][1];
					echo '">
					</div>
				</div>
			<!-- Título -->
			<div class="form-group">
				<label for="titulo" class="col-lg-2 control-label">Título</label>
				<div class="col-lg-7">
					<textarea name="titulo" required id="titulo" class="form-control" rows="5">';
					echo $_SESSION['info_proyecto'][2]; 
					echo '</textarea>
				</div>
			</div>';
			if($_SESSION['adju'] !== ""){
				
				echo '<div class="checkbox">
						<label>
							<input type="checkbox" value="" id="chb_a">
								Solo si desea modificarla, active la casilla.
							<a target="_blank" href="docs/'.$_SESSION['convocatoria'].'/'.$_SESSION['id'].'/'.$_SESSION['adju'].'">Adjudicación </a>
						</label>
					</div>
					<input id="adju" type="hidden" value="' .$_SESSION['adju'].'">';
			}		
	
			echo '<!-- Adjudicacion -->
				<div class="form-group">
					<label for="adjudicacion" class="col-lg-2 control-label">Adjudicacion </label>
						<div class="col-lg-9">
							<input type="file" name="adjudicacion" class="form-control filestyle" id="adjudicacion" style="width: 360px;"';
							if($_SESSION['adju'] !== ""){ echo ' disabled ';}
							echo'>
						</div>
				</div>';
			if($_SESSION['segui'] !== ""){
				
				echo '<div class="checkbox">
						<label>
							<input type="checkbox" value="" id="chb_se">
								Solo si desea modificarla, active la casilla.
							<a target="_blank" href="docs/'.$_SESSION['convocatoria'].'/'.$_SESSION['id'].'/'.$_SESSION['segui'].'">Seguimiento </a>
						</label>
					</div>
					<input id="segui" type="hidden" value="' .$_SESSION['segui'].'">';
			}else{
				echo '<div class="checkbox">
						<label>
							<input type="checkbox" value="" id="chb_se">
								Solo si desea modificarla, active la casilla.
							<a href="docs/SeguimientoProyectos.docx">Seguimiento </a>
						</label>
					</div>';
			}
	
			echo '<!-- Seguimiento -->
				<div class="form-group">
					<label for="seguimiento" class="col-lg-2 control-label">Seguimiento </label>
						<div class="col-lg-9">
							<input type="file" name="seguimiento" class="form-control filestyle" id="seguimiento" style="width: 360px;" disabled>
						</div>
				</div>				
			<!-- Botones -->			
			<div class="form-group">
				<div class="col-lg-offset-1 col-lg-3">
					<button name="btn_alta" id="btn_alta" type="submit" class="btn btn-default" style="margin-right:0px;">Guardar</button>
					<button type="button" name="btn_cancel" id="btn_cancel" class="btn btn-default" onclick="history.back();">Cancelar</button>
				</div>
			</div>
		</form>';
?>							