<?php
    function invertir_fechas($fecha){
    	if (empty($fecha)) {
			return "";
		} else {		
    		$fecha=str_replace("/", "-", $fecha);
    		$arrFechas=explode("-", $fecha);
			return $arrFechas[2]."-".$arrFechas[1]."-".$arrFechas[0];
		}
    }
	
	function validarFecha($fecha){
		
		//echo $fecha;
		if(strlen($fecha) != 10){
			//echo $fecha.' su longitud es '.strlen($fecha);
			return '0000-00-00';
		}
		$fecha=str_replace("-", "/", $fecha);
		if( preg_match('/\d{2}\/\d{2}\/\d{4}/', $fecha)){
			$fecha=invertir_fechas($fecha);
			//echo 'entra en invertir fecha y el valor de fecha es:'.$fecha;
		}else{
			//echo 'No entra en invertir_fechas'.$fecha;
			
		}
		return $fecha;
	}

?>