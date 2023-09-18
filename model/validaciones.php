<?php 
class Validaciones
{
	PUBLIC function validarCedula($string,$type=false){//rif y cedula
		if($type !==false && preg_match("/^j|e|v$/i", $type)){
			$string = $type."-".$string;
		}

		$pattern = '/(?:(?:^[0-9]{7,8}$)|(?:^[ve][-\s]?[0-9]{7,8}$)|(?:^[jg][-\s]?[0-9]{8,10}$))/i';

		if(!preg_match($pattern, $string)){
			throw new Exception("La cedula no es valida", 1);
		}
	}

	// n es igual al maximo de caracteres, si se coloca cero (0) no tendría limite
	PUBLIC function validarNombre($string,$n=50,$mensaje="El Nombre no es valido"){

		$pattern=$n?"/^[a-zA-Z\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{".$n."}$/":"/^[a-zA-Z\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]+$/";
		
		if(!preg_match($pattern, $string)){
			throw new Exception($mensaje, 1);
		}
	}
	PUBLIC function validarTelefono($string){// valida telefono con formato "0414-5555555" o "04145555555" o "0414 5555555"
		$pattern='/^[0-9]{4}[-\s]?[0-9]{7}$/';
		if(!preg_match($pattern, $string)){
			throw new Exception("El teléfono no es valido", 1);
		}
	}
	PUBLIC function validarEmail($string){
		if(!filter_var( $string , FILTER_VALIDATE_EMAIL)){
			throw new Exception("El correo no es valido", 1);
		}


	}
	PUBLIC function validarContrasena($string)
	{
		$pattern='/^(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{6,20}$/';

		if(!preg_match($pattern, $string)){
			throw new Exception("La contraseña no es valida", 1);
		}

		//Debe tener entre 6 y 20 caracteres y al menos **un numero, una letra minúscula y una mayúscula**
	}


	PUBLIC function validar($string,$pattern,$mensaje){// para otros personalizado
		if(!preg_match($pattern, $string)){
			throw new Exception($mensaje, 1);
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



}
?>