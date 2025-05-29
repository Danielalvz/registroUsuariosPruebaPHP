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
                    .then(res => res.text()) // ← ver texto plano para debug
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
let btn_exit= document.getElementById("btn_exit");

btn_exit.addEventListener("click", function(e){

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
            let url= this.getAttribute("href");
            window.location.href= url;
        }
    });

});