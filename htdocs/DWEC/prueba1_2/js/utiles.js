var codigo_global = null;

document.addEventListener("DOMContentLoaded", () => {
    cargarCoches(); // ← primera carga
});
// Cuando la página carga
async function cargarCoches() {
    const tabla = document.getElementById("tabla-coches");
    const tbody = tabla.querySelector("tbody");
    const cargando = document.getElementById("cargando");

    try {
        // Petición al PHP que devuelve JSON
        const respuesta = await fetch("php/listarcoches.php");
        const datos = await respuesta.json();

        tbody.innerHTML = "";

        // Si hay coches
        if (datos.length > 0) {
            datos.forEach(coche => {
                const fila = document.createElement("tr");
                if (coche.vendido === 1) {
                    fila.classList.add("fila-vendida");
                }
                fila.innerHTML = `
                    <td>${coche.marca}</td>
                    <td>${coche.modelo}</td>
                    <td>${coche.precio}</td>
                    <td>${coche.provincia}</td>
                    <td>${coche.poblacion}</td>
                    <td>
                    <input type="checkbox" ${coche.vendido === 1 ? 'checked' : ''} 
                    onchange="actualizarVendido(${coche.codigo}, this.checked);"></td>
                    <td>
                    <button onclick="eliminarCoche(${coche.codigo}, this);">Eliminar</button>
                    <button onclick="modificarCoche(${coche.codigo});">Modificar</button>
                    </td>
                `;
                tbody.appendChild(fila);
            });
            cargando.style.display = "none";
            tabla.style.display = "table";
        } else {
            cargando.textContent = "No hay coches en la base de datos.";
        }

    } catch (error) {
        cargando.textContent = "Error al cargar los datos.";
        console.error(error);
    }
}

function actualizarVendido(codigo, valor) {
    if (valor === true) {
        valor = 1;
    } else {
        valor = 0;
    }

    const url = `php/actualizar_vendido.php?codigo=${codigo}&vendido=${valor}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            console.log(data);
            if (data.status === "error") {
                alert(data.mensaje);
            } else {
                alert(data.mensaje);
                cargarCoches();
            }
        })
        .catch(error => console.error(error));
}

function agregarCoche() {
    const marca = document.getElementById("marca").value;
    const modelo = document.getElementById("modelo").value;
    const precio = document.getElementById("precio").value;
    const provincia = document.getElementById("provincia").value;
    const poblacion = document.getElementById("poblacion").value;

    if (marca === "" || modelo === "" || precio === "" || provincia === "" || poblacion === "") {
        alert("Todos los campos son obligatorios.");
        return;
    }

    const numprecio = parseFloat(precio);
    if (isNaN(numprecio) || numprecio <= 0) {
        alert("El precio no es un número válido.");
        return;
    }

    const tabla = document.getElementById("tabla-coches");
    const cargando = document.getElementById("cargando");

    const url = `php/agregar_coche.php?marca=${encodeURIComponent(marca)}&modelo=${encodeURIComponent(modelo)}&precio=${encodeURIComponent(precio)}&provincia=${encodeURIComponent(provincia)}&poblacion=${encodeURIComponent(poblacion)}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            console.log(data);
            if (data.status === "error") {
                cargando.textContent = data.mensaje;
                return;
            }

            cargarCoches();

            document.getElementById("marca").value = "";
            document.getElementById("modelo").value = "";
            document.getElementById("precio").value = "";
            document.getElementById("provincia").value = "";
            document.getElementById("poblacion").value = "";

            cargando.style.display = "none";
            tabla.style.display = "table";

        })
        .catch(error => console.error(error));
}

function eliminarCoche(codigo, boton) {
    const tabla = document.getElementById("tabla-coches");
    const tbody = tabla.querySelector("tbody");
    const url = `php/eliminar_coche.php?codigo=${codigo}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            console.log(data);
            if (data.status === "success") {


                celda = boton.parentNode;
                fila = celda.parentNode;

                console.log(celda);
                console.log(fila);

                tbody.removeChild(fila);
                alert(data.mensaje);
                cargarCoches();
            } else {
                alert(data.mensaje);
            }
        })
        .catch(error => console.error(error));
}

function modificarCoche(codigo) {
    const div = document.getElementById("informacion-modificar");
    const cargando = document.getElementById("cargando-modificar");

    const url = `php/modificar_coche.php?codigo=${codigo}`;
    codigo_global = codigo;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            div.style.display = "block";

            cargando.style.display = "none";

            document.getElementById("marca2").value = data[0].marca;
            document.getElementById("modelo2").value = data[0].modelo;
            document.getElementById("precio2").value = data[0].precio;
            document.getElementById("provincia2").value = data[0].provincia;
            document.getElementById("poblacion2").value = data[0].poblacion;
            console.log(data);
        })
        .catch(error => console.error(error));
}

function modificarCoche2() {
    const marca = document.getElementById("marca2").value;
    const modelo = document.getElementById("modelo2").value;
    const precio = document.getElementById("precio2").value;
    const provincia = document.getElementById("provincia2").value;
    const poblacion = document.getElementById("poblacion2").value;

    const div = document.getElementById("informacion-modificar");

    const url = `php/modificar_coche2.php?codigo=${codigo_global}&marca=${encodeURIComponent(marca)}&modelo=${encodeURIComponent(modelo)}&precio=${encodeURIComponent(precio)}&provincia=${encodeURIComponent(provincia)}&poblacion=${encodeURIComponent(poblacion)}`;

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

            cargarCoches();

        })
        .catch(error => console.error(error));

}

function verEstadisticas() {
    const div = document.getElementById("estadisticas");
    const tabla = document.getElementById("tabla-estadisticas");
    const tbody = tabla.querySelector("tbody");

    const url = `php/ver_estadisticas.php`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            tbody.innerHTML = "";
            console.log(data);

            if (data.total_coches === 0) {
                div.style.display = "none";
                return;
            }

            div.style.display = "block";

            const fila = document.createElement("tr");
            fila.innerHTML = `
                <td>${data.total_coches}</td>
                <td>${data.coches_vendidos}</td>
                <td>${data.media_precio}</td>
                <td>${data.suma_precios}</td>
            `;
            tbody.appendChild(fila);
        })
        .catch(error => console.error(error));
}