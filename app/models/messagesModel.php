<?php
namespace app\models;
use app\models\mainModel;

class messagesModel extends mainModel {

    protected function guardarMensajeModelo($datos) {
        return $this->guardarDatos("mensajes", $datos);
    }

    protected function obtenerMensajesUsuarioModelo($usuario_id) {
        $sql = $this->conectar()->prepare("
        SELECT 
            m.*,
            u.usuario_nombre AS emisor_nombre,
            u.usuario_foto AS emisor_foto
        FROM mensajes m
        JOIN usuario u ON m.emisor_id = u.usuario_id
        WHERE m.receptor_id = :id
        ORDER BY m.mensaje_fecha DESC");
        $sql->bindParam(":id", $usuario_id);
        $sql->execute();
        return $sql;
    }

    protected function marcarLeidoModelo($id_mensaje) {
        $sql = $this->conectar()->prepare("UPDATE mensajes SET mensaje_leido = 1 WHERE mensaje_id = :id");
        $sql->bindParam(":id", $id_mensaje);
        $sql->execute();
        return $sql;
    }

    protected function obtenerUsuariosModelo($excluir_id) {
    $sql = $this->conectar()->prepare("SELECT usuario_id, usuario_nombre FROM usuario WHERE usuario_id != :id");
    $sql->bindParam(":id", $excluir_id);
    $sql->execute();
    return $sql;
    }

}
