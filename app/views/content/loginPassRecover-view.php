<div class="container min-vh-100 d-flex justify-content-center align-items-center flex-column">
  <h2 class="mb-4 text-center text-primary fw-bold">¿Olvidaste tu contraseña?</h2>
  
  <form class="FormularioAjax p-4 border rounded shadow" 
        action="<?php echo APP_URL; ?>app/ajax/recuperarAjax.php" 
        method="POST" 
        style="min-width: 300px; max-width: 400px; width: 100%;" 
        autocomplete="off">

    <input type="hidden" name="accion_usuario" value="recuperar_clave">

    <h5 class="text-center text-uppercase mb-4">Recuperación de clave</h5>

    <div class="mb-3">
      <label for="correo" class="form-label">Correo electrónico</label>
      <input type="email" class="form-control" id="correo" name="usuario_email" required>
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-primary rounded-pill px-4">Enviar enlace</button>
    </div>

    <div class="mt-3 text-center">
      <a href="<?php echo APP_URL; ?>login/" class="text-decoration-none text-primary">Volver al login</a>
    </div>
    
  </form>
</div>

<script>
  const BASE_URL = "<?php echo APP_URL; ?>";
</script>
