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

    // RECUPERAR CONTRASEÑA-------------------------------------------------------------
   public function recuperarClaveControlador() {
    $email = $this->limpiarCadena($_POST['usuario_email']);

    // Validar email
    if ($email == "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return json_encode([
            "tipo" => "simple",
            "titulo" => "Error",
            "texto" => "Debe ingresar un correo electrónico válido",
            "icono" => "error"
        ]);
    }

    // Verificar si el correo existe
    $check_email = $this->ejecutarConsulta("SELECT * FROM usuario WHERE usuario_email='$email'");
    if ($check_email->rowCount() == 0) {
        return json_encode([
            "tipo" => "simple",
            "titulo" => "Error",
            "texto" => "El correo no está registrado",
            "icono" => "error"
        ]);
    }

    $datos_usuario = $check_email->fetch();
    $usuario_id = $datos_usuario['usuario_id'];
    $nombre = $datos_usuario['usuario_nombre'];

    // Generar token y fecha de expiración
    $token = bin2hex(random_bytes(32));
    $fecha_expira = date("Y-m-d H:i:s", strtotime("+15 minutes"));

    // Guardar token y expiración
    $this->actualizarDatos("usuario", [
        [
            "campo_nombre" => "token_recuperacion",
            "campo_marcador" => ":token",
            "campo_valor" => $token
        ],
        [
            "campo_nombre" => "token_expira",
            "campo_marcador" => ":expira",
            "campo_valor" => $fecha_expira
        ]
    ], [
        "condicion_campo" => "usuario_id",
        "condicion_marcador" => ":id",
        "condicion_valor" => $usuario_id
    ]);

    // Crear enlace
    $link = APP_URL . "loginPassUpdate/?token=$token";

    // Enviar correo
    $asunto = "Recuperacion password de mensajeria privada Cero K";
    $mensaje = "
        <h3>Hola $nombre,</h3>
        <p>Haz clic en el siguiente enlace para restablecer tu password:</p>
        <p><a href='$link'>$link</a></p>
        <p><small>Este enlace expirará en 15 minutos.</small></p>
    ";

    if ($this->enviarCorreo($email, $asunto, $mensaje)) {
        return json_encode([
            "tipo" => "simple",
            "titulo" => "Correo enviado",
            "texto" => "Se ha enviado un enlace a tu correo para restablecer la contraseña",
            "icono" => "success"
        ]);
    } else {
        return json_encode([
            "tipo" => "simple",
            "titulo" => "Error",
            "texto" => "No se pudo enviar el correo. Intenta más tarde",
            "icono" => "error"
        ]);
    }
}

public function actualizarClaveControlador() {
    $token = $this->limpiarCadena($_POST['token']);
    $clave = $this->limpiarCadena($_POST['password']);
    $clave_confirm = $this->limpiarCadena($_POST['password_confirm']);

    // Verificar campos
    if ($clave == "" || $clave_confirm == "") {
        return json_encode([
            "tipo" => "simple",
            "titulo" => "Campos vacíos",
            "texto" => "Debe ingresar y confirmar su nueva contraseña",
            "icono" => "error"
        ]);
    }

    // Validar formato
    if ($this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}", $clave)) {
        return json_encode([
            "tipo" => "simple",
            "titulo" => "Formato inválido",
            "texto" => "La contraseña no cumple con el formato permitido",
            "icono" => "error"
        ]);
    }

    if ($clave !== $clave_confirm) {
        return json_encode([
            "tipo" => "simple",
            "titulo" => "Error",
            "texto" => "Las contraseñas no coinciden",
            "icono" => "error"
        ]);
    }

    // Verificar token
    $consulta = $this->ejecutarConsulta("SELECT * FROM usuario WHERE token_recuperacion='$token'");
    if ($consulta->rowCount() != 1) {
        return json_encode([
            "tipo" => "simple",
            "titulo" => "Token inválido",
            "texto" => "El token no es válido o ya fue usado",
            "icono" => "error"
        ]);
    }

    $usuario = $consulta->fetch();
    $expira = strtotime($usuario['token_expira']);

    if (time() > $expira) {
        return json_encode([
            "tipo" => "simple",
            "titulo" => "Token expirado",
            "texto" => "El enlace ha expirado. Solicita uno nuevo.",
            "icono" => "error"
        ]);
    }

    $clave_hash = password_hash($clave, PASSWORD_BCRYPT);

    // Actualizar contraseña y eliminar token
    $this->actualizarDatos("usuario", [
        [
            "campo_nombre" => "usuario_clave",
            "campo_marcador" => ":clave",
            "campo_valor" => $clave_hash
        ],
        [
            "campo_nombre" => "token_recuperacion",
            "campo_marcador" => ":token",
            "campo_valor" => null
        ],
        [
            "campo_nombre" => "token_expira",
            "campo_marcador" => ":expira",
            "campo_valor" => null
        ]
    ], [
        "condicion_campo" => "usuario_id",
        "condicion_marcador" => ":id",
        "condicion_valor" => $usuario['usuario_id']
    ]);

    return json_encode([
        "tipo" => "redireccionar",
        "titulo" => "Contraseña actualizada",
        "texto" => "Ahora puedes iniciar sesión con tu nueva contraseña",
        "icono" => "success",
        "url" => APP_URL . "login/"
    ]);
}



}