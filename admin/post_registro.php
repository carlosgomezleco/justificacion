<?php
	include('../lib/funciones.php');

	/*
	$archivo = fopen("../docs/registro.txt", "w+");
	fputs($archivo, "");
	fclose($archivo);*/
	
	borrarRegistro();
	header('Location:index.php');
?>