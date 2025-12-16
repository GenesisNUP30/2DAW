var codigo_global = null;

function validarEmail(email) {
    // Expresión regular para validar un correo electrónico
    const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return regex.test(email);
}

async function cargarClientes() {
    const tabla = document.getElementById("tabla-clientes");
    const tbody = tabla.querySelector("tbody");
    const cargando = document.getElementById("cargando");

    try {
        const respuesta = await fetch("php/listarclientes.php");
        const datos = await respuesta.json();

        // Destruir DataTable si ya existía
        if ($.fn.DataTable.isDataTable('#tabla-clientes')) {
            $('#tabla-clientes').DataTable().destroy();
        }

        tbody.innerHTML = ""; 

        if (datos.length > 0) {
            datos.forEach(cliente => {
                const fila = document.createElement("tr");
                fila.innerHTML = `
                    <td>${cliente.id}</td>
                    <td>${cliente.nombre}</td>
                    <td>${cliente.apellidos}</td>
                    <td>${cliente.telefono}</td>
                    <td>${cliente.email}</td>
                    <td>${cliente.direccion}</td>
                    <td>${cliente.fecha_alta}</td>
                    <td>
                        <button onclick="eliminarcliente(${cliente.id}, this);">Eliminar</button>
                        <button onclick="modificarcliente(${cliente.id});">Modificar</button>
                    </td>
                `;
                
                fila.onclick = function () {
                    const codigo_cliente = cliente.id;
                    const filainformacion = document.createElement("tr");
                    filainformacion.style.display = "block";
                    const url = `php/mostrarmascotascliente.php?codigo=${codigo_cliente}`;

                    fetch(url)
                        .then(res => res.json())
                        .then(data => {
                            filainformacion.innerHTML = "";
                            if (data.length > 0) {
                                data.forEach(mascota => {
                                    filainformacion.innerHTML += `
                                        <td colspan="8">
                                            Mascota: <strong>${mascota.nombre}</strong>
                                            Mascota de <strong>${cliente.nombre}</strong>
                                        </td>
                                    `;
                                    fila.parentNode.insertBefore(filainformacion, fila.nextSibling);
                                });
                            } else {
                                filainformacion.innerHTML = `<td colspan="8">No hay mascotas para este cliente.</td>`;
                                fila.parentNode.insertBefore(filainformacion, fila.nextSibling);
                            }
                        })
                        .catch(error => console.error(error));
                };

                tbody.appendChild(fila);
            });

            // Inicializar DataTable después de agregar filas
            $('#tabla-clientes').DataTable({
                pageLength: 5,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                }
            });

            tabla.style.display = "table";
            cargando.style.display = "none";
        } else {
            tabla.style.display = "none";
            cargando.textContent = "No hay clientes.";
        }

    } catch (error) {
        tabla.style.display = "none";
        cargando.textContent = "Error al cargar los datos.";
        console.error(error);
    }
}


async function cargarmascotas() {
    const tabla = document.getElementById("tabla-mascotas");
    const tbody = tabla.querySelector("tbody");
    const cargando = document.getElementById("cargando-mascotas");

    try {
        // Petición al PHP que devuelve JSON
        const respuesta = await fetch("php/listarmascotas.php");
        const datos = await respuesta.json();

        tbody.innerHTML = "";
        // Si hay mascotas
        if (datos.length > 0) {
            datos.forEach(mascota => {
                let icono = "";
                switch (mascota.especie.toLowerCase()) {
                    case "perro":
                        icono = "img/perro.png";
                        break;
                    case "gato":
                        icono = "img/gato.png";
                        break;
                    case "gata":
                        icono = "img/gato.png";
                        break;
                    case "ave":
                        icono = "img/ave.png";
                        break;
                    case "reptil":
                        icono = "img/reptil.png";
                        break;
                }
                const fila = document.createElement("tr");
                fila.innerHTML = `
                
                    <td>${mascota.nombre_mascota}</td>
                    <td>${mascota.especie}</td>
                    <td>${mascota.raza}</td>
                    <td>${mascota.fecha_nacimiento}</td>
                    <td>${mascota.nombre_cliente}</td>
                    <td>
                    <img src="${icono}" alt="Icono de mascota" width="50" height="50">
                    </td>
                    <td>
                    <button onclick="eliminarmascota(${mascota.id}, this);">Eliminar</button>
                    </td>

                `;
                tbody.appendChild(fila);
            });
            tabla.style.display = "table";
            cargando.style.display = "none";
        } else {
            tabla.style.display = "none";
            cargando.textContent = "No hay mascotas.";
        }

    } catch (error) {
        tabla.style.display = "none";
        cargando.textContent = "Error al cargar los datos.";
        console.error(error);
    }
}

async function selectcliente() {
    try {
        // Petición al PHP que devuelve JSON
        const respuesta = await fetch("php/listarclientes.php");
        const datos = await respuesta.json();

        const select = document.getElementById("cliente");
        select.innerHTML = '<option value="">Seleccione un cliente</option>';
        // Si hay mascotas
        if (datos.length > 0) {
            datos.forEach(cliente => {
                const option = document.createElement("option");
                option.value = cliente.id;
                option.textContent = cliente.nombre;
                select.appendChild(option);
            });
        } else {
            select.innerHTML = "<option value=''>No hay clientes</option>";
        }
    } catch (error) {
        console.error(error);
    }

}

document.addEventListener("DOMContentLoaded", () => {
    cargarClientes();
    cargarmascotas();
    selectcliente();
});



function crearcliente() {
    const nombre = document.getElementById("nombre").value;
    const apellidos = document.getElementById("apellidos").value;
    const telefono = document.getElementById("telefono").value;
    const email = document.getElementById("email").value;
    const direccion = document.getElementById("direccion").value;

    if (nombre === "" || telefono === "" || email === "" || direccion === "") {
        alert("Debe rellenar los campos obligatorios");
        return;
    }
    if (!validarEmail(email)) {
        alert("El email no es válido");
        return;
    }

    telefonocliente = parseInt(telefono);
    if (isNaN(telefonocliente)) {
        alert("El telefono no es un número válido");
        return;
    }

    const url = `php/crearcliente.php?nombre=${encodeURIComponent(nombre)}&apellidos=${encodeURIComponent(apellidos)}&telefono=${encodeURIComponent(telefono)}&email=${encodeURIComponent(email)}&direccion=${encodeURIComponent(direccion)}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            console.log(data);
            alert(data.message);
            cargarClientes();
        })
        .catch(error => console.error(error));
}

function modificarcliente(codigo) {
    const div = document.getElementById("cliente-modificar");
    const cargando = document.getElementById("cargando-modificar");

    const url = `php/modificarcliente.php?codigo=${codigo}`;
    codigo_global = codigo;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            console.log(data);
            div.style.display = "block";

            cargando.style.display = "none";

            document.getElementById("nombre2").value = data[0].nombre;
            document.getElementById("apellidos2").value = data[0].apellidos;
            document.getElementById("telefono2").value = data[0].telefono;
            document.getElementById("email2").value = data[0].email;
            document.getElementById("direccion2").value = data[0].direccion;
        })
        .catch(error => console.error(error));
}

function modificarcliente2() {
    const nombre = document.getElementById("nombre2").value;
    const apellidos = document.getElementById("apellidos2").value;
    const telefono = document.getElementById("telefono2").value;
    const email = document.getElementById("email2").value;
    const direccion = document.getElementById("direccion2").value;

    telefonocliente = parseInt(telefono);
    if (isNaN(telefonocliente)) {
        alert("El telefono no es un número válido");
        return;
    }

    const div = document.getElementById("cliente-modificar");

    const url = `php/modificarcliente2.php?codigo=${codigo_global}&nombre=${encodeURIComponent(nombre)}&apellidos=${encodeURIComponent(apellidos)}&telefono=${encodeURIComponent(telefono)}&email=${encodeURIComponent(email)}&direccion=${encodeURIComponent(direccion)}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            console.log(data);
            if (data.status === "error") {
                alert(data.mensaje);

                return;
            }
            alert(data.mensaje);
            div.style.display = "none";

            cargarClientes();

        })
        .catch(error => console.error(error));
}

function eliminarcliente(codigo, boton) {
    const tabla = document.getElementById("tabla-clientes");
    const tbody = tabla.querySelector("tbody");
    const url = `php/eliminarcliente.php?codigo=${codigo}`;

    const confirmado = confirm("¿Estás seguro de que quieres borrar el cliente?");
    if (confirmado) {
        fetch(url)
            .then(res => res.json())
            .then(data => {
                console.log(data);
                if (data.status === "success") {
                    alert(data.mensaje);
                    celda = boton.parentNode;
                    fila = celda.parentNode;

                    console.log(celda);
                    console.log(fila);

                    tbody.removeChild(fila);
                } else {
                    alert(data.mensaje);
                }
            })
            .catch(error => console.error(error));
    }
}

function buscarCliente() {
    const nombre = document.getElementById("buscar-cliente").value;
    const tabla = document.getElementById("busqueda");
    const tbody = tabla.querySelector("tbody");

    if (!nombre) {
        alert("Escribe un nombre");
        return;
    }
    const url = `php/buscarcliente.php?cliente=${encodeURIComponent(nombre)}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            tbody.innerHTML = "";
            data.forEach(cliente => {
                tabla.style.display = "block";
                const fila = document.createElement("tr");
                fila.innerHTML = `
                    <td>${cliente.id}</td>
                    <td>${cliente.nombre}</td>
                    <td>${cliente.apellidos}</td>
                    <td>${cliente.telefono}</td>
                    <td>${cliente.email}</td>
                    <td>${cliente.direccion}</td>
                    <td>${cliente.fecha_alta}</td>
                    <td>
                    <button onclick="eliminarcliente(${cliente.id}, this);">Eliminar</button>
                    <button onclick="modificarcliente(${cliente.id});">Modificar</button>
                    </td>
                `;
                tbody.appendChild(fila);
            });
        })
        .catch(error => console.error(error));
}

function buscarClienteTelefono() {
    const telefono = document.getElementById("buscar-telefono").value;
    const tabla = document.getElementById("busqueda");
    const tbody = tabla.querySelector("tbody");

    if (!telefono) {
        alert("Escribe un telefono");
        return;
    }
    const url = `php/buscarclientetelefono.php?telefono=${encodeURIComponent(telefono)}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            tbody.innerHTML = "";
            data.forEach(cliente => {
                tabla.style.display = "block";

                const fila = document.createElement("tr");
                fila.innerHTML = `
                    <td>${cliente.id}</td>
                    <td>${cliente.nombre}</td>
                    <td>${cliente.apellidos}</td>
                    <td>${cliente.telefono}</td>
                    <td>${cliente.email}</td>
                    <td>${cliente.direccion}</td>
                    <td>${cliente.fecha_alta}</td>
                    <td>
                    <button onclick="eliminarcliente(${cliente.id}, this);">Eliminar</button>
                    <button onclick="modificarcliente(${cliente.id});">Modificar</button>
                    </td>
                `;
                tbody.appendChild(fila);
            });
        })
        .catch(error => console.error(error));
}


function crearmascota() {
    const nombre = document.getElementById("nombre_mascota").value;
    const especie = document.getElementById("especie").value;
    const raza = document.getElementById("raza").value;
    const fechanacimiento = document.getElementById("fechanacimiento").value;
    const id_cliente = document.getElementById("cliente").value;

    if (id_cliente === "") {
        alert("Debe seleccionar un cliente.");
        return;
    }

    const url = `php/crearmascota.php?nombre=${encodeURIComponent(nombre)}&especie=${encodeURIComponent(especie)}&raza=${encodeURIComponent(raza)}&fechanacimiento=${encodeURIComponent(fechanacimiento)}&id_cliente=${encodeURIComponent(id_cliente)}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            console.log(data);
            alert(data.mensaje);
            cargarmascotas();
        })
        .catch(error => console.error(error));
}


function eliminarmascota(codigo, boton) {
    const tabla = document.getElementById("tabla-mascotas");
    const tbody = tabla.querySelector("tbody");
    const url = `php/eliminarmascota.php?codigo=${codigo}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            console.log(data);
            if (data.status === "success") {
                alert(data.mensaje);
                celda = boton.parentNode;
                fila = celda.parentNode;

                console.log(celda);
                console.log(fila);

                tbody.removeChild(fila);
            } else {
                alert(data.mensaje);
            }
        })
        .catch(error => console.error(error));
}

function verestadisticas() {
    const div = document.getElementById("estadisticas");
    const tabla = document.getElementById("tabla-estadisticas");
    const tbody = tabla.querySelector("tbody");

    const url = `php/verestadisticas.php`;
    fetch(url)
        .then(res => res.json())
        .then(data => {
            tbody.innerHTML = "";
            console.log(data);
            if (data.total_clientes === 0) {
                div.style.display = "none";
                return;
            }
            div.style.display = "block";

            const fila = document.createElement("tr");
            fila.innerHTML = `
                <td>${data.total_clientes}</td>
                <td>${data.total_mascotas}</td>
                <td>${data.mascota_comun}</td>
                <td>${data.cliente_con_mas_mascotas}</td>
            `;
            tbody.appendChild(fila);
        })
        .catch(error => console.error(error));
}

