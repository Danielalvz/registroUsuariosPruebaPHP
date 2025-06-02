<?php
require_once "../../config/app.php";
require_once "../views/inc/session_start.php";
require_once "../../autoload.php";

use app\controllers\loginController;

$login = new loginController();

if (isset($_POST['usuario_email'])) {
    echo $login->recuperarClaveControlador();
}
