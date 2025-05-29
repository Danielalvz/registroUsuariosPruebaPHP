<div class="container py-5">
  <?php
    $id = $instanciaLogin->limpiarCadena($url[1]);
    $datos = $instanciaLogin->seleccionarDatos("Unico", "usuario", "usuario_id", $id);

    if ($datos->rowCount() == 1):
      $datos = $datos->fetch();
  ?>

  <div class="card mx-auto shadow-lg" style="max-width: 600px;">
    <div class="card-body text-center">
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
      <h3 class="card-title mb-1"><?php echo $datos['usuario_nombre']; ?></h3>
      <p class="text-muted">@<?php echo $datos['usuario_usuario']; ?></p>
      <hr>

      <div class="text-start">
        <p><strong>Email:</strong> <?php echo $datos['usuario_email'] ?: 'No registrado'; ?></p>
        <p><strong>Teléfono:</strong> <?php echo $datos['usuario_telefono'] ?: 'No registrado'; ?></p>
        <p><strong>Edad:</strong> <?php echo $datos['usuario_edad'] ?: 'No especificada'; ?></p>
        <p><strong>Fecha de creación:</strong> <?php echo date("d-m-Y  h:i:s A", strtotime($datos['usuario_creado'])); ?></p>
      </div>

      <a href="<?php echo APP_URL."userUpdate/".$_SESSION['id']."/"; ?>" class="btn btn-primary mt-4">Editar perfil</a>
    </div>
  </div>

  <?php else: ?>
    <?php include "./app/views/inc/error_alert.php"; ?>
  <?php endif; ?>
</div>
