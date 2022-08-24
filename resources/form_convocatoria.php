<?php
 /* Formulario de alta de una convocatoria */
 echo '<form class="form-horizontal" onsubmit="return NuevaConvocatoria()" role="form" method="POST" action="nueva_convocatoria.php" ENCTYPE="multipart/form-data">
			<!-- Título abreviado de la Convocatoria -->
			<div class="form-group">
				<label for="nombre" class="col-lg-2 control-label">Título</label>
				<div class="col-lg-10">
					<input type="text" class="form-control" size="40" name="nombre" id="nombre"
					 title="Se necesita un nombre" placeholder="Indique un título abreviado (máx. 40 caracteres)" required autofocus
					 value="'; 
					 if(isset($_POST['nombre'])) echo $_POST['nombre'];
					 echo '">
				</div>
			</div>
			<!-- Organismo -->
			<div class="form-group">
				<label for="organismo" class="col-lg-2 control-label">Organismo</label>
					<div class="col-lg-offset-2 col-lg-10">
						<select id="organismo" name="organismo" class="form-control" required>
						<option value="0"'; 
						if(isset($_POST['organismo']) and  $_POST['organismo'] == '0') echo ' selected';
						echo '>Junta de Andalucía</option>
						<option value="1"';
						if(isset($_POST['organismo']) and $_POST['organismo'] == '1') echo ' selected';
						echo '>Ministerio</option>
						</select>
					</div>
			</div>

			<!-- Bases -->
				<div class="form-group">
					<label for="bases" class="col-lg-2 control-label">Bases</label>
						<div class="col-lg-10">
							<input type="file" name="bases" class="form-control filestyle" id="bases">
						</div>
				</div>			
			<!-- Convocatoria -->
			<div class="form-group">
				<label for="convocatoria" class="col-lg-2 control-label">Convocatoria</label>
					<div class="col-lg-10">
						<input type="file" name="convocatoria" class="form-control filestyle" id="convocatoria">
					</div>
			</div>
				
			<!-- Resolución -->
				<div class="form-group">
					<label for="resolucion" class="col-lg-2 control-label">Resolución def.</label>
						<div class="col-lg-10">
							<input type="file" name="resolucion" class="form-control filestyle" id="resolucion">
						</div>
				</div>

			<!-- Botón guardar-->			
			<div class="form-group">
				<div class="col-lg-offset-1 col-lg-3">
					<button name="btn_guardar" id="btn_guardar" type="submit" class="btn btn-default" >Guardar</button>
					<button type="button" name="btn_cancel" id="btn_cancel" onclick="history.back();" class="btn btn-default">Cancelar</button>
				</div>
			</div>
		</form>';
		
		include('modal.php');
?>