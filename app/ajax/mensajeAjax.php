<?php
require_once "../../config/app.php";
require_once "../views/inc/session_start.php";
require_once "../../autoload.php";

use app\controllers\messageController;

$mensaje = new messageController();

if(isset($_POST['accion_mensaje'])) {
    if($_POST['accion_mensaje'] == "enviar") {
        echo json_encode($mensaje->enviarMensajeControlador());
    }
} elseif(isset($_GET['accion']) && $_GET['accion'] == "obtener") {
    echo $mensaje->obtenerMensajesControlador();
} elseif(isset($_GET['accion']) && $_GET['accion'] == "usuarios") {
    echo $mensaje->obtenerUsuariosControlador();
}

