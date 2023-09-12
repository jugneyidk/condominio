<?php

require_once('model/datos.php');
class login extends datos
{
    PUBLIC function iniciarSesion($usuario,$clave)
    {
        if (!empty($usuario) && !empty($clave)) {            
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);            
            try {
                $consulta = $co->query("Select id,rif_cedula,id_rol,clave from datos_usuarios inner join usuarios_roles where usuarios_roles.id_usuario=datos_usuarios.id and datos_usuarios.rif_cedula='$usuario'");
                $resultado = $consulta->fetch();
                // if ($resultado && password_verify($clave,$resultado['clave'])) {
                if ($resultado && $clave == $resultado['clave']) {
                    session_start();
			        $_SESSION['rol'] = $resultado['id_rol'];
			        $_SESSION['id_usuario'] = $resultado['id'];
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
