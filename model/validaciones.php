<?php 
class Validaciones extends Exception
{
	PUBLIC function validarCedula($string,$type=true){//rif y cedula (true para tipo de cedula obligatorio)

		$cedula_1 = "/(?:(?:^[ve][-\s]?[0-9]{7,8}$)|(?:^[jg][-\s]?[0-9]{8,10}$))/i";
		$cedula_2 = "/(?:(?:^[0-9]{7,8}$)|(?:^[ve][-\s]?[0-9]{7,8}$)|(?:^[jg][-\s]?[0-9]{8,10}$))/i";
		$pattern = ($type) ? $cedula_1 : $cedula_2;
		//$pattern = '/(?:(?:^[0-9]{7,8}$)|(?:^[ve][-\s]?[0-9]{7,8}$)|(?:^[jg][-\s]?[0-9]{8,10}$))/i';

		if(!preg_match($pattern, $string)){
			throw new Validaciones("La cedula no es valida", 1);
		}
	}

	// n es igual al maximo de caracteres, si se coloca cero (0) no tendría limite
	PUBLIC function validarNombre($string,$n="1,50",$mensaje="El Nombre no es valido"){

		$pattern=$n?"/^[a-zA-Z\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{".$n."}$/u":"/^[a-zA-Z\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]+$/u";
		
		if(!preg_match($pattern, $string)){
			throw new Validaciones($mensaje, 1);
		}
	}
	PUBLIC function alfanumerico ($string,$n="1,50",$mensaje="caracteres no permitidos"){
		$pattern=$n?"/^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{".$n."}$/u":"/^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]+$/u";
		if(!preg_match($pattern, $string)){
			throw new Validaciones($mensaje, 1);
		}

	}
	PUBLIC function validarTelefono($string,$mensaje = "El teléfono no es valido"){// valida telefono con formato "0414-5555555" o "04145555555" o "0414 5555555"
		$pattern='/^[0-9]{4}[-\s]?[0-9]{7}$/';
		if(!preg_match($pattern, $string)){
			throw new Validaciones($mensaje, 1);
		}
	}
	PUBLIC function validarEmail($string,$mensaje = "El correo no es valido"){
		if(!filter_var( $string , FILTER_VALIDATE_EMAIL)){
			throw new Validaciones($mensaje, 1);
		}


	}
	PUBLIC function validarContrasena($string, $mensaje = "La contraseña no es valida")
	{
		$pattern='/^(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{6,20}$/';
		if(!preg_match($pattern, $string)){
			throw new Validaciones($mensaje, 1);
		}
		//Debe tener entre 6 y 20 caracteres y al menos **un numero, una letra minúscula y una mayúscula**
	}

	PUBLIC function fecha($string, $mensaje = "fecha"){
		if(is_string($string)){
			if(!preg_match("/^[\d][\d][\d][\d][\D][\d][\d][\D][\d][\d]$/", $string)){
				// si no es un string con el formato
				throw new Validaciones("La $mensaje no tiene el formato adecuado (yyyy-mm-dd) :: $string", 1);
			}
			$string = preg_split("/\D/", $string);

		}else{throw new Validaciones("la $mensaje no es una cadena de caracteres", 1);}

		$a = $string[0];// año
		$m = $string[1];// mes
		$d = $string[2];// dia
		if($a < 1900){throw new Validaciones("El año de la $mensaje no puede ser menor a 1900", 1);}
		if(($m < 1) || $m > 12){throw new Validaciones("El mes de la $mensaje no puede ser menor a 1 o mayor a 12", 1);}
		if(($d < 1) || $d > 31){throw new Validaciones("El día de la $mensaje no puede ser menor a 1 o mayor a 31", 1);}
		if(($a%4 != 0) and ($m == 2) and ($d > 28)){throw new Validaciones("($mensaje) Solo el año bisiesto tiene mas de 28 días en febrero", 1);}
		if((($m == 4) || ($m == 6 )|| ($m == 9 )|| ($m == 11) ) and $d>30){throw new Validaciones("En la $mensaje el mes seleccionado no puede tener mas de 30 días", 1);}
		if($m==2 and $d>29){throw new Validaciones("En la $mensaje el mes de febrero no puede tener mas de 29 días", 1);}
	}

	PUBLIC function hora($string,$mensaje = "La hora no es valida"){
		if(!preg_match("/^(?:[01]?[0-9]|2[0-3]):[0-5][0-9]$/", $string)){
			throw new Validaciones($mensaje, 1);
		}
	}

	PUBLIC function monto_Miles($string, $mensaje = "El monto es invalido"){
		if(!preg_match("/^[0-9]{1,3}(?:[\.][0-9]{3}){0,6}[,][0-9]{2}$/", $string)){
			throw new Validaciones($mensaje, 1);
			
		}
	}
	PUBLIC function monto($string, $mensaje = "El monto es invalido" ){
		if(!preg_match("/^[0-9]{1,18}(?:[\,\.][0-9]{2})?$/", $string)){
			throw new Validaciones($mensaje, 1);
			
		}
	}
	PUBLIC function numero($string,$n = "1,50",$mensaje = "El numero es invalido"){
		if(!preg_match("/^[0-9]{".$n."}$/", $string)){
			throw new Validaciones($mensaje, 1);
		}
	}




	PUBLIC function validar($string,$pattern,$mensaje){// para otros personalizado
		if(!preg_match($pattern, $string)){
			throw new Validaciones($mensaje, 1);
		}
	}



	PUBLIC function removeWhiteSpace($string){
		// elimina espacios al principio y al final de una cadena
		// también elimina espacios seguidos de otro espacio
		// ej: "   hola      como estas   " pasa a "hola como estas"

		if(preg_match("/(?:^\s)|(?:[\s][\s])|(?:[\s]+$)/", $string)){
			$string = preg_replace("/\n/m", "---WHITE_ENDL_SPACE---", $string);
			$string = preg_replace("/(?:^\s+)|(?:\s+$)/m", "", $string);

			while (preg_match("/[\s][\s]/", $string)) {
				$string = preg_replace("/[\s][\s]/", " ", $string);
			}
			$string = preg_replace("/---WHITE_ENDL_SPACE---/", "\n", $string);
		}
		return $string;

	}

	PUBLIC function validarPagos($obj){
		//tipo_pago
		$tipo_pago_Array = [
			"1", //efectivo
			"2", //transferencia
			"3", //pago_mobil
			"4" //divisa
		];
		if(!in_array($obj->tipo_pago, $tipo_pago_Array)){
			throw new Validaciones("El tipo de pago seleccionado no esta programado", 1);
		}
		//fecha
		$this->fecha($obj->fecha);
		//hora
		$this->hora($obj->hora);
		//monto
		$this->monto_Miles($obj->monto);

		if($obj->tipo_pago == 2){
			$this->validarCedula($obj->cedula);
			//referencia
			$this->validar($obj->referencia,"/^[0-9]{1,50}$/","Ingrese un numero de referencia valido"); 
			//banco
			$this->alfanumerico($obj->banco,"1,60", "Los datos introducidos en \"Banco\" no son validos");
		}
		else if($obj->tipo_pago == 3)
		{
			$this->validarCedula($obj->cedula);
			//referencia
			$this->validar($obj->referencia,"/^[0-9]{1,50}$/","Ingrese un numero de referencia valido"); 
			//banco
			$this->alfanumerico($obj->banco,"1,60", "Los datos introducidos en \"Banco\" no son validos");

			$this->validarTelefono($obj->telefono);
		}
		else if ($obj->tipo_pago == 4){ //divisa
			if(is_numeric($obj->cantidad) and $obj->cantidad > 0){
				if(isset($obj->billetes)){
					for($i = 0; $i < count($obj->billetes); $i++){
						$this->validar($obj->billetes[$i]->denominacion,"/^[0-9]{1,18}(?:[.,][0-9]{2})?$/","Ingrese una denominación de billetes valida ej 5 o 5.25 en la Divisa Nº ".($i+1));
						$this->validar($obj->billetes[$i]->serial,"/^[0-9]{1,20}$/","Ingrese una denominación de billetes valida ej 5 o 5.25 en la Divisa Nº ".($i+1));
					}
				}
				else throw new Validaciones("No se pasaron los billetes", 1);
				
			}
			else{
				throw new Validaciones("Debe ingresar una cantidad de billetes para divisa", 1);
			}
		}



		//throw new Validaciones("llego hasta aqui", 1);
		




		// ob_start();
		// echo "<pre>\n";
		// var_dump($obj);
		// echo "</pre>";
		// $valor = ob_get_clean();

		//throw new Validaciones($valor, 1);
		
		return true;
	}



}
?>