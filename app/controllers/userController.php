<?php

namespace app\controllers;
use app\models\mainModel;

class userController extends mainModel {
    #Controlador para regisitrar usuario#
    public function registrarUsuarioControlador(){
        #Almacena datos#
        $nombre = $this-> limpiarCadena($_POST['usuario_nombre']);
        $usuario = $this-> limpiarCadena($_POST['usuario_usuario']);
        $email = $this-> limpiarCadena($_POST['usuario_email']);
        $clave = $this-> limpiarCadena($_POST['usuario_clave']);
        $claveConfirmacion = $this-> limpiarCadena($_POST['usuario_clave_confirmacion']);
        $telefono = $this-> limpiarCadena($_POST['usuario_telefono']);
        $edad = $this-> limpiarCadena($_POST['usuario_edad']);
        // $foto = $this->limpiarCadena($_POST['usuario_foto']);

        #Verificando campos obligatorios#
        if ($nombre == "" || $usuario == "" || $email == "" || $clave == "" || $claveConfirmacion == "") {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        #Verificando integridad de los datos#

        //Verificar formato nombre
        if ($this-> verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $nombre)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El NOMBRE no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        //Verificar formato usuario
        if ($this-> verificarDatos("[a-zA-Z0-9]{4,20}", $usuario)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El USUARIO no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        //Verificar disponibildiad usuario
        $check_usuario = $this-> ejecutarConsulta(
            "SELECT usuario_usuario FROM usuario 
            WHERE usuario_usuario= '$usuario'");

        if ($check_usuario-> rowCount() > 0) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El nombre de USUARIO ya está registrado",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        //Verificar formato email y disponibilidad
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $check_email = $this-> ejecutarConsulta(
                "SELECT usuario_email FROM usuario 
                WHERE usuario_email= '$email'");

            if ($check_email-> rowCount() > 0) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "El EMAIL que acaba de ingresar ya se encuentra registrado en el sistema, por favor verifique e intente nuevamente",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "Ha ingresado un correo electrónico no valido",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        //Verificar formato clave
        if($this-> verificarDatos("[a-zA-Z0-9$@.-]{7,100}",$clave) || 
            $this-> verificarDatos("[a-zA-Z0-9$@.-]{7,100}",$claveConfirmacion)){
		    	$alerta= [
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"Las CLAVES no coinciden con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }

        //Verificar coincidencia claves
        if ($clave !== $claveConfirmacion) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "Las CLAVES no coinciden",
                "icono" => "error"
            ];
            return json_encode($alerta);
        } else {
            $claveHash= password_hash($clave,PASSWORD_BCRYPT,["cost"=> 10]);
        }

        
        //Verificar formato telefono
        if ($telefono != "") {
            if ($this-> verificarDatos("[+]?[\d ]{7,20}", $telefono)) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "El TELÉFONO no tiene un formato válido",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
        }

        //Verificar formato de edad
        if ($edad != "") {
            if (!ctype_digit($edad) || (int) $edad < 0 || (int) $edad > 120) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "La EDAD debe ser un número válido entre 0 y 120",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
        }

        #Directorio de imagenes#
    	$img_dir="../views/fotos/";

        #Comprobar si se selecciono una imagen#
    	if($_FILES['usuario_foto']['name'] != "" && 
            $_FILES['usuario_foto']['size'] > 0) {

    		#Creando directorio#
		    if(!file_exists($img_dir)){
                //0777 -> permisos de lectura y escritura en el proyecto
		        if(!mkdir($img_dir,0777)){
		            $alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"Error al crear el directorio",
						"icono"=>"error"
					];
					return json_encode($alerta);
		        } 
		    }

            #Verificando formato de imagenes#
		    if(mime_content_type($_FILES['usuario_foto']['tmp_name']) != "image/jpeg" && 
                mime_content_type($_FILES['usuario_foto']['tmp_name']) != "image/png"){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"La imagen que ha seleccionado es de un formato no permitido",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
            }

            #Verificando peso de imagen#
            //de bytes a kbytes -> se divide en 1024
            //maximo 5MB -> 5 * 1024 = 5120 KB
            if(($_FILES['usuario_foto']['size'] / 1024) > 5120){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"La imagen que ha seleccionado supera el peso permitido",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
            }

            #Nombre de la foto#
            //para que los nombres de foto no se repitan
            $foto= str_ireplace(" ","_",$nombre);
            $foto= $foto."_".rand(0,100);

            #Extension de la imagen#
            switch(mime_content_type($_FILES['usuario_foto']['tmp_name'])){
                case 'image/jpeg':
                    $foto= $foto.".jpg";
                break;
                case 'image/png':
                    $foto= $foto.".png";
                break;
            }

            chmod($img_dir,0777);

            #Moviendo imagen al directorio#
            if(!move_uploaded_file($_FILES['usuario_foto']['tmp_name'],$img_dir.$foto)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No podemos subir la imagen al sistema en este momento",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
            }
        } else {
            $foto= "";
        }

        #Datos registro usuario#
        $usuario_datos_reg=[
            [
                "campo_nombre"=>"usuario_nombre",
                "campo_marcador"=>":Nombre",
                "campo_valor"=>$nombre
            ],
            [
                "campo_nombre"=>"usuario_usuario",
                "campo_marcador"=>":Usuario",
                "campo_valor"=>$usuario
            ],
            [
                "campo_nombre"=>"usuario_email",
                "campo_marcador"=>":Email",
                "campo_valor"=>$email
            ],
            [
                "campo_nombre"=>"usuario_clave",
                "campo_marcador"=>":Clave",
                "campo_valor"=>$claveHash
            ],
            [
                "campo_nombre"=>"usuario_telefono",
                "campo_marcador"=>":Telefono",
                "campo_valor"=>$telefono
            ],
            [
                "campo_nombre"=>"usuario_edad",
                "campo_marcador"=>":Edad",
                "campo_valor"=>$edad
            ],
            [
                "campo_nombre"=>"usuario_foto",
                "campo_marcador"=>":Foto",
                "campo_valor"=>$foto
            ],
            [
                "campo_nombre"=>"usuario_creado",
                "campo_marcador"=>":Creado",
                "campo_valor"=>date("Y-m-d H:i:s")
            ]
        ];

        $registrar_usuario= $this-> guardarDatos("usuario",$usuario_datos_reg);

        if($registrar_usuario-> rowCount() == 1) {
            $alerta=[
                "tipo"=>"limpiar",
                "titulo"=>"Usuario registrado",
                "texto"=>"El usuario ".$nombre." se registro con exito",
                "icono"=>"success"
            ];
		} else {	
            //Eliminar foto si no se registro el usuario
            if(is_file($img_dir.$foto)) {
                chmod($img_dir.$foto, 0777);
                unlink($img_dir.$foto);
            }

            $alerta =[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No se pudo registrar el usuario, por favor intente nuevamente",
                "icono"=>"error"
            ];
            
        }

        return json_encode($alerta);

    }

    public function actualizarUsuarioControlador() {
        $id=$this-> limpiarCadena($_POST['usuario_id']);

        #Verificando usuario#
        $datos=$this->ejecutarConsulta("SELECT * FROM usuario WHERE usuario_id='$id'");
        
        if($datos-> rowCount() <= 0){
            $alerta=[
                "tipo"=> "simple",
                "titulo"=> "Ocurrió un error inesperado",
                "texto"=> "No hemos encontrado el usuario en el sistema",
                "icono"=> "error"
            ];
            return json_encode($alerta);
        }else{
            $datos= $datos-> fetch();
        }

        $admin_usuario= $this-> limpiarCadena($_POST['administrador_usuario']);
		$admin_clave= $this-> limpiarCadena($_POST['administrador_clave']);

        #Verificando campos obligatorios admin#
        if($admin_usuario == "" || $admin_clave == ""){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No ha llenado todos los campos que son obligatorios, que corresponden a su USUARIO y CLAVE",
                "icono"=>"error"
            ];
            return json_encode($alerta);
        }

        //Verificacion formato usuario
        if($this-> verificarDatos("[a-zA-Z0-9]{4,20}",$admin_usuario)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"Su USUARIO no coincide con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
		}

        //Verificacion formato clave
        if($this-> verificarDatos("[a-zA-Z0-9$@.-]{7,100}",$admin_clave)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"Su CLAVE no coincide con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
        }

        #Verificando administrador#
        $check_admin = $this-> ejecutarConsulta("SELECT * FROM usuario WHERE usuario_usuario='$admin_usuario' AND usuario_id='".$_SESSION['id']."'");
        if($check_admin-> rowCount() == 1){

            $check_admin = $check_admin-> fetch();

            if($check_admin['usuario_usuario'] != $admin_usuario || !password_verify($admin_clave,$check_admin['usuario_clave'])) {
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"USUARIO o CLAVE de administrador incorrectos",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
            }
        } else {
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"USUARIO o CLAVE de administrador incorrectos",
                "icono"=>"error"
            ];
            return json_encode($alerta);
        }

        #Almacenando datos#
        $nombre = $this->limpiarCadena($_POST['usuario_nombre']);
        $usuario = $this->limpiarCadena($_POST['usuario_usuario']);
        $email = $this->limpiarCadena($_POST['usuario_email']);
        $clave = $this-> limpiarCadena($_POST['usuario_clave']);
        $claveConfirmacion = $this-> limpiarCadena($_POST['usuario_clave_confirmacion']);
        $telefono = $this-> limpiarCadena($_POST['usuario_telefono']);
        $edad = $this-> limpiarCadena($_POST['usuario_edad']);
        // $foto = $this->limpiarCadena($_POST['usuario_foto']);


        #Verificando campos obligatorios#
        if($nombre == "" || $usuario == "" || $email == "") {
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No has llenado todos los campos que son obligatorios",
                "icono"=>"error"
            ];
            return json_encode($alerta);
        }

        #Verificando integridad de los datos#
        if($this-> verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,100}",$nombre)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El NOMBRE no coincide con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
        }

        if($this->verificarDatos("[a-zA-Z0-9]{4,20}",$usuario)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El USUARIO no coincide con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
        }

        //Verificar disponibildiad usuario
        $check_usuario = $this-> ejecutarConsulta(
            "SELECT usuario_usuario FROM usuario 
            WHERE usuario_usuario= '$usuario'");

        if ($check_usuario-> rowCount() > 0) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El nombre de USUARIO ya está registrado",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        # Verificando email #
        if($email != "" && $datos['usuario_email'] != $email){
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                $check_email= $this-> ejecutarConsulta("SELECT usuario_email FROM usuario WHERE usuario_email='$email'");
                if($check_email -> rowCount() > 0){
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"El EMAIL que acaba de ingresar ya se encuentra registrado en el sistema, por favor verifique e intente nuevamente",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                }
            } else {
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"Ha ingresado un correo electrónico no valido",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
            }
        }

        //verificando claves
        if($clave!="" || $claveConfirmacion !=""){
            	if($this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}",$clave) || $this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}",$claveConfirmacion)){

			        $alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"Las CLAVES no coinciden con el formato solicitado",
						"icono"=>"error"
					];
					return json_encode($alerta);
			    }else{
			    	if($clave!=$claveConfirmacion){

						$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Ocurrió un error inesperado",
							"texto"=>"Las nuevas CLAVES que acaba de ingresar no coinciden, por favor verifique e intente nuevamente",
							"icono"=>"error"
						];
						return json_encode($alerta);
			    	}else{
			    		$claveHash=password_hash($clave,PASSWORD_BCRYPT,["cost"=>10]);
			    	}
			    }
			}else{
				$claveHash=$datos['usuario_clave'];
            }

        
        //Verificar formato telefono
        if ($telefono != "") {
            if ($this-> verificarDatos("[+]?[\d ]{7,20}", $telefono)) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "El TELÉFONO no tiene un formato válido",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
        }

        //Verificar formato de edad
        if ($edad != "") {
            if (!ctype_digit($edad) || (int) $edad < 0 || (int) $edad > 120) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "La EDAD debe ser un número válido entre 0 y 120",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
        }

        #Directorio de imagenes#
    	$img_dir="../views/fotos/";

         #Comprobar si se selecciono una imagen#
    	if($_FILES['usuario_foto']['name'] != "" && 
            $_FILES['usuario_foto']['size'] > 0) {

    		#Creando directorio#
		    if(!file_exists($img_dir)){
                //0777 -> permisos de lectura y escritura en el proyecto
		        if(!mkdir($img_dir,0777)){
		            $alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"Error al crear el directorio",
						"icono"=>"error"
					];
					return json_encode($alerta);
		        } 
		    }

            #Verificando formato de imagenes#
		    if(mime_content_type($_FILES['usuario_foto']['tmp_name']) != "image/jpeg" && 
                mime_content_type($_FILES['usuario_foto']['tmp_name']) != "image/png"){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"La imagen que ha seleccionado es de un formato no permitido",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
            }

            #Verificando peso de imagen#
            //de bytes a kbytes -> se divide en 1024
            //maximo 5MB -> 5 * 1024 = 5120 KB
            if(($_FILES['usuario_foto']['size'] / 1024) > 5120){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"La imagen que ha seleccionado supera el peso permitido",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
            }

            #Nombre de la foto#
            //para que los nombres de foto no se repitan
            $foto= str_ireplace(" ","_",$nombre);
            $foto= $foto."_".rand(0,100);

            #Extension de la imagen#
            switch(mime_content_type($_FILES['usuario_foto']['tmp_name'])){
                case 'image/jpeg':
                    $foto= $foto.".jpg";
                break;
                case 'image/png':
                    $foto= $foto.".png";
                break;
            }

            chmod($img_dir,0777);

            #Moviendo imagen al directorio#
            if(!move_uploaded_file($_FILES['usuario_foto']['tmp_name'],$img_dir.$foto)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No podemos subir la imagen al sistema en este momento",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
            }
        } else {
            $foto= "";
        }

        $usuario_datos_up = [
            [
                "campo_nombre"=>"usuario_nombre",
                "campo_marcador"=>":Nombre",
                "campo_valor"=>$nombre
            ],
            [
                "campo_nombre"=>"usuario_usuario",
                "campo_marcador"=>":Usuario",
                "campo_valor"=>$usuario
            ],
            [
                "campo_nombre"=>"usuario_email",
                "campo_marcador"=>":Email",
                "campo_valor"=>$email
            ],
            [
                "campo_nombre"=>"usuario_clave",
                "campo_marcador"=>":Clave",
                "campo_valor"=>$claveHash
            ],
            [
                "campo_nombre"=>"usuario_telefono",
                "campo_marcador"=>":Telefono",
                "campo_valor"=>$telefono
            ],
            [
                "campo_nombre"=>"usuario_edad",
                "campo_marcador"=>":Edad",
                "campo_valor"=>$edad
            ],
            [
                "campo_nombre"=>"usuario_foto",
                "campo_marcador"=>":Foto",
                "campo_valor"=>$foto
            ]
        ];

        $condicion = [
            "condicion_campo"=>"usuario_id",
            "condicion_marcador"=>":ID",
            "condicion_valor"=>$id
        ];

        if($this-> actualizarDatos("usuario",$usuario_datos_up,$condicion)){

            if($id == $_SESSION['id']) {
                $_SESSION['nombre']= $nombre;
                $_SESSION['usuario']= $usuario;
                $_SESSION['telefono'] = $telefono;
                $_SESSION['edad'] = $edad;
                $_SESSION['foto'] = $foto;
            }

            $alerta=[
                "tipo"=>"recargar",
                "titulo"=>"Usuario actualizado",
                "texto"=>"Los datos del usuario ".$datos['usuario_nombre']." se actualizaron correctamente",
                "icono"=>"success"
            ];
        } else {
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No hemos podido actualizar los datos del usuario ".$datos['usuario_nombre'].", por favor intente nuevamente",
                "icono"=>"error"
            ];
        }

        return json_encode($alerta);
    }

}