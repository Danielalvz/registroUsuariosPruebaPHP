<?php
    require_once "./config/app.php";
    require_once "./autoload.php";
    require_once "./app/views/inc/session_start.php";


    if(isset($_GET['views'])) {
        $url = explode("/", $_GET['views']);
    } else {
        $url = ["login"];
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once "./app/views/inc/head.php"; ?>
</head>
<body>
    <?php 
        use app\controllers\viewsController;
        use app\controllers\loginController;

        $instanciaLogin = new loginController();

        $viewsController = new viewsController();

        $vista = $viewsController-> obtenerVistasControlador($url[0]);

        if($vista === "login" || $vista === "404") {
            require_once "./app/views/content/".$vista."-view.php";
        } else {
            // if(!isset($_SESSION['id']) || !isset($_SESSION['nombre'])
            //     || $_SESSION['id'] == "") {
            //     $instanciaLogin-> cerrarSesionControlador();
            //     exit();
            // }
            $pagina = $_GET['views'] ?? '';
            if ($pagina === '') {
                $url = ["dashboard"];
            } else {
                $url = explode("/", $pagina);
            }


            require_once "./app/views/inc/navbar.php";
            require_once $vista;
        }

        require_once "./app/views/inc/script.php"; 
    ?>  
</body>
</html>
