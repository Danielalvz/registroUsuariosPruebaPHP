<?php
    require_once "../../config/app.php";
    require_once "../views/inc/session_start.php";
    require_once "../../autoload.php";

    use app\controllers\userController;

    if(isset($_POST['modulo_usuario'])) {
        $instanciaUsuario = new userController();

        if($_POST['modulo_usuario'] == "registrar") {
            echo $instanciaUsuario-> registrarUsuarioControlador();
        }

        if($_POST['modulo_usuario'] == "actualizar") {
            echo $instanciaUsuario-> actualizarUsuarioControlador();
        }

        if($_POST['modulo_usuario'] == "actualizarFoto") {
            echo $instanciaUsuario-> actualizarFotoUsuarioControlador();
        }
    } else {
        session_destroy();
        header("Location: ".APP_URL."login/");
    }