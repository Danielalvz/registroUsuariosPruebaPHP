<div class="container mb-5">
    <?php
        $id = $instanciaLogin->limpiarCadena($url[1]);
    ?>
    <?php if ($id == $_SESSION['id']): ?>

    <h1 class="display-6">Mi foto de perfil</h1>
    <h2 class="h5 text-muted">Actualizar foto de perfil</h2>
    <?php else: ?>
    <h1 class="display-6 mt-4">Usuarios</h1>
    <h2 class="h5 text-muted">Actualizar foto de perfil</h2>
    <?php endif; ?>
</div>

<div class="container py-5">

    <div>
        <?php include "./app/views/inc/btn_back.php"; ?>
    </div>

    <?php
        $datos = $instanciaLogin-> seleccionarDatos("Unico", "usuario", "usuario_id", $id);
    ?>

    <?php if ($datos-> rowCount() == 1):
        $datos = $datos-> fetch();
    ?>

    <h2 class="text-center mb-4"><?php echo $datos['usuario_nombre']; ?></h2>

    <p class="text-center pb-5">
        <strong>Usuario creado:</strong> <?php echo date("d-m-Y  h:i:s A", strtotime($datos['usuario_creado'])); ?>
    </p>

    <div class="row">
        <div class="col-md-5">
            <?php if(is_file("./app/views/fotos/".$datos['usuario_foto'])):

            ?>
            <div class="mb-4 text-center">
                <img src="<?php echo APP_URL; ?>app/views/fotos/<?php echo $datos['usuario_foto']; ?>" class="rounded-circle img-fluid" style="max-width: 200px;">
            </div>

            <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" autocomplete="off">
                <input type="hidden" name="modulo_usuario" value="eliminarFoto">
                <input type="hidden" name="usuario_id" value="<?php echo $datos['usuario_id']; ?>">

                <div class="text-center mb-5">
                    <button type="submit" class="btn btn-danger rounded-pill">Eliminar foto</button>
                </div>
            </form>

            <?php else: ?>
                <?php ?>
            <div class="text-center">
                <img src="<?php echo APP_URL; ?>app/views/fotos/default.png" class="rounded-circle img-fluid" style="max-width: 200px;">
            </div>
            <?php endif; ?>
        </div>

        <div class="col-md-7">
            <form class="FormularioAjax text-center" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="modulo_usuario" value="actualizarFoto">
                <input type="hidden" name="usuario_id" value="<?php echo $datos['usuario_id']; ?>">

                <div class="mb-3">
                    <label class="form-label">Foto o imagen del usuario</label>
                    <div class="input-group mb-3">
                        <input type="file" class="form-control" name="usuario_foto" accept=".jpg, .png, .jpeg">
                    </div>
                    <div class="form-text">JPG, JPEG, PNG. (MAX 5MB)</div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success rounded-pill">Actualizar foto</button>
                </div>
            </form>
        </div>
    </div>

    <?php else: ?>
        <?php include "./app/views/inc/error_alert.php"; ?>
    <?php endif; ?>
</div>
