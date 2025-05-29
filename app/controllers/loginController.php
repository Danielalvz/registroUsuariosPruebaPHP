<?php

namespace app\controllers;
use app\models\mainModel;

class loginController extends mainModel
{
    public function iniciarSesionControlador() {
        $usuario = $this->limpiarCadena($_POST['login_usuario']);
        $clave = $this->limpiarCadena($_POST['login_clave']);

        # Verificar campos obligatorios
        if ($usuario == "" || $clave == "") {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        # Verificar formato del usuario
        if ($this-> verificarDatos("[a-zA-Z0-9]{4,20}", $usuario)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El USUARIO no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        # Verificar formato de la clave
        if ($this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}", $clave)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "La CLAVE no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        # Verificar si el usuario existe
        $check_usuario = $this-> ejecutarConsulta(
            "SELECT * FROM usuario 
            WHERE usuario_usuario='$usuario'");

        if ($check_usuario-> rowCount() == 1) {
            $check_usuario = $check_usuario->fetch();

            if($check_usuario['usuario_usuario'] == $usuario && 
                password_verify($clave, $check_usuario['usuario_clave'])) {
                    
                # Iniciar sesión
                $_SESSION['id'] = $check_usuario['usuario_id'];
                $_SESSION['nombre'] = $check_usuario['usuario_nombre'];
                $_SESSION['usuario'] = $check_usuario['usuario_usuario'];
                $_SESSION['telefono'] = $check_usuario['usuario_telefono'];
                $_SESSION['edad'] = $check_usuario['usuario_edad'];
                $_SESSION['foto'] = $check_usuario['usuario_foto'];

                # Enviar alerta de redirección
                $alerta = [
                    "tipo" => "redireccionar",
                    "titulo" => "Inicio de sesión exitoso",
                    "texto" => "Bienvenido, " . $check_usuario['usuario_nombre'],
                    "icono" => "success",
                    "url" => APP_URL . "dashboard/"
                ];
                return json_encode($alerta);
            }
        } 

        # Usuario o clave incorrectos
        $alerta = [
            "tipo" => "simple",
            "titulo" => "Ocurrió un error inesperado",
            "texto" => "Usuario o clave incorrectos",
            "icono" => "error"
        ];
        return json_encode($alerta);
    }

    public function cerrarSesionControlador(){
        session_destroy();

        $alerta = [
            "tipo" => "redireccionar",
            "titulo" => "Sesión cerrada",
            "texto" => "Has cerrado sesión exitosamente",
            "icono" => "success",
            "url" => APP_URL . "login/"
        ];

        return json_encode($alerta);
    }

}