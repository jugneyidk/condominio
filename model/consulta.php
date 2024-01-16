<?php  

require_once('model/datos.php');
class consulta extends datos
{
    PUBLIC function iniciarSesion($usuario,$clave)
    {
        if (!empty($usuario) && !empty($clave)) {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);            
            try {

                $tipo_identificacion = preg_replace("/[\s-]?[0-9]*$/", "", $usuario);
                $usuario = preg_replace("/^.[\s-]?/", "", $usuario);


                //$tipo_identificacion = 0;// V 
                switch ($tipo_identificacion) {
                    case "V":
                        $tipo_identificacion = 0;
                        break;
                    case "E":
                        $tipo_identificacion = 1;
                    break;
                    case "J":
                        $tipo_identificacion = 2;
                    break;
                    case "G":
                        $tipo_identificacion = 3;
                    break;
                }



                $consulta = $co->prepare("SELECT id,cedula_rif, tipo_identificacion, correo, clave FROM habitantes WHERE cedula_rif = ? AND tipo_identificacion = ?");
                $consulta->execute([$usuario,$tipo_identificacion]);
                $temp =$resultado = $consulta->fetch();
                if ($resultado && password_verify($clave, $resultado['clave'])) {
                //if ($resultado) {
                    //session_start();
			        $_SESSION['id_habitante'] = $resultado['id'];
                    $_SESSION['CONDOMINIO_TOKEN'] = password_hash($resultado["cedula_rif"]."-".$resultado["tipo_identificacion"], PASSWORD_DEFAULT);
                    $r["resultado"]="correcto";
                    return $r;
                }else{
                    $r['resultado'] = "incorrecto";
                    $r['mensaje'] = "Los datos ingresados son incorrectos";
                    return $r;
                }
            } catch (Exception $e) {
                $r["resultado"] = "incorrecto";
                $r["mensaje"] = $e->getMessage()."::".$e->getLine();
                return $r;
            }
        }
    }
}
?>