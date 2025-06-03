<?php
    namespace app\models;
    use \PDO;
    use \PDOException;

    if(file_exists(__DIR__."/../../config/server.php")) {
        require_once __DIR__."/../../config/server.php";
    }

    require_once __DIR__ . '/../../PHPMailer/PHPMailer.php';
    require_once __DIR__ . '/../../PHPMailer/SMTP.php';
    require_once __DIR__ . '/../../PHPMailer/Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class mainModel {
        private $server = DB_SERVER;
        private $db = DB_NAME;
        private $user = DB_USER;
        private $pass = DB_PASS;

        protected function conectar(){
            $conexion = new PDO("mysql:host=".$this->server.";dbname=".$this->db, 
            $this->user, $this->pass);

            $conexion-> exec("SET CHARACTER SET utf8");

            return $conexion;
        }

        protected function ejecutarConsulta($consulta){
            $sql = $this-> conectar()-> prepare($consulta);
            $sql-> execute();

            return $sql;
        }

        public function limpiarCadena($cadena) {
            $palabras = ["<script>","</script>","<script src","<script type=",
            "SELECT * FROM","SELECT "," SELECT ","DELETE FROM","INSERT INTO",
            "DROP TABLE","DROP DATABASE","TRUNCATE TABLE","SHOW TABLES",
            "SHOW DATABASES","<?php","?>","--","^","<",">","==","=",";","::"];

            $cadena=trim($cadena);
			$cadena=stripslashes($cadena);

			foreach($palabras as $palabra){
				$cadena=str_ireplace($palabra, "", $cadena);
			}

			$cadena=trim($cadena);
			$cadena=stripslashes($cadena);

			return $cadena;
        }

        protected function verificarDatos($filtro, $cadena){
			if(preg_match("/^".$filtro."$/", $cadena)){
				return false;
            }else{
                return true;
            }
		}

        protected function guardarDatos($tabla, $datos) {
            $query = "INSERT INTO $tabla (";

            $contador = 0;
            foreach($datos as $clave) {
                if($contador >= 1) { $query.= ","; }
                $query.= $clave["campo_nombre"];
                $contador++;
            }

            $query.= ") VALUES(";

            $contador = 0;
            foreach($datos as $clave) {
                if($contador >= 1) { $query.= ","; }
                $query.= $clave["campo_marcador"];
                $contador++;
            }
            
            $query.= ")";

            $sql = $this-> conectar()-> prepare($query);

            //Con bindParam de PDO, se vincula un parámetro con una variable en especifico
            //bind param sustituye de la consulta SQL un marcador (:name) con el valor real de una variable PHP
            foreach($datos as $clave) {
                $sql-> bindParam($clave["campo_marcador"], $clave["campo_valor"]);
            }

            $sql-> execute();

            return $sql;
        }

        public function seleccionarDatos($tipo, $tabla, $campo, $id) {
            //Para evitar inyecciones SQL
            $tipo = $this-> limpiarCadena($tipo);
            $tabla = $this-> limpiarCadena($tabla);
            $campo = $this-> limpiarCadena($campo);
            $id = $this-> limpiarCadena($id);

            if($tipo == "Unico") {
                $sql = $this-> conectar()-> prepare(
                    "SELECT * FROM $tabla 
                    WHERE $campo =:ID"
                );

                $sql-> bindParam(":ID", $id);
            } elseif($tipo == "Normal") {
                $sql = $this-> conectar()-> prepare(
                    "SELECT $campo FROM $tabla");
            }

            $sql-> execute();

            return $sql;
        }

        protected function actualizarDatos($tabla, $datos, $condicion) {
            $query = "UPDATE $tabla SET ";

            $contador = 0;
            foreach($datos as $clave) {
                if($contador >= 1) { $query.= ","; }
                $query.= $clave["campo_nombre"]."=".$clave["campo_marcador"];
                $contador++;
            }

            $query.= " WHERE ".$condicion["condicion_campo"]."=".$condicion["condicion_marcador"];
            
            $sql = $this-> conectar()-> prepare($query);

            foreach($datos as $clave) {
                $sql-> bindParam($clave["campo_marcador"], $clave["campo_valor"]);
            }

            $sql-> bindParam($condicion["condicion_marcador"], $condicion["condicion_valor"]);

            try {
                $sql->execute();
            } catch (PDOException $e) {
                echo "Error SQL: " . $e->getMessage();
            }

            return $sql;
        }

        protected function eliminarRegistro($tabla, $campo, $id) {
            $sql = $this-> conectar()-> prepare(
                "DELETE FROM $tabla 
                WHERE $campo=:id");
            
             $sql-> bindParam(":id", $id);
             $sql-> execute();

            return $sql;
        }

        protected function enviarCorreo($destino, $asunto, $mensajeHTML) {

        $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'ml@gmail.com'; 
                $mail->Password   = 'mmmmm'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('m@gmail.com', 'Mensajeria Cero K Prueba');
                $mail->addAddress($destino);
                $mail->isHTML(true);
                $mail->Subject = $asunto;
                $mail->Body    = $mensajeHTML;

                $mail->send();
                return true;
            } catch (Exception $e) {
                error_log("Error al enviar correo: {$mail->ErrorInfo}");
                return false;
            }
        }       
    }
    