<div class="container min-vh-100 d-flex justify-content-center align-items-center flex-column">
  <h2 class="mb-4 text-center text-primary fw-bold">¡Bienvenidos a la plataforma de usuarios!</h2>
  
  <form class="FormularioAjax p-4 border rounded shadow" action="<?php echo APP_URL; ?>app/ajax/loginAjax.php" style="min-width: 300px; max-width: 400px; width: 100%;" method="POST" autocomplete="off">
    
    <h5 class="text-center text-uppercase mb-4">LOGIN</h5>

    <div class="mb-3">
      <label for="login_usuario" class="form-label">Usuario</label>
      <input type="text" class="form-control" id="login_usuario" name="login_usuario"
             pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required>
    </div>

    <div class="mb-4">
      <label for="login_clave" class="form-label">Clave</label>
      <input type="password" class="form-control" id="login_clave" name="login_clave"
             pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-primary rounded-pill px-4">Iniciar sesión</button>
    </div>

  </form>
  <div class="mt-4 text-center">
    <a href="<?php echo APP_URL; ?>dashboard" class="text-decoration-none">
      <span class="text-muted">¿No tienes cuenta?</span>
      <span class="fw-bold text-primary">Ir a la plataforma y registrarse</span>
    </a>
  </div>
</div>
