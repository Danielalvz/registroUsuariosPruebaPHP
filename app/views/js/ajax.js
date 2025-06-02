const forms_ajax = document.querySelectorAll(".FormularioAjax");

forms_ajax.forEach((form) => {
    form.addEventListener("submit", function (e) {
        e.preventDefault();

        Swal.fire({
            title: "¿Estas seguro?",
            text: "Quieres realizar la acción solicitada",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, enviar",
            cancelButtonText: "No, cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                let data = new FormData(this);
                let method = this.getAttribute("method");
                let action = this.getAttribute("action");

                let encabezados = new Headers();

                let config = {
                    method: method,
                    headers: encabezados,
                    cache: 'no-cache',
                    body: data
                }

                fetch(action, config)
                    .then(res => res.text()) // ver texto plano para debug
                    .then(text => {
                        console.log("Respuesta cruda:", text);
                        try {
                            const json = JSON.parse(text);
                            alertas_ajax(json);
                        } catch (e) {
                            console.error("Error al parsear JSON:", e);
                        }
                    })
                    .catch(error => {
                        console.error("Error en fetch:", error);
                        Swal.fire("Error", "No se pudo procesar la solicitud", "error");
                    });

            }
        });
    });
});

function alertas_ajax(alerta) {
    if (alerta.tipo == "simple") {

        Swal.fire({
            icon: alerta.icono,
            title: alerta.titulo,
            text: alerta.texto,
            confirmButtonText: 'Aceptar'
        });

    } else if (alerta.tipo == "recargar") {

        Swal.fire({
            icon: alerta.icono,
            title: alerta.titulo,
            text: alerta.texto,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.isConfirmed) {
                location.reload();
            }
        });

    } else if (alerta.tipo == "limpiar") {

        Swal.fire({
            icon: alerta.icono,
            title: alerta.titulo,
            text: alerta.texto,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector(".FormularioAjax").reset();
            }
        });

    } else if (alerta.tipo == "redireccionar") {
        window.location.href = alerta.url;
    }
}

// Boton para cerrar sesion
let btn_exit = document.getElementById("btn_exit");

btn_exit.addEventListener("click", function (e) {

    e.preventDefault();

    Swal.fire({
        title: '¿Quieres salir del sistema?',
        text: "La sesión actual se cerrará y saldrás del sistema",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, salir',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            let url = this.getAttribute("href");
            window.location.href = url;
        }
    });

});

// Cargar mensajes

document.addEventListener("DOMContentLoaded", () => {
    cargarUsuarios();
    cargarMensajes();
});

function cargarUsuarios() {
    fetch(`${BASE_URL}app/ajax/mensajeAjax.php?accion=usuarios`)
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById("receptor_id");
            select.innerHTML = '<option value="">Seleccione un usuario</option>';
            data.forEach(usuario => {
                const option = document.createElement("option");
                option.value = usuario.usuario_id;
                option.textContent = usuario.usuario_nombre;
                select.appendChild(option);
            });
        })
        .catch(() => {
            document.getElementById("receptor_id").innerHTML = "<option>Error al cargar usuarios</option>";
        });
}

function cargarMensajes() {
    fetch(`${BASE_URL}app/ajax/mensajeAjax.php?accion=obtener`)
        .then(res => res.json())
        .then(data => {
            const contenedor = document.getElementById("bandejaMensajes");
            if (data.length === 0) {
                contenedor.innerHTML = "<p>No tienes mensajes.</p>";
                return;
            }

             contenedor.innerHTML = data.map(msg => `
                <div class="card mb-3">
                  <div class="card-body d-flex align-items-center">
                    <img src="${BASE_URL}app/views/fotos/${msg.emisor_foto}" alt="Foto de ${msg.emisor_nombre}" class="rounded-circle me-3" width="50" height="50">
                    <div>
                      <h6 class="card-subtitle mb-1 text-muted">
                        De: ${msg.emisor_nombre} - ${msg.mensaje_fecha}
                      </h6>
                      <p class="card-text mb-0">${msg.mensaje_texto}</p>
                    </div>
                  </div>
                </div>
            `).join("");
        })
        .catch(err => {
            console.error("Error al cargar mensajes:", err);
        });
}


