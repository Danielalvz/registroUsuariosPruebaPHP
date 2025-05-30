<?php
    namespace app\models;

    class viewsModel {
        protected function obtenerVistasModelo($vista) {

            //Define valores permitidos en la url
            $listaBlanca = ["dashboard", "userNew", "userProfile", "userUpdate", "logOut", "userPhoto"];

            if(in_array($vista, $listaBlanca)) {
                if(is_file("./app/views/content/".$vista."-view.php")) {
                    $contenido = "./app/views/content/".$vista."-view.php";
                } else {
                    $contenido = "404";
                }
            } elseif($vista === "login" || $vista === "index") {
                $contenido = "login";
            } else {
                $contenido = "404";
            }

            return $contenido;
        }
    }