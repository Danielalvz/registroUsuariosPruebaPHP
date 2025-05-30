<div class="container-fluid mt-4 mb-4 text-center">
  <h1 class="display-5">Mensajes</h1>
  <h2 class="h5 text-muted">Enviar nuevo mensaje</h2>
</div>

<div class="container py-4">

  <form class="FormularioAjax" id="formMensaje" method="POST" action="<?php echo APP_URL; ?>app/ajax/mensajeAjax.php" autocomplete="off">
    <input type="hidden" name="accion_mensaje" value="enviar">

    <div class="row mb-3">
      <div class="col-md-6">
        <label for="receptor_id" class="form-label">Seleccionar usuario</label>
        <select name="receptor_id" id="receptor_id" class="form-select" required>
          <option value="">Cargando usuarios...</option>
        </select>
      </div>
      <div class="col-md-6">
        <label for="mensaje_texto" class="form-label">Mensaje</label>
        <textarea name="mensaje_texto" id="mensaje_texto" class="form-control" rows="3" required></textarea>
      </div>
    </div>

    <div class="text-center">
      <button type="reset" class="btn btn-outline-primary rounded-pill me-2">Limpiar</button>
      <button type="submit" class="btn btn-primary rounded-pill">Enviar</button>
    </div>
  </form>

  <hr class="my-5">

  <h2 class="h5">Bandeja de entrada</h2>
  <div id="bandejaMensajes" class="mt-3"></div>
</div>

<script>
  const BASE_URL = "<?php echo APP_URL; ?>";
</script>
