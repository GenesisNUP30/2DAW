// js/utiles.js

// Variables globales
let palabra_id_global = null;
let partida_activa = false;
let usuarioLogueado = null;
let esAdmin = false;

// Al cargar la página
document.addEventListener("DOMContentLoaded", () => {
    // Si estamos en juego.html, verificar sesión y cargar categorías
    if (document.getElementById("categoria")) {
        verificarSesionYJugar();
    }
    // Si estamos en administracion.html, verificar admin y cargar datos
    if (document.getElementById("lista-categorias")) {
        verificarAdmin();
    }
});

function login() {
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const mensaje = document.getElementById("mensaje");

    const url = `php/login.php?username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`;

    fetch(url)
        .then(res => res.text())
        .then(data => {
            if (data === "admin") {
                window.location.href = "administracion.html";
            } else if (data === "jugador") {
                window.location.href = "juego.html";
            } else {
                mensaje.innerHTML = "<div class='alert alert-danger'>Usuario o contraseña incorrectos</div>";
            }
        })
        .catch(error => {
            console.error(error);
            mensaje.innerHTML = "<div class='alert alert-danger'>Error al conectar</div>";
        });
}

function irAlJuego() {
    window.location.href = "juego.html";
}


function verificarSesionYJugar() {
    fetch("php/check_session.php")
        .then(res => res.text())
        .then(data => {
            if (data === "no_logueado") {
                window.location.href = "login.html";
                return;
            }
            // Obtener si es admin
            return fetch("php/obtener_rol.php")
                .then(r => r.text())
                .then(rol => {
                    esAdmin = (rol === "1");
                    if (esAdmin) {
                        document.getElementById("boton-admin").style.display = "inline-block";
                    }
                    cargarCategorias();
                });
        })
        .catch(error => console.error(error));
}

function cargarCategorias() {
    const select = document.getElementById("categoria");
    const cargando = document.getElementById("cargando-categorias");

    fetch("php/obtener_categorias.php")
        .then(res => res.json())
        .then(data => {
            select.innerHTML = "";
            data.forEach(cat => {
                const option = document.createElement("option");
                option.value = cat.id;
                option.textContent = cat.nombre;
                select.appendChild(option);
            });
            cargando.style.display = "none";
            select.style.display = "block";
        })
        .catch(error => console.error(error));
}

function iniciarPartida() {
    const catId = document.getElementById("categoria").value;
    if (!catId) return;

    const url = `php/nueva_partida.php?categoria_id=${catId}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                alert("Error: " + data.error);
                return;
            }
            document.getElementById("palabra-oculta").textContent = data.palabra_oculta;
            document.getElementById("errores").textContent = "0";
            document.getElementById("puntos").textContent = "0";
            document.getElementById("imagen-ahorcado").src = "img/0.jpg";
            document.getElementById("mensaje-juego").innerHTML = "";
            document.getElementById("zona-juego").style.display = "block";
            generarTeclado();
            partida_activa = true;
        })
        .catch(error => console.error(error));
}

function generarTeclado() {
    const tecladoDiv = document.getElementById("teclado");
    tecladoDiv.innerHTML = "";
    // Letras del alfabeto español (incluye ñ)
    const letras = "abcdefghijklmnñopqrstuvwxyz".split("");

    letras.forEach(letra => {
        const btn = document.createElement("button");
        btn.type = "button";
        btn.className = "btn btn-outline-secondary m-1";
        btn.textContent = letra.toUpperCase();
        btn.onclick = () => jugarLetra(letra);
        tecladoDiv.appendChild(btn);
    });
}

function jugarLetra(letra) {
    if (!partida_activa) {
        alert("No hay una partida activa");
        return;
    }

    const url = `php/jugar_letra.php?letra=${letra}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.repetida) {
                alert("Ya has jugado esa letra");
                return;
            }

            // Actualizar imagen
            document.getElementById("imagen-ahorcado").src = `img/${data.errores}.jpg`;

            // Actualizar palabra
            document.getElementById("palabra-oculta").textContent = data.palabra_oculta;

            // Actualizar contadores
            document.getElementById("errores").textContent = data.errores;
            document.getElementById("puntos").textContent = data.puntos;

            // Deshabilitar botón
            const btn = Array.from(document.querySelectorAll("#teclado button"))
                .find(b => b.textContent.toLowerCase() === letra);
            if (btn) {
                btn.disabled = true;
                btn.className = data.acierto
                    ? "btn btn-success m-1"
                    : "btn btn-danger m-1";
            }

            // Verificar fin de partida
            if (data.estado === 'ganada' || data.estado === 'perdida') {
                finalizarPartida(data.estado, data.puntos);
            }
        })
        .catch(error => console.error(error));
}

function finalizarPartida(estado, puntos) {
    partida_activa = false;
    const msg = estado === 'ganada'
        ? `¡Has ganado! Puntos: ${puntos}`
        : `¡Has perdido! Puntos: ${puntos}`;
    document.getElementById("mensaje-juego").innerHTML =
        `<div class="alert alert-${estado === 'ganada' ? 'success' : 'danger'}">${msg}</div>`;

    // Guardar puntuación
    fetch("php/guardar_puntuacion.php")
        .then(res => res.text())
        .then(data => console.log("Puntuación guardada"));
}

function resolverPalabra() {
    if (!partida_activa) {
        alert("No hay una partida activa");
        return;
    }
    const palabra = document.getElementById("palabra-resolver").value;
    if (!palabra) {
        alert("Escribe una palabra");
        return;
    }

    const url = `php/resolver.php?palabra=${encodeURIComponent(palabra)}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            finalizarPartida(data.resultado, data.puntos);
            document.getElementById("palabra-resolver").value = "";
        })
        .catch(error => console.error(error));
}

function nuevaPartida() {
    partida_activa = false;
    document.getElementById("categoria").value = "";
    document.getElementById("zona-juego").style.display = "none";
    document.getElementById("mensaje-juego").innerHTML = "";
}

function salirJuego() {
    fetch("php/logout.php")
        .then(() => {
            window.location.href = "login.html";
        })
        .catch(error => console.error(error));
}

// ===== ADMINISTRACIÓN =====

function verificarAdmin() {
    fetch("php/check_admin.php")
        .then(res => res.text())
        .then(data => {
            if (data !== "ok") {
                alert("Acceso denegado. Solo administradores.");
                window.location.href = "juego.html";
                return;
            }
            // Obtener nombre del usuario logueado
            return fetch("php/obtener_usuario_logueado.php")
                .then(r => r.text())
                .then(nombre => {
                    usuarioLogueado = nombre;
                    cargarAdminData();
                });
        })
        .catch(error => {
            console.error(error);
            window.location.href = "login.html";
        });
}

function cargarAdminData() {
    cargarCategoriasAdmin();
    cargarPalabrasAdmin();
    cargarJugadores();
}

function irAdmin() {
    window.location.href = "administracion.html";
}

function cargarCategoriasAdmin() {
    fetch("php/admin_categorias.php?accion=listar")
        .then(res => res.json())
        .then(data => {
            let html = "<table class='table table-sm'><thead><tr><th>ID</th><th>Nombre</th><th>Acciones</th></tr></thead><tbody>";
            data.forEach(c => {
                html += `
                <tr id="cat-fila-${c.id}">
                    <td>${c.id}</td>
                    <td>
                        <span id="cat-nombre-${c.id}">${c.nombre}</span>
                        <input type="text" id="cat-edit-${c.id}" value="${c.nombre}" class="form-control form-control-sm" style="display:none">
                    </td>
                    <td>
                        <button onclick="editarCategoria(${c.id})" class="btn btn-sm btn-warning">Editar</button>
                        <button onclick="guardarCategoria(${c.id})" id="btn-guardar-cat-${c.id}" class="btn btn-sm btn-success" style="display:none">Guardar</button>
                        <button onclick="eliminarCategoria(${c.id})" class="btn btn-sm btn-danger">Eliminar</button>
                    </td>
                </tr>`;
            });
            html += "</tbody></table>";
            document.getElementById("lista-categorias").innerHTML = html;
        });
}

function agregarCategoria() {
    const nombre = document.getElementById("nueva-categoria").value;
    if (!nombre) return;
    const url = `php/admin_categorias.php?accion=agregar&nombre=${encodeURIComponent(nombre)}`;
    fetch(url)
        .then(res => res.text())
        .then(() => {
            alert("Categoría agregada");
            cargarCategoriasAdmin();
            document.getElementById("nueva-categoria").value = "";
        });
}

function cargarPalabrasAdmin() {
    // Cargar categorías en el select (para agregar nuevas)
    fetch("php/obtener_categorias.php")
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById("categoria-palabra");
            select.innerHTML = "";
            data.forEach(c => {
                const opt = document.createElement("option");
                opt.value = c.id;
                opt.textContent = c.nombre;
                select.appendChild(opt);
            });
        });

    // Cargar lista de palabras
    fetch("php/admin_palabras.php?accion=listar")
        .then(res => res.json())
        .then(data => {
            // También necesitamos categorías para el select de edición
            fetch("php/obtener_categorias.php")
                .then(r => r.json())
                .then(cats => {
                    let html = "<table class='table table-sm'><thead><tr><th>ID</th><th>Palabra</th><th>Categoría</th><th>Acciones</th></tr></thead><tbody>";
                    data.forEach(p => {
                        // Opciones para select de edición
                        let opciones = "";
                        cats.forEach(c => {
                            const sel = c.id == p.categoria_id ? "selected" : "";
                            opciones += `<option value="${c.id}" ${sel}>${c.nombre}</option>`;
                        });

                        html += `
                        <tr id="pal-fila-${p.id}">
                            <td>${p.id}</td>
                            <td>
                                <span id="pal-texto-${p.id}">${p.palabra}</span>
                                <input type="text" id="pal-edit-${p.id}" value="${p.palabra}" class="form-control form-control-sm" style="display:none">
                            </td>
                            <td>
                                <span id="pal-cat-${p.id}">${p.categoria}</span>
                                <select id="pal-cat-edit-${p.id}" class="form-select form-select-sm" style="display:none">${opciones}</select>
                            </td>
                            <td>
                                <button onclick="editarPalabra(${p.id})" class="btn btn-sm btn-warning">Editar</button>
                                <button onclick="guardarPalabra(${p.id})" id="btn-guardar-pal-${p.id}" class="btn btn-sm btn-success" style="display:none">Guardar</button>
                                <button onclick="eliminarPalabra(${p.id})" class="btn btn-sm btn-danger">Eliminar</button>
                            </td>
                        </tr>`;
                    });
                    html += "</tbody></table>";
                    document.getElementById("lista-palabras").innerHTML = html;
                });
        });
}

function agregarPalabra() {
    const palabra = document.getElementById("nueva-palabra").value;
    const catId = document.getElementById("categoria-palabra").value;
    if (!palabra || !catId) return;
    const url = `php/admin_palabras.php?accion=agregar&palabra=${encodeURIComponent(palabra)}&categoria_id=${catId}`;
    fetch(url)
        .then(res => res.text())
        .then(() => {
            alert("Palabra agregada");
            cargarPalabrasAdmin();
            document.getElementById("nueva-palabra").value = "";
        });
}


function verHistorial() {
    const div = document.getElementById("historial-partidas");
    const passDiv = document.getElementById("cambiar-pass");
    if (div.style.display === "none") {
        div.style.display = "block";
        passDiv.style.display = "none";
        cargarHistorial();
    } else {
        div.style.display = "none";
    }
}

function cambiarPassword1() {
    const div = document.getElementById("cambiar-pass");
    const histDiv = document.getElementById("historial-partidas");
    if (div.style.display === "none") {
        div.style.display = "block";
        histDiv.style.display = "none";
    } else {
        div.style.display = "none";
    }
}

function cargarHistorial() {
    fetch("php/ver_historial.php")
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                document.getElementById("lista-partidas").innerHTML = "<p>No tienes partidas aún.</p>";
                return;
            }
            let html = "<table class='table table-sm'><thead><tr><th>Palabra</th><th>Estado</th><th>Puntos</th><th>Fecha</th></tr></thead><tbody>";
            data.forEach(p => {
                const estado = p.estado === 'ganada' ? '✅ Ganada' : '❌ Perdida';
                html += `<tr>
                    <td>${p.palabra}</td>
                    <td>${estado}</td>
                    <td>${p.puntos}</td>
                    <td>${p.fecha}</td>
                </tr>`;
            });
            html += "</tbody></table>";
            document.getElementById("lista-partidas").innerHTML = html;
        })
        .catch(err => {
            console.error(err);
            document.getElementById("lista-partidas").innerHTML = "<p>Error al cargar historial.</p>";
        });
}

function cambiarPassword2() {
    const nueva = document.getElementById("nueva-pass").value;
    if (!nueva) {
        alert("Escribe una contraseña");
        return;
    }
    const url = `php/cambiar_password.php?nueva=${encodeURIComponent(nueva)}`;
    fetch(url)
        .then(res => res.text())
        .then(data => {
            const msgDiv = document.getElementById("mensaje-pass");
            if (data === "ok") {
                msgDiv.innerHTML = "<div class='alert alert-success'>Contraseña actualizada</div>";
                document.getElementById("nueva-pass").value = "";
            } else {
                msgDiv.innerHTML = "<div class='alert alert-danger'>Error al cambiar contraseña</div>";
            }
        })
        .catch(err => {
            console.error(err);
            document.getElementById("mensaje-pass").innerHTML = "<div class='alert alert-danger'>Error de conexión</div>";
        });
}

function cargarTodosJugadores() {
    const tablaDiv = document.getElementById("tabla-todos-jugadores");
    const cuerpo = document.getElementById("cuerpo-todos-jugadores");

    fetch("php/listar_todos_jugadores.php")
        .then(res => res.json())
        .then(data => {
            cuerpo.innerHTML = "";
            if (data.length === 0) {
                cuerpo.innerHTML = "<tr><td colspan='6'>No hay jugadores registrados</td></tr>";
            } else {
                data.forEach(j => {
                    const fila = document.createElement("tr");
                    // Evitar que el admin se elimine a sí mismo
                    const esMismoUsuario = j.username === document.body.dataset.usuarioLogueado;
                    const btnEliminar = esMismoUsuario
                        ? "<button disabled class='btn btn-sm btn-outline-secondary'>Auto</button>"
                        : `<button onclick="eliminarJugador(${j.id})" class='btn btn-sm btn-danger'>Eliminar</button>`;

                    fila.innerHTML = `
                        <td>${j.username}</td>
                        <td>${j.admin}</td>
                        <td>${j.total_partidas}</td>
                        <td>${j.total_puntos}</td>
                        <td>
                            <input type="password" id="pass-${j.id}" class="form-control form-control-sm" placeholder="Nueva pass">
                            <button onclick="modificarPassword(${j.id})" class="btn btn-sm btn-warning mt-1">Cambiar</button>
                        </td>
                        <td>${btnEliminar}</td>
                    `;
                    cuerpo.appendChild(fila);
                });
            }
            tablaDiv.style.display = "block";
        })
        .catch(err => {
            console.error(err);
            cuerpo.innerHTML = "<tr><td colspan='6'>Error al cargar jugadores</td></tr>";
        });
}

function buscarJugador() {
    const username = document.getElementById("buscador-usuario").value.trim();
    if (!username) {
        alert("Escribe un nombre de usuario");
        return;
    }

    const url = `php/buscar_jugador.php?username=${encodeURIComponent(username)}`;
    const resultadoDiv = document.getElementById("resultado-busqueda");

    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                resultadoDiv.innerHTML = `<div class="alert alert-warning">${data.error}</div>`;
                return;
            }

            let html = `
                <h5>🔍 Jugador: <strong>${data.usuario.username}</strong></h5>
                <p><strong>Administrador:</strong> ${data.usuario.admin}</p>
                <h6>Historial de partidas:</h6>
            `;

            if (data.partidas.length > 0) {
                html += `<table class="table table-sm"><thead><tr><th>Palabra</th><th>Estado</th><th>Puntos</th><th>Fecha</th></tr></thead><tbody>`;
                data.partidas.forEach(p => {
                    const estado = p.estado === 'ganada' ? '✅ Ganada' : '❌ Perdida';
                    html += `<tr>
                        <td>${p.palabra}</td>
                        <td>${estado}</td>
                        <td>${p.puntos}</td>
                        <td>${p.fecha}</td>
                    </tr>`;
                });
                html += "</tbody></table>";
            } else {
                html += "<p>Este jugador no ha jugado ninguna partida.</p>";
            }

            resultadoDiv.innerHTML = html;
        })
        .catch(err => {
            console.error(err);
            resultadoDiv.innerHTML = "<div class='alert alert-danger'>Error al buscar jugador</div>";
        });
}

function modificarPassword(usuario_id) {
    const input = document.getElementById(`pass-${usuario_id}`);
    const nueva = input.value.trim();
    if (!nueva) {
        alert("Escribe una nueva contraseña");
        return;
    }

    const url = `php/modificar_password_admin.php?usuario_id=${usuario_id}&nueva=${encodeURIComponent(nueva)}`;
    fetch(url)
        .then(res => res.text())
        .then(data => {
            if (data === "ok") {
                alert("Contraseña actualizada");
                input.value = "";
            } else {
                alert("Error al cambiar contraseña");
            }
        })
        .catch(err => {
            console.error(err);
            alert("Error de conexión");
        });
}

function eliminarJugador(usuario_id) {
    if (!confirm("¿Seguro que deseas eliminar este jugador? Esta acción no se puede deshacer.")) {
        return;
    }

    const url = `php/eliminar_usuario.php?usuario_id=${usuario_id}`;
    fetch(url)
        .then(res => res.text())
        .then(data => {
            if (data === "ok") {
                alert("Jugador eliminado");
                cargarTodosJugadores(); // Recargar tabla
            } else {
                alert("Error al eliminar jugador");
            }
        })
        .catch(err => {
            console.error(err);
            alert("Error de conexión");
        });
}


function editarCategoria(id) {
    document.getElementById(`cat-nombre-${id}`).style.display = "none";
    document.getElementById(`cat-edit-${id}`).style.display = "block";
    document.getElementById(`btn-guardar-cat-${id}`).style.display = "inline-block";
}

function guardarCategoria(id) {
    const nuevoNombre = document.getElementById(`cat-edit-${id}`).value.trim();
    if (!nuevoNombre) {
        alert("Nombre no válido");
        return;
    }
    const url = `php/admin_categorias.php?accion=editar&id=${id}&nombre=${encodeURIComponent(nuevoNombre)}`;
    fetch(url)
        .then(res => res.text())
        .then(data => {
            if (data === "ok") {
                document.getElementById(`cat-nombre-${id}`).textContent = nuevoNombre;
                document.getElementById(`cat-nombre-${id}`).style.display = "inline";
                document.getElementById(`cat-edit-${id}`).style.display = "none";
                document.getElementById(`btn-guardar-cat-${id}`).style.display = "none";
            } else {
                alert("Error al guardar categoría");
            }
        });
}

function eliminarCategoria(id) {
    if (!confirm("¿Eliminar categoría? ¡También se borrarán sus palabras!")) return;
    const url = `php/admin_categorias.php?accion=eliminar&id=${id}`;
    fetch(url)
        .then(res => res.text())
        .then(data => {
            if (data === "ok") {
                document.getElementById(`cat-fila-${id}`).remove();
            } else {
                alert("Error al eliminar categoría");
            }
        });
}

function editarPalabra(id) {
    document.getElementById(`pal-texto-${id}`).style.display = "none";
    document.getElementById(`pal-cat-${id}`).style.display = "none";
    document.getElementById(`pal-edit-${id}`).style.display = "block";
    document.getElementById(`pal-cat-edit-${id}`).style.display = "block";
}

function guardarPalabra(id) {
    const nuevaPalabra = document.getElementById(`pal-edit-${id}`).value.trim();
    const nuevaCat = document.getElementById(`pal-cat-edit-${id}`).value;
    if (!nuevaPalabra || !nuevaCat) {
        alert("Completa todos los campos");
        return;
    }
    const url = `php/admin_palabras.php?accion=editar&id=${id}&palabra=${encodeURIComponent(nuevaPalabra)}&categoria_id=${nuevaCat}`;
    fetch(url)
        .then(res => res.text())
        .then(data => {
            if (data === "ok") {
                // Actualizar vista
                document.getElementById(`pal-texto-${id}`).textContent = nuevaPalabra;
                // Para la categoría, necesitamos su nombre → lo sacamos del select
                const select = document.getElementById(`pal-cat-edit-${id}`);
                const nombreCat = select.options[select.selectedIndex].text;
                document.getElementById(`pal-cat-${id}`).textContent = nombreCat;

                // Ocultar campos de edición
                document.getElementById(`pal-texto-${id}`).style.display = "inline";
                document.getElementById(`pal-cat-${id}`).style.display = "inline";
                document.getElementById(`pal-edit-${id}`).style.display = "none";
                document.getElementById(`pal-cat-edit-${id}`).style.display = "none";
            } else {
                alert("Error al guardar palabra");
            }
        });
}

function eliminarPalabra(id) {
    if (!confirm("¿Eliminar esta palabra?")) return;
    const url = `php/admin_palabras.php?accion=eliminar&id=${id}`;
    fetch(url)
        .then(res => res.text())
        .then(data => {
            if (data === "ok") {
                document.getElementById(`pal-fila-${id}`).remove();
            } else {
                alert("Error al eliminar palabra");
            }
        });
}



