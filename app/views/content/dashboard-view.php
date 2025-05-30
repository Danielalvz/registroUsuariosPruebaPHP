<div class="container py-5">
  <h1 class="display-4 text-center mb-5">Bienvenido a la Plataforma</h1>

  <div class="row justify-content-center mb-4">
    <div class="col-auto text-center">
      <?php
        if (isset($_SESSION['foto']) && is_file("./app/views/fotos/". $_SESSION['foto'])) {
            $foto = APP_URL.'app/views/fotos/'.$_SESSION['foto'];
        } else {
            $foto = APP_URL.'app/views/img/login.gif'; 
        }
      ?>
      <img src="<?php echo $foto; ?>" class="" width="180" height="180" alt="Foto de perfil" style="object-fit: cover;">
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="text-center mb-4">
        <?php if (isset($_SESSION['nombre'])): ?>
          <h2 class="fw-bold">¡Hola, <?php echo $_SESSION['nombre']; ?>!</h2>
          <div class="d-flex justify-content-center gap-3 mt-3">
            <a href="<?php echo APP_URL; ?>userProfile/<?php echo $_SESSION['id']; ?>/" class="btn btn-outline-primary btn-md">Ver perfil</a>
            <a href="<?php echo APP_URL; ?>userUpdate/<?php echo $_SESSION['id']; ?>/" class="btn btn-outline-secondary btn-md">Editar perfil</a>
          </div>
        <?php else: ?>
          <h2 class="fw-bold">¡Bienvenido visitante!</h2>
          <p class="mt-3">¿No tienes una cuenta? 
            <a href="<?php echo APP_URL; ?>userNew/">Regístrate aquí</a>
          </p>
          <p class="text-muted small">
            En esta plataforma podrás crear tu propio usuario, editar tu perfil y, enviar mensajes a otros usuarios registrados.
          </p>
        <?php endif; ?>
      </div>

      <hr>
      <div class="card shadow rounded-3 border-primary border-2 mt-4">
        <div class="card-header bg-primary text-white text-center fs-5 fw-semibold rounded-top">
          📰 Noticias
        </div>
        <div class="card-body bg-light">
          <p class="card-text small text-justify">
            Próximamente se mejorará la función de mensajes, y se implementará la función de recuperación de contraseña via email, gracias!
          </p>
          <div class="text-center mt-3">
            <img src="<?php echo APP_URL.'app/views/img/construccion.gif'; ?>" 
                  alt="En construcción" 
                  class="img-fluid rounded shadow-sm" 
                  style="max-width: 60%; height: auto;">
          </div>
        </div>
      </div>

    </div>
  </div>
  
</div>
