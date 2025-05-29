<div class="container-fluid my-4 d-flex justify-content-between align-items-center">
    <?php
        $id = $instanciaLogin->limpiarCadena($url[1]);
    ?>

    <div>
        <?php if ($id == $_SESSION['id']): ?>
            <h1 class="h3 mb-1">Mi cuenta</h1>
            <h2 class="h5 text-muted">Actualizar cuenta</h2>
        <?php else: ?>
            <h1 class="h3 mb-1">Usuarios</h1>
            <h2 class="h5 text-muted">Actualizar usuario</h2>
        <?php endif; ?>
    </div>

    <div>
        <?php include "./app/views/inc/btn_back.php"; ?>
    </div>
</div>


<div class="container py-5">
    <?php
        $datos = $instanciaLogin-> seleccionarDatos("Unico", "usuario", "usuario_id", $id);
    ?>

    <?php if ($datos-> rowCount() == 1):
        $datos = $datos-> fetch();
    ?>

    <?php
        $fotoNombre = $datos['usuario_foto'] ?? '';
        $rutaFoto = './app/views/fotos/' . $fotoNombre;

        if (!empty($fotoNombre) && is_file($rutaFoto)) {
            $fotoUrl = APP_URL . 'app/views/fotos/' . $fotoNombre;
        } else {
            $fotoUrl = APP_URL . 'app/views/img/default.png'; // Ruta alternativa si no hay foto
        }
    ?>

    <img 
        src="<?php echo $fotoUrl; ?>" 
        alt="Foto de perfil"
        class="rounded-circle mb-3 mx-auto d-block"
        style="width: 120px; height: 120px; object-fit: cover; border: 2px solid #ddd;"
    >
    <h2 class="text-center mb-4"><?php echo $datos['usuario_nombre']; ?></h2>

    <p class="text-center pb-5">
        <strong>Usuario creado:</strong> <?php echo date("d-m-Y  h:i:s A", strtotime($datos['usuario_creado'])); ?>
    </p>

    <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST"
        autocomplete="off">

        <input type="hidden" name="modulo_usuario" value="actualizar">
        <input type="hidden" name="usuario_id" value="<?php echo $datos['usuario_id']; ?>">

        <div class="row mb-3">
            <div class="col-md-12">
                <label for="usuario_nombre" class="form-label">Nombre completo</label>
                <input id="usuario_nombre" class="form-control" type="text" name="usuario_nombre"
                    pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,100}" maxlength="40"
                    value="<?php echo $datos['usuario_nombre']; ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="usuario_usuario" class="form-label">Usuario</label>
                <input id="usuario_usuario" class="form-control" type="text" name="usuario_usuario"
                    pattern="[a-zA-Z0-9]{4,20}" maxlength="20" value="<?php echo $datos['usuario_usuario']; ?>"
                    required>
            </div>
            <div class="col-md-6">
                <label for="usuario_email" class="form-label">Email</label>
                <input id="usuario_email" class="form-control" type="email" name="usuario_email" maxlength="70"
                    value="<?php echo $datos['usuario_email']; ?>">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="usuario_telefono" class="form-label">Teléfono</label>
                <input id="usuario_telefono" class="form-control" type="text" name="usuario_telefono"
                    pattern="[+]?[\d ]{7,20}" maxlength="20" value="<?php echo $datos['usuario_telefono']; ?>">
            </div>
            <div class="col-md-6">
                <label for="usuario_edad" class="form-label">Edad</label>
                <input id="usuario_edad" class="form-control" type="number" name="usuario_edad" min="0" max="120"
                    value="<?php echo $datos['usuario_edad']; ?>">
            </div>
        </div>

        <div class="row mb-6">
            <div class="col-md-12">
                <label for="usuario_foto" class="form-label">Foto de perfil</label>
                <input class="form-control" type="file" id="usuario_foto" name="usuario_foto" accept=".jpg, .jpeg, .png" value="<?php echo $datos['usuario_foto']; ?>">
                <div class="form-text">Formatos permitidos: JPG, JPEG, PNG. Tamaño máximo 5MB.</div>
        </div>

        <p class="text-center my-4">
            Si desea actualizar la clave de su usuario por favor llene los 2 campos. Si NO desea actualizar la clave
            deje los campos vacíos.
        </p>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="usuario_clave" class="form-label">Nueva clave</label>
                <input id="usuario_clave" class="form-control" type="password" name="usuario_clave"
                    pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100">
            </div>
            <div class="col-md-6">
                <label for="usuario_clave_confirmacion" class="form-label">Repetir nueva clave</label>
                <input id="usuario_clave_confirmacion" class="form-control" type="password" name="usuario_clave_confirmacion"
                    pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100">
            </div>
        </div>

        <p class="text-center my-5">
            Para poder actualizar los datos de este usuario por favor ingrese su USUARIO y CLAVE con la que ha iniciado
            sesión
        </p>

        <div class="row mb-4">
            <div class="col-md-6">
                <label for="administrador_usuario" class="form-label">Usuario</label>
                <input id="administrador_usuario" class="form-control" type="text" name="administrador_usuario"
                    pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required>
            </div>
            <div class="col-md-6">
                <label for="administrador_clave" class="form-label">Clave</label>
                <input id="administrador_clave" class="form-control" type="password" name="administrador_clave"
                    pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
            </div>
        </div>

        <p class="text-center">
            <button type="submit" class="btn btn-primary rounded-pill">Actualizar</button>
        </p>
    </form>

<?php else: ?>
    <?php include "./app/views/inc/error_alert.php"; ?>
<?php endif; ?>

</div>