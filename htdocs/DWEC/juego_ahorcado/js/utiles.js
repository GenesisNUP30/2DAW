// Cuando la página carga
function iniciarSesion() {
    const mensaje = document.getElementById("mensaje");

    const usuario = document.getElementById("usuario").value;
    const password = document.getElementById("password").value;

    if (!usuario || !password) {
        mensaje.textContent = "Por favor, completa todos los campos.";
        return;
    }

    const url = `php/validar_login.php?usuario=${encodeURIComponent(usuario)}&password=${encodeURIComponent(password)}`;
    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                if (data.rol === "admin") {
                    window.location.href = "php/administracion.php"; // Redirige a la página de administración
                } else if (data.rol === "jugador") {
                    window.location.href = "php/juego.php"; // Redirige a la página del juego
                }
            } else {
                mensaje.textContent = data.message; // Muestra el mensaje de error
            }
        })
        .catch(error => {
            console.error("Error en la solicitud:", error);
            mensaje.textContent = "Hubo un problema al iniciar sesión.";
        });
}

function validarLetra() {
    const letra = document.getElementById("letra").value;
    const mensaje = document.getElementById("mensaje");

    if (!letra) {
        mensaje.textContent = "Por favor, introduce una letra.";
        return;
    }

    const url = `php/validar_letra.php?letra=${encodeURIComponent(letra)}&palabra=${encodeURIComponent(palabraActual)}`;
    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                actualizarPalabra(data.posiciones, letra);
            } else {
                mensaje.textContent = data.message;
                reducirIntentos();
            }
        });
}

function resolverPalabra() {
    const respuesta = prompt("Introduce la palabra completa:");
    const url = `php/resolver_palabra.php?respuesta=${encodeURIComponent(respuesta)}&palabra=${encodeURIComponent(palabraActual)}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                alert("¡Felicidades! Has ganado.");
                location.reload();
            } else {
                alert("Respuesta incorrecta. Has perdido.");
                location.reload();
            }
        });
}