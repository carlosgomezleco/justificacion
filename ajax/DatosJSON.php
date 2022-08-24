<?php

	include('../lib/Db.php');
	include('../lib/Conf.class.php');
	header('Content-type: application/json');
	$bd = Db::getInstance();
	switch ($_GET['info']) {
        case 'DOCUMENTACION' :
			$id=$_GET['id'];
            $sql = "SELECT * FROM jp_documentacion WHERE id='".$id."'";
			$resultado = $bd->ejecutar($sql);
            $fila = $bd->obtener_fila($resultado);
            echo json_encode($fila);
            break;
			
		case 'MODIFICACION' :
			$id=$_GET['id'];
            $sql = "SELECT * FROM jp_modificacion_proyecto WHERE id='".$id."'";
			$resultado = $bd->ejecutar($sql);
            $fila = $bd->obtener_fila($resultado);
            echo json_encode($fila);
            break;
		
		case 'FACTURA' :
			$id=$_GET['id'];
            $sql = "SELECT * FROM jp_facturacion WHERE id='".$id."'";
			$resultado = $bd->ejecutar($sql);
            $fila = $bd->obtener_fila($resultado);
            echo json_encode($fila);
            break;
			
		case 'INVENTARIO' :
			$id=$_GET['id'];
            $sql = "SELECT * FROM jp_inventario WHERE id='".$id."'";
			$resultado = $bd->ejecutar($sql);
            $fila = $bd->obtener_fila($resultado);
            echo json_encode($fila);
            break;
		
		case 'AUDITORIA' :
			$id=$_GET['id'];
            $sql = "SELECT * FROM jp_auditoria WHERE id='".$id."'";
			$resultado = $bd->ejecutar($sql);
            $fila = $bd->obtener_fila($resultado);
            echo json_encode($fila);
            break;
		
		case 'JUSTIFICACION' :
			$id=$_GET['id'];
            $sql = "SELECT * FROM jp_justificacion_documental WHERE id='".$id."'";
			$resultado = $bd->ejecutar($sql);
            $fila = $bd->obtener_fila($resultado);
            echo json_encode($fila);
            break;
			
		case 'REINTEGRO' :
			$id=$_GET['id'];
            $sql = "SELECT * FROM jp_reintegro WHERE id='".$id."'";
			$resultado = $bd->ejecutar($sql);
            $fila = $bd->obtener_fila($resultado);
            echo json_encode($fila);
            break;
			
		/////////PARTE DE ADMINISTRACION
		case 'LOGO' :
			$id=$_GET['id'];
            $sql = "SELECT * FROM jp_logo WHERE id='".$id."'";
			$resultado = $bd->ejecutar($sql);
            $fila = $bd->obtener_fila($resultado);
            echo json_encode($fila);
            break;
			
		case 'CHECKLIST' :
			$id=$_GET['id'];
            $sql = "SELECT * FROM jp_tipo_documento WHERE id='".$id."'";
			$resultado = $bd->ejecutar($sql);
            $fila = $bd->obtener_fila($resultado);
            echo json_encode($fila);
            break;
			
		case 'USUARIO' :
			$id=$_GET['id'];
            $sql = "SELECT * FROM jp_usuarios WHERE id='".$id."'";
			$resultado = $bd->ejecutar($sql);
            $fila = $bd->obtener_fila($resultado);
            echo json_encode($fila);
            break;
	}

?>