<?php

 $_SESSION['convo'] = $_SESSION['res_def'] = $_SESSION['bas']=""; 

 /* Formulario de alta de una convocatoria */
if(isset($_SESSION['error_convo']) and ($_SESSION['error_convo']!="")){
		echo $_SESSION['error_convo'];
		
 }
 echo '<form class="form-horizontal" onsubmit="mensaje()" role="form" method="POST" action="convocatoria.php" ENCTYPE="multipart/form-data">
			<input type="hidden" class="form-control" size="40" name="id" id="id" value="'.$_SESSION['id'].'">
			<!-- Título abreviado de la Convocatoria -->
			<div class="form-group">
				<label for="nombre" class="col-lg-2 control-label">Título</label>
				<div class="col-lg-9">
					<input type="text" class="form-control" size="40" name="nombre" id="nombre"
					 title="Se necesita un nombre" placeholder="Indique un título abreviado (máx. 40 caracteres)" required autofocus
					 value="'; 
					 if(isset($fila['nombre'])) echo $fila['nombre'];
					 echo '">
				</div>
			</div>
			<!-- Organismo -->
			<div class="form-group">
				<label for="organismo" class="col-lg-2 control-label">Organismo</label>
					<div class="col-lg-offset-2 col-lg-9">
						<select id="organismo" name="organismo" class="form-control" required disabled>
						<option value="0"'; 
				//Organismo 0 => Junta de Andalucía 1=> Ministerio
						if(isset($fila['organismo']) and $fila['organismo'] == '0') echo ' selected';
						echo '>Junta de Andalucía</option>
						<option value="1"';if(isset($fila['organismo']) and $fila['organismo'] == '1') echo ' selected';
						echo '>Ministerio</option>
						</select>
					</div>
			</div>';
			//Si el usuario tiene los permisos 0 => 'Administrador' podrá modificar los ficheros y los logos,
			//en caso de que tenga los permisos 1=> 'PAS' solo podrá verlos sin editar nada.
			if($fila['bases'] !== ""){
				$_SESSION['bas'] = $fila['bases'];
				echo '<div class="checkbox">
						<label>
							<a href="docs/'.$_SESSION['id'].'/'.$fila['bases'].'">Bases asignadas </a>
						</label>';  
						if($_SESSION['permisos']==0){
							echo ' -  <a onclick="return eliminar_bases('.$_SESSION['id'].');"><img src="img/cross.png" width=20/></a><br/><br/>';
							echo'<input type="checkbox" value="" id="chb_b">
								Solo si desea modificarla, active la casilla.';	
						}
						echo'<br/>
					</div>
					
					<input id="bas" type="hidden" value="' .$_SESSION['bas'].'">';
			}		
			if($_SESSION['permisos']==0){
			echo '<!-- Bases -->
				<div class="form-group">
					<label for="bases" class="col-lg-2 control-label">Bases</label>
						<div class="col-lg-9">
							<input type="file" name="bases" class="form-control filestyle" id="bases">
						</div>
				</div>';
			}
			
			//¿Checkbox?
			if($fila['convocatoria'] !== ""){
				$_SESSION['convo'] = $fila['convocatoria'];
				echo '<div class="checkbox">
						<label>
						<a href="docs/'.$_SESSION['id'].'/'.$fila['convocatoria'].'">Convocatoria asignada</a> 
						</label>';
							if($_SESSION['permisos']==0){
								echo '  -  <a onclick="return eliminar_doc_convocatoria('.$_SESSION['id'].');"><img src="img/cross.png" width=20/></a><br/><br/>';
								echo '<input type="checkbox" value="" id="chb_c"> Solo si desea modificarla, active la casilla.';
							}
							echo'<br/>
					</div>
					
					<input id="convo" type="hidden" value="' .$_SESSION['convo'].'">';
			}
			if($_SESSION['permisos']==0){
			echo ' 	<!-- Convocatoria -->
					<div class="form-group">
						<label for="convocatoria" class="col-lg-2 control-label">Convocatoria</label>
							<div class="col-lg-9">
								<input type="file" name="convocatoria" class="form-control filestyle" id="convocatoria">
							</div>
						</div>';
			}
			if($fila['resolucion'] !== ""){
				$_SESSION['res_def'] = $fila['resolucion'];
				echo'<div class="checkbox">
				<label>
				<a href="docs/'.$_SESSION['id'].'/'.$fila['resolucion'].'">Resolución asignada</a> 
						</label>';
						if($_SESSION['permisos']==0){
							echo '  -  <a onclick="return eliminar_resolucion('.$_SESSION['id'].');"><img src="img/cross.png" width=20/></a><br/><br/>';
						echo'<input type="checkbox" value="" id="chb_r">
								Solo si desea modificarla, active la casilla.';
						}
						echo'<br/>
					</div>
					
					<input id="resol" type="hidden" value="' .$_SESSION['res_def'].'">';
			}		
			if($_SESSION['permisos']==0){
			echo '<!-- Resolución -->
				<div class="form-group">
					<label for="resolucion" class="col-lg-2 control-label">Resolución def.</label>
						<div class="col-lg-9">
							<input type="file" name="resolucion" class="form-control filestyle" id="resolucion">
						</div>
				</div>';
			}
			echo '<!-- Logotipos -->
			<div class="col-lg-10">
				<label for="Logos" class="col-lg-9 control-label">Logos </label>
			</div>';
			if($_SESSION['permisos']==0){
			echo'<div class="col-lg-2">
					<button name="btn_añadir" id="btn_añadir" type="button" onclick="window.location.href = \'add_logos.php\';" class="btn btn-default">Añadir logo</button>
			</div>';
			}
			echo '<div id="container-table">';
			include('resources/listado_logos.php');
			echo '</div>';// div id="container-table"
		if($_SESSION['permisos']==0){
		echo' <!-- Botón guardar-->			
			<div class="form-group">
				<div class="col-lg-offset-1 col-lg-3">
					<button name="btn_guardar" id="btn_guardar" type="submit" class="btn btn-default">Guardar</button>
					<button type="button" name="btn_cancel" id="btn_cancel" onclick="history.back();" class="btn btn-default">Cancelar</button>
				</div>
			</div>
		</form>';
		}
		
		echo '<div id="myModal" class="modal fade" role="dialog" data-keyboard="false">
                <div class="modal-dialog">
                <!-- Modal content-->
                    <div class="modal-content">                       
                        <div class="modal-body">
                            <img src="img/loading.gif" class="img-responsive center-block"/>
                        </div>                         
                    </div>
                </div>
            </div>';

?>