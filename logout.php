<?php

	//Cerramos la sesión y volvemos al index de la intranet	
	//unset($_SESSION['user']);
	//unset($_SESSION['pass']);	
	//unset($_SESSION['tab']);
	//if(isset($_SESSION['info_proyecto'])) unset($_SESSION['info_proyecto']);
	session_start();
	session_destroy();	
	header('Location:index.php');
?>