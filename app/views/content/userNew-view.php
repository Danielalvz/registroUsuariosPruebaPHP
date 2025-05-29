<div class="container-fluid mb-4">
  <h1 class="display-5">Usuarios</h1>
  <h2 class="h5 text-muted">Nuevo usuario</h2>
</div>

<div class="container py-4">
  <form class="FormularioAjax" method="POST" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="modulo_usuario" value="registrar">
    <input type="hidden" name="fecha_creacion" value="<?php echo date('Y-m-d'); ?>">

    <div class="row mb-3">
      <div class="col-md-12">
        <label for="usuario_nombre" class="form-label">Nombre completo</label>
        <input type="text" class="form-control" id="usuario_nombre" name="usuario_nombre"
               maxlength="40" required>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label for="usuario_usuario" class="form-label">Usuario</label>
        <input type="text" class="form-control" id="usuario_usuario" name="usuario_usuario"
               pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required>
      </div>
      <div class="col-md-6">
        <label for="usuario_email" class="form-label">Email</label>
        <input type="email" class="form-control" id="usuario_email" name="usuario_email" maxlength="70" required>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label for="usuario_clave" class="form-label">Clave</label>
        <input type="password" class="form-control" id="usuario_clave" name="usuario_clave"
               pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
      </div>
      <div class="col-md-6">
        <label for="usuario_clave_confirmacion" class="form-label">Repetir clave</label>
        <input type="password" class="form-control" id="usuario_clave_confirmacion" name="usuario_clave_confirmacion"
               pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label for="usuario_telefono" class="form-label">Teléfono (opcional)</label>
        <input type="text" class="form-control" id="usuario_telefono" name="usuario_telefono"
               pattern="[+]?[\d ]{7,20}" maxlength="20">
      </div>
      <div class="col-md-6">
        <label for="usuario_edad" class="form-label">Edad (opcional)</label>
        <input type="number" class="form-control" id="usuario_edad" name="usuario_edad" min="0" max="120">
      </div>
    </div>

    <div class="mb-4">
      <label for="usuario_foto" class="form-label">Foto de perfil</label>
      <input class="form-control" type="file" id="usuario_foto" name="usuario_foto" accept=".jpg, .jpeg, .png">
      <div class="form-text">Formatos permitidos: JPG, JPEG, PNG. Tamaño máximo 5MB.</div>
    </div>

    <div class="text-center">
      <button type="reset" class="btn btn-outline-primary rounded-pill me-2">Limpiar</button>
      <button type="submit" class="btn btn-primary rounded-pill">Guardar</button>
    </div>
  </form>
</div>
