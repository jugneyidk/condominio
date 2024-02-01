<?php

use PHPUnit\Framework\TestCase;
error_reporting(E_ALL);
require_once("./model/usuarios-administracion.php");
require_once("./model/bitacora.php");


class UsuariosTest extends TestCase{
    
    
    
    private $usuario;
    
    protected function setUp(): void {
        session_start(); // Inicia la sesión al principio de cada prueba
        $tipo = '1';
        $usuario_id = '2';
        $_SESSION['id_usuario'] = 2;
        $_SESSION['Conjunto_Residencial_José_Maria_Vargas_rol'] = 5;
        $_GET['p'] = 'usuarios-administracion';
    
        $this->usuario = new usuarios();
    }
   

    public function testIncluirUsuarioRepetido(){


        $this->usuario->set_rif_cedula('121212');
        $this->usuario->set_tipo_identificacion('0');
        $this->usuario->set_razon_social('Empresa XYZ');
        $this->usuario->set_domicilio_fiscal('Dirección XYZ');
        $this->usuario->set_telefono('1234567890');
        $this->usuario->set_correo('correo@ejemplo.com');
        $this->usuario->set_password('contrasena');
        $this->usuario->set_rol('2');
      
        $resultado = $this->usuario->incluir_S(); 

       // $this->assertEquals('incluir', $resultado['resultado']);
        $this->assertEquals('La cedula ingresada ya existe', $resultado['mensaje']);

    }
}
?>