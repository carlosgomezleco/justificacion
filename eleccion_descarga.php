<?php
	if(isset($_SESSION['error_descarga']) and $_SESSION['error_descarga']!=''){
		echo $_SESSION['error_descarga'];
		$_SESSION['error_descarga']='';
	}
	echo'<p> Seleccione el tipo de descarga que desea realizar </p>';
	echo '<p><a href="descarga.php?id='.$_SESSION['id'].'&c='.$_GET['c'].'">Todo el proyecto </a></p>';
	echo '<p><a href="descarga_parcial.php?id='.$_SESSION['id'].'&c='.$_GET['c'].'">Documentos no justificados </a></p>';
?>