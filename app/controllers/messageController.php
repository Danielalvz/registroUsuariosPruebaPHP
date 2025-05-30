<?php
namespace app\controllers;
use app\models\messagesModel;

class messageController extends messagesModel {

    public function enviarMensajeControlador() {
        $emisor_id = $_SESSION['id'];
        $receptor_id = $this->limpiarCadena($_POST['receptor_id']);
        $mensaje = $this->limpiarCadena($_POST['mensaje_texto']);

        $datos = [
            [
                "campo_nombre" => "emisor_id",
                "campo_marcador" => ":Emisor",
                "campo_valor" => $emisor_id
            ],
            [
                "campo_nombre" => "receptor_id",
                "campo_marcador" => ":Receptor",
                "campo_valor" => $receptor_id
            ],
            [
                "campo_nombre" => "mensaje_texto",
                "campo_marcador" => ":Mensaje",
                "campo_valor" => $mensaje
            ]
        ];

        if($this->guardarMensajeModelo($datos)) {
            return [
                "tipo" => "limpiar",
                "titulo" => "Mensaje enviado",
                "texto" => "El mensaje fue enviado exitosamente",
                "icono" => "success"
            ];
        } else {
            return [
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "No se pudo enviar el mensaje",
                "icono" => "error"
            ];
        }
    }

    public function obtenerMensajesControlador() {
        $usuario_id = $_SESSION['id'];
        $mensajes = $this->obtenerMensajesUsuarioModelo($usuario_id);
        $resultados = $mensajes->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($resultados);
    }

    public function obtenerUsuariosControlador() {
    $usuarios = $this->obtenerUsuariosModelo($_SESSION['id']); // Excluye al usuario actual
    $datos = $usuarios->fetchAll(\PDO::FETCH_ASSOC);
    return json_encode($datos);
}

}

