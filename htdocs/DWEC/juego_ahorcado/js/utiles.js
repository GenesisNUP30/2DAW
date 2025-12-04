// Cuando la página carga
document.addEventListener("DOMContentLoaded", () => {
    const categoriaSelect = document.getElementById("categoria");

    if (categoriaSelect) {
        fetch("/obtener_categorias.php")
            .then(res => res.json())
            .then(categorias => {
                categoriaSelect.innerHTML = ""; // Limpiar opciones
                categorias.forEach(categoria => {
                    const option = document.createElement("option");
                    option.value = categoria;
                    option.textContent = categoria;
                    categoriaSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error("Error al cargar las categorías:", error);
                categoriaSelect.innerHTML = "<option value=''>Error al cargar</option>";
            });
    }
});

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

function iniciarJuego() {
    const categoria = document.getElementById("categoria").value;
    const mensaje = document.getElementById("mensaje");

    if (!categoria) {
        mensaje.textContent = "Por favor, selecciona una categoría.";
        return;
    }

    fetch(`php/obtener_palabra.php?categoria=${encodeURIComponent(categoria)}`)
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                palabraActual = data.palabra; // Guardar la palabra actual
                mostrarPalabraOculta(palabraActual);
                document.getElementById("categoria-seleccionada").textContent = categoria;
                document.getElementById("seleccion-categoria").style.display = "none";
                document.getElementById("juego").style.display = "block";
            } else {
                mensaje.textContent = "No se pudo obtener una palabra. Intenta nuevamente.";
            }
        })
        .catch(error => {
            console.error("Error al iniciar el juego:", error);
            mensaje.textContent = "Hubo un problema al iniciar el juego.";
        });
}

function mostrarPalabraOculta(palabra) {
    const palabraOculta = palabra.split("").map(() => "_").join(" ");
    document.getElementById("palabra").textContent = palabraOculta;
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