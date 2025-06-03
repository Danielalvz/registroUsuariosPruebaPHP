<?php
  $token = $_GET['token'] ?? '';

  if (!$token) {
    echo "<div class='container text-center mt-5'><h4 class='text-danger'>Token inválido</h4></div>";
    exit;
  }
?>

<div class="container min-vh-100 d-flex justify-content-center align-items-center flex-column">
  <h2 class="mb-4 text-center text-primary fw-bold">Restablecer contraseña</h2>

  <form class="FormularioAjax p-4 border rounded shadow" 
        action="<?php echo APP_URL; ?>app/ajax/actualizarClaveAjax.php" 
        method="POST" 
        style="min-width: 300px; max-width: 400px; width: 100%;" 
        autocomplete="off">

    <input type="hidden" name="modulo" value="actualizar-clave">
    <input type="hidden" name="token" value="<?php echo $token; ?>">

    <h5 class="text-center text-uppercase mb-4">Nueva clave</h5>

    <div class="mb-3">
      <label for="password" class="form-label">Nueva contraseña</label>
      <input type="password" name="password" class="form-control" id="password" required>
    </div>

    <div class="mb-3">
      <label for="password_confirm" class="form-label">Confirmar contraseña</label>
      <input type="password" name="password_confirm" class="form-control" id="password_confirm" required>
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-primary rounded-pill px-4">Actualizar contraseña</button>
    </div>

  </form>
</div>
