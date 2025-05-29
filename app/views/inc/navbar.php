<nav class="navbar navbar-expand-lg bg-dark border-bottom border-body" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="<?php echo APP_URL; ?>/dashboard/">
        <img src="<?php echo APP_URL; ?>app/views/img/cerok.png" alt="Logo CERO K" width="100" height="28" class="ms-2">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="<?php echo APP_URL; ?>/dashboard/">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo APP_URL; ?>userNew/">Registro</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo APP_URL; ?>">Login</a>
        </li>

        <?php if (isset($_SESSION['usuario']) && isset($_SESSION['id'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo APP_URL; ?>userProfile/<?php echo $_SESSION['id']; ?>/">Perfil</a>
          </li>
        <?php endif; ?>
      </ul>
      
      <!-- Menú derecho: Usuario logueado -->
      <?php if (isset($_SESSION['usuario']) && isset($_SESSION['id'])): ?>
        <ul class="navbar-nav mb-2 mb-lg-0">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?php echo $_SESSION['usuario']; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="<?php echo APP_URL."userUpdate/".$_SESSION['id']."/"; ?>">Mi cuenta</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="<?php echo APP_URL."logOut/"; ?>" id="btn_exit">Salir</a></li>
            </ul>
          </li>
        </ul>
      <?php endif; ?>

      <span class="navbar-text">
        Usuarios de CERO K
      </span>
    </div>
  </div>
</nav>