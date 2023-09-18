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
                $consulta = $co->query("Select id,cedula_rif,correo from habitantes where cedula_rif = '$clave' and correo = '$usuario'");
                $resultado = $consulta->fetch();
                if ($resultado) {
                    //session_start();
			        $_SESSION['id_habitante'] = $resultado['id'];
                    $r["resultado"]="correcto";
                    return $r;
                }else{
                    $r['resultado'] = "incorrecto";
                    $r['mensaje'] = "Los datos ingresados son incorrectos";
                    return $r;
                }
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
    }
}
?>