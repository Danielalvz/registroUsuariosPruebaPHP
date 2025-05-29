<?php
    $alerta = $instanciaLogin-> cerrarSesionControlador();
    $alerta = json_decode($alerta, true); //se convierte JSON a array PHP

    //se redirecciona
    if ($alerta['tipo'] == "redireccionar") {
        header("Location: " . $alerta['url']);
        exit();
    }

    
?>

