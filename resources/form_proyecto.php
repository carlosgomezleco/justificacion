<?php

	echo '<form class="form-horizontal" onsubmit="mensaje()" role="form" method="POST" action="nuevo_proyecto.php" ENCTYPE="multipart/form-data">';
	include('resources/select_convocatoria.php');
	if($existe == 1){
		echo'	<!-- Expediente -->
				<div class="form-group">
					<label for="expediente" class="col-lg-2 control-label">Expediente</label>
					<div class="col-lg-10">
						<input type="text" class="form-control" name="expediente" id="expediente"
						 placeholder="Expediente" autofocus 
						 value="'; 
						 if(isset($_POST['expediente'])) echo $_POST['expediente'];
						 echo '">
					</div>
				</div>
				<!-- Referencia -->
				<div class="form-group">
					<label for="referencia" class="col-lg-2 control-label">Referencia</label>
					<div class="col-lg-10">
						<input type="text" name="referencia" class="form-control" id="referencia"
						 placeholder="Referencia" required 
						 value="'; 
						 if(isset($_POST['referencia'])) echo $_POST['referencia'];
						 echo '">
					</div>
				</div>
				<div class="form-group">
					<label for="adjudicacion" class="col-lg-2 control-label">Adjudicacion</label>
						<div class="col-lg-10">
							<input type="file" name="adjudicacion" class="form-control filestyle" id="adjudicacion">
						</div>
				</div>
				<!-- Título -->
				<div class="form-group">
					<label for="titulo" class="col-lg-2 control-label">Título</label>
					<div class="col-lg-10">
						<textarea name="titulo" required placeholder="Escriba aquí el título..." id="titulo" class="form-control" rows="3">';
						if(isset($_POST['titulo'])) echo $_POST['titulo'];
						echo '</textarea>
					</div>
				</div>
				<!-- Botón guardar-->			
				<div class="form-group">
					<div class="col-lg-offset-1 col-lg-3">
						<button name="btn_alta" id="btn_alta" type="submit" class="btn btn-default">Guardar</button>
						<button type="button" name="btn_cancel" id="btn_cancel" onclick="history.back();" class="btn btn-default">Cancelar</button>
					</div>
				</div>';
		}		
		echo '</form>';
		
		include('modal.php');

?>