<?php
require_once "../../config/app.php";
require_once "../views/inc/session_start.php";
require_once "../../autoload.php";

use app\controllers\loginController;

if (isset($_POST['token']) && isset($_POST['password']) && isset($_POST['password_confirm'])) {
    $login = new loginController();
    echo $login->actualizarClaveControlador();
}
